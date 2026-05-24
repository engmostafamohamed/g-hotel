<?php

namespace App\Services\V1\CRM;

use App\Http\Repository\V1\CRM\RestaurantMenu\RestaurantMenuRepository;
use App\Models\HotelLocation;
use App\Models\MenuImport;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Services\FileUploadService;
use App\Traits\UsesHotelScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ZipArchive;
use App\Utils\FileUpload;
class RestaurantMenuService
{
    use UsesHotelScope;
    public function __construct(
        protected RestaurantMenuRepository $repository,
        protected FileUploadService $fileUploadService
    ) {}
    public function importMenuFromCSV($restaurant_id, $file, $location, $menuType, $updateExisting)
    {
        $restaurant = $this->repository->findRestaurantByLocation($location);
        $hotel = HotelLocation::where('property_code', $location)->first();

        if (!$restaurant) {
            throw new \Exception("Restaurant not found for location $location");
        }

        $restaurant = Restaurant::where('id', $restaurant_id)
            ->where('hotel_id', $hotel->id)
            ->first();

        if (!$restaurant) {
            throw new \Exception("Restaurant with ID $restaurant_id not found at location '$location'.");
        }

        if (!$file || $file->getClientOriginalExtension() !== 'zip') {
            return response()->json(['error' => 'Please upload a valid ZIP file'], 400);
        }

        $updateExisting =false;
        //  Upload ZIP file
        $zipPath = $file->storeAs('uploads/zips', uniqid() . '.zip', 'public');
        $zipFullPath = storage_path('app/public/' . $zipPath);
        $filePath = 'storage/' . $zipPath; // for saving in DB

        //  Extract ZIP file
        $extractPath = storage_path('app/public/uploads/imports/' . Str::random(10));
        File::makeDirectory($extractPath, 0755, true);

        $zip = new ZipArchive;
        if ($zip->open($zipFullPath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to extract ZIP file'], 500);
        }

        //  Find CSV file
        $csvFiles = File::files($extractPath);
        $csvFile = collect($csvFiles)->first(fn($f) => $f->getExtension() === 'csv');
        if (!$csvFile) {
            return response()->json(['error' => 'No CSV file found in ZIP'], 400);
        }

        //  Read CSV
        $handle = fopen($csvFile->getPathname(), 'r');
        $header = fgetcsv($handle);

        $errors = [];
        $newItems = $updatedItems = 0;

        while (($row = fgetcsv($handle)) !== false) {

            $data = array_combine($header, $row);

            try {
                if (!is_numeric($data['price'])) {
                    throw new \Exception('Invalid price format');
                }

                //  Extract image name (ignore folder + extension)
                $imageRelative = trim($data['image_path']);
                $imageName = pathinfo($imageRelative, PATHINFO_FILENAME); // e.g., "image1"

                //  Search image (match name only, ignore extension and case)
                $foundImage = collect(File::allFiles($extractPath))
                    ->first(function ($f) use ($imageName) {
                        $base = pathinfo($f->getFilename(), PATHINFO_FILENAME);
                        $ext = strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION));
                        // Allow jpg, jpeg, png, webp
                        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                        return strcasecmp($base, $imageName) === 0 && in_array($ext, $allowed);
                    });

                //  If not found, throw detailed error
                if (!$foundImage) {
                    $allFiles = collect(File::allFiles($extractPath))
                        ->map(fn($f) => $f->getPathname());
                    throw new \Exception("Image not found: {$imageName}. Available files: " . json_encode($allFiles));
                }


                //  Upload the image to local/S3
                $uploadedImagePath = FileUpload::uploadImageOnLocal(
                    new \Illuminate\Http\UploadedFile(
                        $foundImage->getPathname(),
                        $foundImage->getFilename(),
                        mime_content_type($foundImage->getPathname()), // <-- add MIME type
                        null,
                        true // mark test mode true (so it doesn’t move tmp file)
                    ),
                    'RestaurantMenuImages'
                );

                $category = $this->repository->findOrCreateCategory($restaurant, $data['category']);
                $item = $this->repository->storeMenuItem($category->id, [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'image_path' => $uploadedImagePath,
                    'price' => $data['price'],
                    'dietary_tags' => [], // Could parse from CSV later
                    'code' => $data['code'] ?? Str::uuid()->toString(),
                ], $updateExisting);

                $item->wasRecentlyCreated ? $newItems++ : $updatedItems++;

            } catch (\Exception $e) {
                $errors[] = ['row' => $data, 'error' => $e->getMessage()];
            }
        }

        fclose($handle);

        $import = MenuImport::create([
            'import_id' => $importId = 'imp_' . Str::random(5),
            'restaurant_id' => $restaurant->id,
            'hotel_location_id' => $restaurant->hotel_id,
            'menu_type' => $menuType,
            'csv_file_path' => $filePath,
            'new_items' => $newItems,
            'updated_items' => $updatedItems,
            'errors' => $errors,
            'report_url' => '/reports/import-placeholder',
        ]);

        return [
            'import_id' => $importId,
            'stats' => [
                'new_items' => $newItems,
                'updated_items' => $updatedItems,
                'errors' => $errors,
            ],
            'validation_report_url' => '/reports/import-' . $import->id,
        ];
    }


    public function getFullMenuGroupedByRestaurant(int $perPage)
    {
        $query = Restaurant::with([
            'hotelLocation:id,display_name,property_code',
            'menuCategories.menuItems',
        ]);

        if ($hotelId = $this->getHotelIdFromAuth()) {
            $query->where('hotel_id', $hotelId);
        }

        return $query->paginate($perPage);
    }
    public function getRestaurantMenuById($restaurantId)
    {
        $query = Restaurant::with([
            'hotelLocation:id,display_name,property_code',
            'menuCategories.menuItems',
        ])->where('id', $restaurantId);

        if ($hotelId = $this->getHotelIdFromAuth()) {
            $query->where('hotel_id', $hotelId);
        }

        return $query->firstOrFail();
    }

    public function deleteRestaurantMenu($restaurantId)
    {
        $hotelId = $this->getHotelIdFromAuth();

        $restaurantQuery = Restaurant::with('menuCategories.menuItems')->where('id', $restaurantId);

        if ($hotelId) {
            $restaurantQuery->where('hotel_id', $hotelId);
        }

        $restaurant = $restaurantQuery->firstOrFail();

        DB::transaction(function () use ($restaurant) {
            foreach ($restaurant->menuCategories as $category) {
                $category->menuItems()->delete();
                $category->delete();
            }
        });
    }
    public function updateMenuItem(int $itemId, array $data)
    {

        $item = MenuItem::query()->where('id', $itemId)->first();

        if ($hotelId = $this->getHotelIdFromAuth()) {
            // Join through related restaurant and hotel
            $item->whereHas('menuCategory.restaurant', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            });
        }
        if (!$item) {
            return ['status' => 'Menu_not_found'];
        }

        if (array_key_exists('name', $data)) {
            if (is_array($data['name'])) {
                $item->setTranslations('name', $data['name']);
            } else {
                $item->setTranslation('name', app()->getLocale(), $data['name']);
            }
        }

        if (array_key_exists('description', $data)) {
            if (is_array($data['description'])) {
                $item->setTranslations('description', $data['description']);
            } else {
                $item->setTranslation('description', app()->getLocale(), $data['description']);
            }
        }

        if (isset($data['price'])) {
            $item->price = $data['price'];
        }
        if (isset($data['image_path'])) {
            $item->image_path = FileUpload::uploadImageOnLocal($data['image_path'], 'RestaurantMenuImages');
            // $item->image_path = $this->fileUploadService->upload($data['image_path'], 'uploads/restaurantMenuImages');

        }

        if (isset($data['dietary_tags'])) {
            $item->dietary_tags = $data['dietary_tags'];
        }

        $item->save();

        return $item;
    }
}

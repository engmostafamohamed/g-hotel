<?php
namespace App\Services\V1\CRM;

use App\Http\Repository\V1\CRM\Service\ServiceRepository;
use App\Models\HotelLocation;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\FileUploadService;
use App\Traits\UsesHotelScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ServiceService
{
    use UsesHotelScope;
    public function __construct(
        protected FileUploadService $fileUploadService,
        protected ServiceRepository $repository
    ) {
    }
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $authHotelId = $this->getHotelIdFromAuth();

            if ($authHotelId && (!isset($data['hotel_id']) || $data['hotel_id'] != $authHotelId)) {
                abort(403, 'Unauthorized to create service for this hotel.');
            }

            $hotel = HotelLocation::findOrFail($data['hotel_id']);

            $category = ServiceCategory::where('name', $data['category'])->first();

            $service = Service::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'category_id' => $category->id,
                'price' => $data['price'],
                'sync_with_pms' => $data['sync_with_pms'] ?? false,
                'pms_sync_status' => 'pending',
                'version' => 1,
                'locations' => $data['locations'],
                'hotel_id' => $data['hotel_id']
            ]);

            $hotel = HotelLocation::findOrFail($data['hotel_id']);
            $service->hotelLocation()->associate($hotel);

            if (isset($data['image'])) {
                $resizedImage = Image::make($data['image'])
                    ->resize(800, 600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                $tempPath = storage_path('app/temp/' . Str::uuid() . '.jpg');
                $resizedImage->save($tempPath);

                $uploaded = new UploadedFile($tempPath, basename($tempPath), 'image/jpeg', null, true);

                $filePath = app(FileUploadService::class)->upload($uploaded, 'uploads/services');
                $service->update(['image_path' => $filePath]);

                unlink($tempPath);
            }

            $this->validateTimeSlots($data['time_slots']);

            foreach ($data['time_slots'] as $slot) {
                $service->timeSlots()->create($slot);
            }

            // Simulate PMS Sync Logic Here (queue job, event dispatch, etc.)

            return $service;
        });
    }

    private function validateTimeSlots(array $slots): void
    {
        foreach ($slots as $i => $a) {
            foreach ($slots as $j => $b) {
                if ($i === $j)
                    continue;

                $startA = strtotime($a['start']);
                $endA = strtotime($a['end']);
                $startB = strtotime($b['start']);
                $endB = strtotime($b['end']);

                if (($startA < $endB) && ($startB < $endA)) {
                    throw new \Exception("Time slot overlap between {$a['start']}–{$a['end']} and {$b['start']}–{$b['end']}");
                }
            }
        }
    }

    public function update(array $data, int $id)
    {
        $hotelId = $this->getHotelIdFromAuth();

        return DB::transaction(function () use ($data, $id, $hotelId) {
            $query = Service::where('id', $id);

            if ($hotelId) {
                $query->where('hotel_id', $hotelId);
            }

            $service = $query->firstOrFail();

            if (isset($data['category'])) {
                $category = ServiceCategory::where('name', $data['category'])->firstOrFail();
                $data['category_id'] = $category->id;
                unset($data['category']);
            }

            $service->update($data);

            if (isset($data['image'])) {
                $image = Image::make($data['image'])->resize(800, 600);
                $path = 'uploads/services/' . uniqid() . '.jpg';
                $image->save(public_path($path));
                $service->update(['image_path' => $path]);
            }

            if (isset($data['locations'])) {
                $service->locations = $data['locations'];
                $service->save();
            }

            if (isset($data['time_slots'])) {
                foreach ($data['time_slots'] as $slot) {
                    $service->timeSlots()->create($slot);
                }
            }
            return $service->fresh(['category', 'hotelLocation', 'timeSlots']);
        });
    }
    public function getAll($hotelId, $perPage = 10, array $filters = [])
    {
        return $this->repository->getAllPaginated($hotelId, $perPage, $filters);
    }

    public function getOne($hotelId, int $id)
    {
        return $this->repository->find($hotelId, $id);
    }
    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
    public function setAvailability($serviceId, array $data)
    {
        return $this->repository->setAvailability($serviceId, $data);
    }
}
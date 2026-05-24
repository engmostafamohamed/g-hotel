<?php

namespace App\Http\Repository\V1\CRM\Service;
use App\Models\Service;
use App\Traits\UsesHotelScope;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Schedule;
class ServiceRepository
{
    use UsesHotelScope;
    public function showServicesRepository(Request $request)
    {
        $hotelId = $request->header('hotel_id');
        if (!$hotelId) {
            return ['status' => 'not_found'];
        }
        // Get only currently open restaurants
        $currentDay = strtolower(Carbon::now()->format('l'));
        // $currentTime = Carbon::now()->format('H:i');
        $currentTime = Carbon::now('Africa/Cairo')->format('H:i');

        $query = Service::with(['category', 'hotelLocation', 'timeSlots', 'schedules', 'exceptions'])->where('hotel_id', $hotelId)->paginate(10);

        if ($query->isEmpty()) {
            return ['status' => 'not_found'];
        }

        $data = $query->through(function ($item) {
            return [
                'service_name' => $item->getTranslation('name', app()->getLocale()),
                'item->service_image_url' => $item->image_url,
                'hotel_id' => $item->hotel_id,
                'service_description' => $item->getTranslation('description', app()->getLocale()),
            ];
        });

        return [
            'status' => 'success',
            'services' => $data,
        ];
    }

    public function storeServiceRepository(Request $request)
    {
        try {
            if (!$request->hasFile('service_image')) {
                return ['status' => 'image_not_found'];
            }

            $imagePath = FileUpload::uploadImageOnLocal(
                $request->file('service_image'),
                'Service'
            );

            $record = Service::create([
                'name' => [
                    'en' => $request->input('service_name.en'),
                    'ar' => $request->input('service_name.ar'),
                ],
                'image_url' => $imagePath,
                'description' => [
                    'en' => $request->input('service_description.en'),
                    'ar' => $request->input('service_description.ar'),
                ],
                'hotel_id' => $request->input('hotel_id'),
            ]);

            return [
                'status' => 'success',
                'data' => $record,
            ];

        } catch (QueryException $e) {
            // Log::error('DB Error when creating service: ' . $e->getMessage());
            return ['status' => 'db_error'];

        } catch (Exception $e) {
            // Log::error('Unexpected error when creating service: ' . $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function getAllPaginated($hotelId, int $perPage = 10, array $filters = [])
    {
        // return Service::with(['category', 'hotelLocation', 'timeSlots'])
        //     ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
        //     ->latest()
        //     ->paginate($perPage);
        return Service::with(['category', 'hotelLocation', 'timeSlots', 'schedules', 'exceptions'])
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->when(isset($filters['category_id']), function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            })
            ->when(is_null($hotelId) && isset($filters['hotel_location_id']), function ($q) use ($filters) {
                $q->where('hotel_id', $filters['hotel_location_id']);
            })
            ->latest()
            ->paginate($perPage);
    }

    public function find($hotelId, int $id): Service
    {
        return Service::with(['category', 'hotelLocation', 'timeSlots', 'schedules', 'exceptions'])
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->findOrFail($id);
    }
    public function update(int $id, array $data): Service
    {
        $service = Service::findOrFail($id);
        $service->update($data);
        return $service->fresh();
    }

    public function delete(int $id): void
    {
        $hotelId = $this->getHotelIdFromAuth();

        $query = Service::where('id', $id);

        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }

        $service = $query->firstOrFail();

        $service->delete();
    }

    public function setAvailability($serviceId, array $data)
    {
        $service = Service::findOrFail($serviceId);

        $hotelId = $this->getHotelIdFromAuth();
        if ($hotelId && $service->hotel_id != $hotelId) {
            throw new Exception("You are not allowed to access this service.");
        }

        DB::transaction(function () use ($service, $data) {
            $service->schedules()->delete();
            foreach ($data['schedules'] as $schedule) {
                $service->schedules()->create($schedule);
            }

            $service->exceptions()->delete();
            if (isset($data['exceptions'])) {
                foreach ($data['exceptions'] as $exception) {
                    $service->exceptions()->create($exception);
                }
            }

            $service->timeSlots()->delete();
            foreach ($data['time_slots'] as $slot) {
                $service->timeSlots()->create($slot);
            }
        });
        return $service->load(['schedules', 'exceptions', 'timeSlots']);
    }
}

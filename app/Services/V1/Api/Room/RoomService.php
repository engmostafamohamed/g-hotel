<?php
namespace App\Services\V1\Api\Room;

use App\DataTransferObjects\RoomDTOs\Api\RoomDTO;
use App\Http\Repository\V1\Api\Room\RoomRepository;

use App\Models\Category;
use App\Models\Feature;
use App\Models\View;
use App\Models\Bed;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;

class RoomService
{
    public function __construct(private RoomRepository $repository) {}

    public function getFilters(): Collection
    {
        $hotelId = current_hotel_id();

        if (!$hotelId) {
            throw new AuthorizationException(__('room.hotel_context_required'));
        }

        $categories = $this->repository->getCategoriesForFilter($hotelId);
        $features   = $this->repository->getFeaturesForFilter($hotelId);
        $views      = $this->repository->getViewsForFilter();

        return collect([
            'categories' => $categories,
            'features'   => $features,
            'views'      => $views,
        ]);
    }
}
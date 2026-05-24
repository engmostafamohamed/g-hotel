<?php

namespace App\DataTransferObjects\CategoryDTOs;

use App\Http\Requests\V1\CRM\Category\StoreCategoryRequest;
use App\Http\Requests\V1\CRM\Category\UpdateCategoryRequest;

class CategoryDTO
{
    public function __construct(
        public array $category_name,
        public array $category_description,
        public int $hotel_id,
        public array $feature_ids = [],
        public mixed $category_images = [],
        public ?int $max_adults = null,
        public ?int $max_children = null,
        public ?bool $infants_allowed = null,
        public ?array $policies = null,
        public ?array $bed_data = null,
    ) {}

    public static function fromRequest(StoreCategoryRequest|UpdateCategoryRequest $request): self
    {
        return new self(
            category_name: $request->input('category_name', []),
            category_description: $request->input('category_description', []),
            hotel_id: $request->input('hotel_id'),
            feature_ids: $request->input('feature_ids', []),
            category_images: $request->file('category_images', []),
            max_adults: $request->input('max_adults'),
            max_children: $request->input('max_children'),
            infants_allowed: $request->boolean('infants_allowed'),
            policies: $request->input('policies', ['en' => [], 'ar' => []]),
            bed_data: $request->input('bed_data', []),
        );
    }
}
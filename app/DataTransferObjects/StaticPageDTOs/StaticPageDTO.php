<?php

namespace App\DataTransferObjects\StaticPageDTOs;

use App\Http\Requests\V1\CRM\StaticPage\UpdateStaticPageRequest;



class StaticPageDTO
{
    public function __construct(
        public ?array $title,
        public ?array $content,
        public ?bool $is_active
    ) {}

    public static function fromRequest(UpdateStaticPageRequest $request): self
    {
        return new self(
            title: $request->input('title'),
            content: $request->input('content'),
            is_active: $request->input('is_active')
        );
    }
}

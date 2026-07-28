<?php

namespace Modules\Pages\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image,
            'image_alt' => $this->image_alt,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'og_image_alt' => $this->og_image_alt,
            'is_analytics_allowed' => $this->is_analytics_allowed,
            'is_visible_in_nav' => $this->is_visible_in_nav,
            'is_enabled' => $this->is_enabled,
            'is_indexable' => $this->is_indexable,
            'is_readonly' => $this->is_readonly,
            // 'readonly_by' => $this->readonly_by,
            'readonly_at' => $this->readonly_at?->format('l, F jS Y, g:i A'),
            'readonly_reason' => $this->readonly_reason,
            'order' => $this->order,
            'deleted_at' => $this->deleted_at?->format('l, F jS Y, g:i A'),
            'path' => '/'.$this->path,
            'depth' => $this->depth,
            'created_at' => $this->created_at->format('l, F jS Y, g:i A'),
            'updated_at' => $this->updated_at->format('l, F jS Y, g:i A'),
        ];
    }
}

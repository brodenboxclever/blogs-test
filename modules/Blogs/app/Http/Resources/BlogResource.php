<?php

namespace Modules\Blogs\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'image' => $this->image,
            'image_alt' => $this->image_alt,
            'is_readonly' => $this->is_readonly,
            'page' => $this->page ? [
                // 'id' => $this->page?->uuid,
                // 'uuid' => $this->page?->uuid,
                // 'path' => $this->page?->getPath(),
                // 'title' => $this->page?->title,
            ] : null,
            // 'readonly_by' => $this->readonly_by->name,
            'readonly_at' => $this->readonly_at?->format('F jS Y, g:i A'),
            'readonly_reason' => $this->readonly_reason,
            'order' => $this->order,
            'deleted_at' => $this->deleted_at?->format('F jS Y, g:i A'),
            'created_at' => $this->created_at->format('F jS Y, g:i A'),
            'updated_at' => $this->updated_at->format('F jS Y, g:i A'),
        ];
    }
}

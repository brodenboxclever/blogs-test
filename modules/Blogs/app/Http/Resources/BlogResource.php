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
            'order' => $this->order,
            'deleted_at' => $this->deleted_at?->format('M j Y, g:i A'),
            'created_at' => $this->created_at->format('M j Y, g:i A'),
            'updated_at' => $this->updated_at->format('M j Y, g:i A'),
        ];
    }
}

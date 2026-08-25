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
            'is_enabled' => $this->is_enabled,
            'order' => $this->order,

            $this->mergeWhen($request->user(), [
                'created_at' => $this->created_at->format('M d Y g:ia'),
                'updated_at' => $this->updated_at->format('M d Y g:ia'),
                'deleted_at' => $this->deleted_at?->format('M d Y g:ia'),
                'is_readonly' => $this->is_readonly,
            ]),
        ];
    }
}

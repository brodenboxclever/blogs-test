<?php

namespace Modules\Blogs\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'is_enabled' => $this->is_enabled,
            'published_at' => $this->published_at?->format('M d Y g:ia'),

            'blog' => BlogResource::make($this->whenLoaded('blog')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'comments_count' => $this->whenCounted('comments'),

            $this->mergeWhen($request->user(), [
                'unpublished_at' => $this->unpublished_at?->format('M d Y g:ia'),
                'created_at' => $this->created_at->format('M d Y g:ia'),
                'updated_at' => $this->updated_at->format('M d Y g:ia'),
                'deleted_at' => $this->deleted_at?->format('M d Y g:ia'),
            ]),
        ];
    }
}

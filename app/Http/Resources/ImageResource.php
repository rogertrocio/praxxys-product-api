<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'imageable_type' => $this->imageable_type,
            'imageable_id' => $this->imageable_id,
            'path' => $this->path,
            'file_name' => $this->file_name,
            'url' => $this->url(),
            'properties' => $this->properties,
            'created_at' => $this->created_at?->format('F d, Y H:i A'),
            'updated_at' => $this->updated_at?->format('F d, Y H:i A'),
        ];
    }
}

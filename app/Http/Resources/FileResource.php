<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'owner' => $this->owner,
            'visibility' => $this->visibility(),
            'path' => $this->path(),
            'mime' => $this->mimeType(),
            'size' => $this->size(),
            'lastModified' => $this->lastModified(),
        ];
    }
}

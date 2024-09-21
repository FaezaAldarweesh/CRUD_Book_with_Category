<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Book id' => $this->id,
            'Book title' => $this->title, 
            'Book author' => $this->author, 
            'published at' => $this->published_at, 
            'active status' => $this->is_active ? "active" : "unactive",
            'Book category' => $this->category->name,
        ];
    }
}

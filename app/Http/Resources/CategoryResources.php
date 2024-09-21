<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category id' => $this->id,
            'category name' => $this->name, 
            'category description' => $this->description, 
            'category books' => $this->books->map(function($book){
                return [
                    'Book id' => $book->id,
                    'Book title' => $book->title, 
                    'Book author' => $book->author, 
                    'published at' => $book->published_at, 
                    'active status' => $book->is_active ? "active" : "unactive",
                ];
            }),
        ];
    }
}

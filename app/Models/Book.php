<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title', 
        'author', 
        'published_at', 
        'is_active',
        'category_id',
    ];

    //Accessors published_at
    public function getPublishedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i'); 
    }

    //Mutators published_at
    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = Carbon::parse($value)->format('Y-m-d H:i:s'); 
    }

    public function scopeFilter(Builder $query, $is_active,$category)
    {
        if ($is_active !== null) {
            $query->where('is_active', '=', $is_active);
        }
        if ($category !== null) {
            $query->where('category_id', '=', $category);
        }
        return $query;
    }

    public function category (){
        return $this->belongsTo(Category::class,'category_id','id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'desc',
        'confirmed',
        'user_id',
        'published_at',
        'ad_level',
        'hits',
        'city',
        'iran_city_id',
        'province',
        'iran_province_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getCity()
    {
        return $this->belongsTo(IranCity::class, 'iran_city_id');
    }

    public function getProvince()
    {
        return $this->belongsTo(IranProvince::class, 'iran_province_id');
    }

    public function getCategories()
    {
        return json_decode($this->business_categories);
    }
}

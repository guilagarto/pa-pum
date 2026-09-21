<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioImage extends Model
{
    protected $fillable = ['profile_id', 'image_path'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

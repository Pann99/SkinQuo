<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    use HasFactory;

    protected $table = 'sex';

    protected $fillable = [
        'sex',
        'icon_image_url',
    ];

    public $timestamps = false;

    /**
     * Relationship: Sex has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'sex_id', 'id');
    }
}

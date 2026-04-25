<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // <-- Gunakan Model MongoDB

class ActivityType extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_types';

    protected $fillable = [
        'name',
        'description',
    ];
}
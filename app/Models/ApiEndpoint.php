<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiEndpoint extends Model
{
    protected $fillable = [
        'name',
        'method',
        'uri',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_api_endpoint');
    }
}

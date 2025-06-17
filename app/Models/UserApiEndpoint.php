<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApiEndpoint extends Model
{
    protected $table = 'user_api_endpoint';

    protected $fillable = [
        'user_id',
        'api_endpoint_id',
    ];

    public $timestamps = false;

    /**
     * Get the user that owns the UserApiEndpoint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the API endpoint associated with the UserApiEndpoint.
     */
    public function apiEndpoints()
    {
        return $this->belongsToMany(ApiEndpoint::class);
    }
}

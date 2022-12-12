<?php

namespace Wainwright\CasinoDog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Route;

class UserJob extends Eloquent  {
    protected $table = 'wainwright_job_user';
    protected $timestamp = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'name',
        'email'
        'is_admin',
        'password',
        'current_state',
        'requested_state',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
}
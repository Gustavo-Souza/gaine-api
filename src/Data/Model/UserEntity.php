<?php

declare(strict_types=1);

namespace App\Data\Model;

use Illuminate\Database\Eloquent\Model;

class UserEntity extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'firebase_auth_id',
        'firebase_auth_name',
        'fcm_device_id',
        'notification'
    ];
}

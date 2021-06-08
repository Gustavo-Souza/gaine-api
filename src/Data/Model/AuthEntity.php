<?php

declare(strict_types=1);

namespace App\Data\Model;

use Illuminate\Database\Eloquent\Model;

class AuthEntity extends Model
{
    protected $table = 'auth';
    protected $fillable = [
        'user_id',
        'token'
    ];
}

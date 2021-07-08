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


    public function user(): UserEntity
    {
        return $this->belongsTo(UserEntity::class, 'user_id', 'id')
            ->get()
            ->first();
    }
}

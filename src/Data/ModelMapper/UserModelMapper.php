<?php

declare(strict_types=1);

namespace App\Data\ModelMapper;

use App\Data\Model\User;
use Illuminate\Database\Eloquent\Model;

class UserModelMapper implements ModelMapperInterface
{
    public function toDomainModel(Model $entity)
    {
        $user = new User(
            $entity->id,
            $entity->firebase_auth_id,
            $entity->firebase_auth_name,
            $entity->fcm_device_id,
            $entity->notification,
            $entity->created_at->getTimestamp(),
            $entity->updated_at->getTimestamp()
        );
        return $user;
    }
}

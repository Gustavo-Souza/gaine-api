<?php

declare(strict_types=1);

namespace App\Data\Model;

class User
{
    private $id;
    private $firebaseAuthenticationId;
    private $firebaseAuthenticationName;
    private $firebaseCloudMessagingDeviceId;
    private $hasNotificationEnabled;
    private $createdAt;
    private $updatedAt;


    public function __construct(
        int $id = 0,
        string $firebaseAuthenticationId = '',
        string $firebaseAuthenticationName = '',
        string $firebaseCloudMessagingDeviceId = '',
        bool $hasNotificationEnabled = false,
        int $createdAt = 0,
        int $updatedAt = 0
    ) {
        $this->id = $id;
        $this->firebaseAuthenticationId = $firebaseAuthenticationId;
        $this->firebaseAuthenticationName = $firebaseAuthenticationName;
        $this->firebaseCloudMessagingDeviceId = $firebaseCloudMessagingDeviceId;
        $this->hasNotificationEnabled = $hasNotificationEnabled;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthenticationId(): string
    {
        return $this->authenticationId;
    }

    public function getAuthenticationName(): string
    {
        return $this->authenticationName;
    }

    public function getFirebaseCloudMessagingDeviceId(): string
    {
        return $this->firebaseCloudMessagingDeviceId;
    }

    public function hasNotificationEnabled(): bool
    {
        return $this->hasNotificationEnabled;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function withFirebaseAuthenticationId(
        string $firebaseAuthenticationId
    ): self {
        $instance = clone $this;
        $instance->firebaseAuthenticationId = $firebaseAuthenticationId;
        return $instance;
    }

    public function withFirebaseAuthenticationName(
        string $firebaseAuthenticationName
    ): self {
        $instance = clone $this;
        $instance->firebaseAuthenticationName = $firebaseAuthenticationName;
        return $instance;
    }

    public function withFirebaseCloudMessagingDeviceId(
        string $firebaseCloudMessagingDeviceId
    ): self {
        $instance = clone $this;
        $instance->firebaseCloudMessagingDeviceId = $firebaseCloudMessagingDeviceId;
        return $instance;
    }
}

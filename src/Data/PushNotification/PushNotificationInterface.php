<?php

declare(strict_types=1);

namespace App\Data\PushNotification;

use App\Data\Model\Code;
use App\Data\Model\User;

interface PushNotificationInterface
{
    public function sendCode(array $devicesId, Code $code): void;
    public function sendCodeInvalidationWarningForUser(
        User $userWhoInformed,
        User $userWhoSentCode,
        Code $code
    );
}

<?php

declare(strict_types=1);

namespace App\Data\PushNotification;

use App\Data\Model\Code;

interface PushNotificationInterface
{
    public function sendCode(array $devicesId, Code $code): void;
}

<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    const NAME = 'user.registered';

    private string $type = 'welcome';

    public function __construct(readonly int $userId, readonly string $email)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

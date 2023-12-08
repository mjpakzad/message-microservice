<?php

namespace App\Service\ServiceManagement;

interface UserServiceInterface
{
    public function isAuthenticated(): bool;

    public function me(): mixed;

    public function getUserId(): int;
}

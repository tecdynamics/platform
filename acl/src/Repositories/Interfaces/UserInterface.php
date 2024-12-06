<?php

namespace Tec\ACL\Repositories\Interfaces;

use Tec\Support\Repositories\Interfaces\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{
    public function getUniqueUsernameFromEmail(string $email): string;
}

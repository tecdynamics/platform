<?php

namespace Tec\ACL\Repositories\Interfaces;

use Tec\Support\Repositories\Interfaces\RepositoryInterface;

interface RoleInterface extends RepositoryInterface
{
    public function createSlug(string $name, int|string $id): string;
}

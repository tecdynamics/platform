<?php

namespace Tec\ACL\Repositories\Caches;

use Tec\ACL\Repositories\Interfaces\RoleInterface;
use Tec\Support\Repositories\Caches\CacheAbstractDecorator;

class RoleCacheDecorator extends CacheAbstractDecorator implements RoleInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}

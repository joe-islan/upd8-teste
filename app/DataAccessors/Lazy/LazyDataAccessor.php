<?php

namespace App\DataAccessors\Lazy;

use App\DataAccessors\DataAccessorInterface;
use App\DataAccessors\LazyDataAccessorInterface;
use Psr\Log\LoggerInterface;

class LazyDataAccessor implements LazyDataAccessorInterface
{
    protected ?DataAccessorInterface $dataAccessor;

    public function __construct(
        protected \Closure $initializer,
        protected LoggerInterface $logger
    ) {
    }

    public function __call($name, $arguments)
    {
        return $this->getDataAccessor()->$name(...$arguments);
    }

    protected function getDataAccessor(): DataAccessorInterface
    {
        if (!isset($this->dataAccessor)) {
            $this->dataAccessor = $this->initializer->__invoke();
        }

        return $this->dataAccessor;
    }
}

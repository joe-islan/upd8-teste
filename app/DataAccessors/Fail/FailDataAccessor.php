<?php

namespace App\DataAccessors\Fail;

use App\DataAccessors\Exception\DataAccessorNotInitializedException;
use App\DataAccessors\FailDataAccessorInterface;

class FailDataAccessor implements FailDataAccessorInterface
{
    public function __construct(
        private string $dataAccessor,
    ) {
    }

    public function __call($name, $arguments): void
    {
        $this->throwException();
    }

    protected function throwException(): void
    {
        throw new DataAccessorNotInitializedException(sprintf('DataAccessor \'%s\' is not initialized.', $this->dataAccessor));
    }
}

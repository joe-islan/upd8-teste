<?php

namespace App\DataAccessors\Retry;

use App\DataAccessors\DataAccessorInterface;
use App\DataAccessors\RetryDataAccessorInterface;
use Psr\Log\LoggerInterface;

class RetryCustomDataAccessor implements RetryDataAccessorInterface
{
    public function __construct(
        protected DataAccessorInterface $dataAccessor,
        protected int $retry,
        protected LoggerInterface $logger,
        protected ?\Closure $onFail = null
    ) {
        $this->onFail = $onFail ?? static function (): bool {
            return true;
        };
    }

    public function __call($name, $arguments): mixed
    {
        return $this->call(fn () => $this->dataAccessor->{$name}(...$arguments));
    }

    protected function call(\Closure $closure): mixed
    {
        $try = 0;

        do {
            try {
                return $closure();
            } catch (\Exception $e) {
                $retry = $this->onFail->__invoke($e, $try, $this->dataAccessor, $this->logger);
            }
        } while (++$try < $this->retry && $retry);

        throw $e;
    }
}

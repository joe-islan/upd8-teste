<?php

namespace App\DataAccessors\Cache;

use App\DataAccessors\ClientDataAccessorInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\Client;

class ClientDataAccessor implements ClientDataAccessorInterface
{
    public function __construct(
        private ClientDataAccessorInterface $dataAccessor
    ) {
    }

    public function all(array $filters = [], int $perPage = 4): LengthAwarePaginator
    {
        return $this->dataAccessor->all($filters, $perPage);
    }

    public function find(int $id): Client
    {
        return $this->dataAccessor->find($id);
    }

    public function create(
        string $cpf,
        string $name,
        Carbon $birthdate,
        string $gender,
        string $address,
        string $state,
        string $city
    ): Client
    {
        return $this->dataAccessor->create($cpf, $name, $birthdate, $gender, $address, $state, $city);
    }

    public function update(array $attributes, Client $client): Client
    {
        return $this->dataAccessor->update($attributes, $client);
    }

    public function delete(int $id): bool
    {
        return $this->dataAccessor->delete($id);
    }
}

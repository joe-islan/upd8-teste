<?php

namespace App\DataAccessors;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClientDataAccessorInterface extends DataAccessorInterface
{
    public function all(array $filters = [], int $perPage = 4): LengthAwarePaginator;

    public function find(int $id): Client;

    public function create(
        string $cpf,
        string $name,
        Carbon $birthdate,
        string $gender,
        string $address,
        string $state,
        string $city
    ): Client;

    public function update(array $attributes, Client $client): Client;

    public function delete(int $id): bool;
}

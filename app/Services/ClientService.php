<?php

namespace App\Services;

use App\DataAccessors\ClientDataAccessorInterface;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;


class ClientService
{
    public function __construct(
        private readonly ClientDataAccessorInterface $clientDataAccessor,
    ) {
    }

    public function getAll(array $filters = [], int $perPage = 4): LengthAwarePaginator
    {
        return $this->clientDataAccessor->all($filters, $perPage);
    }



    public function getById(int $id): ?Model
    {
        return $this->clientDataAccessor->find($id);
    }

    public function create(
        string $cpf,
        string $name,
        Carbon $birthdate,
        string $gender,
        string $address,
        string $state,
        string $city
    ): Client {
        return $this->clientDataAccessor->create(
            $cpf, 
            $name, 
            $birthdate, 
            $gender, 
            $address, 
            $state, 
            $city
        );
    }

    public function update(array $data, Client $client): Client
    {
        return $this->clientDataAccessor->update($data, $client);
    }

    public function delete(int $id): bool
    {
        return $this->clientDataAccessor->delete($id);
    }
}

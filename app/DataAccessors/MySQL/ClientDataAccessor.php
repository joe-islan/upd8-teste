<?php

namespace App\DataAccessors\MySQL;

use App\DataAccessors\ClientDataAccessorInterface;
use Illuminate\Pagination\LengthAwarePaginator;

use Carbon\Carbon;
use App\Models\Client;

class ClientDataAccessor implements ClientDataAccessorInterface
{
    public function all(array $filters = [], int $perPage = 4): LengthAwarePaginator
    {
        $query = Client::query();

        if (!empty($filters['cpf'])) {
            $query->where('cpf', $filters['cpf']);
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['birthdate'])) {
            $query->where('birthdate', $filters['birthdate']);
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        if (!empty($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        return $query->paginate($perPage);
    }



    public function find(int $id): Client
    {
        return Client::find($id);
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
        $client = (new Client())->fill([
            'cpf' => $cpf,
            'name' => $name,
            'birthdate' => $birthdate,
            'gender' => $gender,
            'address' => $address,
            'state' => $state,
            'city' => $city,
        ]);

        $client->save();

        return $client;
    }

    public function update(array $attributes, Client $client): Client
    {
        $client->update($attributes);
        return $client->refresh();
    }

    public function delete(int $id): bool
    {
        return (bool) Client::destroy($id);
    }
}

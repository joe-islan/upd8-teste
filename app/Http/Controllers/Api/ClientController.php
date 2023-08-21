<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Helpers\ControllerHelper;
use App\Services\ClientService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function __construct(
        private ControllerHelper $controllerHelper,
        private ClientService $clientService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'cpf'       => $request->get('cpf'),
                'name'      => $request->get('name'),
                'birthdate' => $request->get('birthdate'),
                'gender'    => $request->get('gender'),
                'state'     => $request->get('state'),
                'city'      => $request->get('city')
            ];
            return $this->controllerHelper->successJsonResponse(
                Response::HTTP_OK,
                'Lista de clientes obtida com sucesso',
                $this->clientService->getAll($filters)->toArray()
            );
        } catch (\Exception $e) {
            return $this->controllerHelper->errorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                sprintf('Ocorreu um erro - %s.', $e->getMessage())
            );
        }
    }
    

    public function store(\App\Http\Requests\StoreClientRequest $request): JsonResponse
    {
        try {
            return $this->controllerHelper->successJsonResponse(
                Response::HTTP_CREATED,
                'Cliente criado com sucesso!',
                $this->clientService->create(
                    $request['cpf'],
                    $request['name'],
                    Carbon::parse($request['birthdate']),
                    $request['gender'],
                    $request['address'],
                    $request['state'],
                    $request['city'],
                )
            );
        } catch (\Exception $e) {
            return $this->controllerHelper->errorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                sprintf('Ocorreu um erro - %s.', $e->getMessage())
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return $this->controllerHelper->successJsonResponse(
                Response::HTTP_OK,
                'Cliente obtido com sucesso',
                $this->clientService->getById($id)
            );
        } catch (\Exception $e) {
            return $this->controllerHelper->errorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                sprintf('Ocorreu um erro - %s.', $e->getMessage())
            );
        }
    }

    public function update(\App\Http\Requests\UpdateClientRequest $request, int $id): JsonResponse
    {
        try {
            $updatedClient = $this->clientService->update(
                [
                    'cpf' => $request['cpf'],
                    'name' => $request['name'],
                    'birthdate' => $request['birthdate'],
                    'gender' => $request['gender'],
                    'address' => $request['address'],
                    'state' => $request['state'],
                    'city' => $request['city'],
                ],
                $this->clientService->getById($id)
            );

            return $this->controllerHelper->successJsonResponse(
                Response::HTTP_OK,
                'Cliente atualizado com sucesso!',
                $updatedClient
            );
        } catch (\Exception $e) {
            return $this->controllerHelper->errorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                sprintf('Ocorreu um erro - %s.', $e->getMessage())
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->clientService->delete($id);

            return $this->controllerHelper->successJsonResponse(
                Response::HTTP_OK,
                'Cliente deletado com sucesso!'
            );
        } catch (\Exception $e) {
            return $this->controllerHelper->errorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                sprintf('Ocorreu um erro - %s.', $e->getMessage())
            );
        }
    }
}

<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserRepository
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return User::query()->select(['id', 'name', 'email', 'role'])->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        return User::query()->findOrFail($id, $columns);
    }

    /**
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $params): JsonResponse
    {
        if ($params['password'] === $params['passwordRepeat']) {
            unset($params['passwordRepeat']);

            User::query()->create(array_merge($params, ['password' => app('hash')->make($params['password'])]));

            return response()->json(null, Response::HTTP_CREATED);
        }

        return response()->json(['error' => 'passwordNotEqual'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @param array $params
     * @param int $id
     * @return JsonResponse
     */
    public function update(array $params, int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);

        if (isset($params['password']) || isset($params['passwordRepeat'])) {
            if (isset($params['password']) && isset($params['passwordRepeat']) && $params['password'] === $params['passwordRepeat']) {
                $params = array_merge($params, ['password' => app('hash')->make($params['password'])]);
            } else {
                return response()->json(['error' => 'passwordNotEqual'], Response::HTTP_METHOD_NOT_ALLOWED);
            }
        }

        $user->update($params);

        return response()->json(null);
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        User::query()->findOrFail($id)->delete();

        return null;
    }
}
<?php

namespace App\Policies;

use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class ClientPolicy
{
    use HandlesAuthorization;
    //Before method is inside the AuthServiceProvider

    public function viewAny(Authenticatable $user): bool
    {
        if (Auth::user()->hasRole(['client'])) {
            return true;
        }

        return false;
    }

    public function view(Authenticatable $user, Client $client): bool
    {
        if (Auth::user()->hasRole(['client'])) {
            return $user->id == $client->id || $user->id == $client->id;
        }

        return false;
    }

    //    public function create(Authenticatable $user): bool
    //    {
    //    }

    public function update(Authenticatable $user, Client $client): bool
    {
        if ($user->hasRole(['client']) && $user instanceof Client) {
            return $user->id === $client->id;
        }

        return false;
    }

    //    public function delete(Authenticatable $user, Client $client): bool
    //    {
    //    }
    //
    //    public function restore(Authenticatable $user, Client $client): bool
    //    {
    //    }
    //
    //    public function forceDelete(Authenticatable $user, Client $client): bool
    //    {
    //    }
}

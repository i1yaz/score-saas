<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClientRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Client::class;
    }
    public function create(array $input): Model
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    public function index(int $paginate)
    {
        $clients = Client::query();
        if (Auth::user()->hasRole(['client'])) {
            $clients = $clients->where('id', Auth::id());
        }

        return $clients->paginate($paginate);
    }
}

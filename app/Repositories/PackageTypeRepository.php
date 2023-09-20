<?php

namespace App\Repositories;

use App\Models\PackageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PackageTypeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'hours',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PackageType::class;
    }

    public function create(array $input): Model
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }
}

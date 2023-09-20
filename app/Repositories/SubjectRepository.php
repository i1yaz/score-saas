<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubjectRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Subject::class;
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

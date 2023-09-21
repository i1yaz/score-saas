<?php

namespace App\Repositories;

use App\Models\ParentUser;

class ParentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'first_name',
        'last_name',
        'status',
        'phone',
        'address',
        'address2',
        'phone_alternate',
        'referral_source',
        'added_by',
        'referral_from_positive_experience_with_tutor',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ParentUser::class;
    }

    public function find(int $id, array $columns = ['*'], $withAddedBy = false)
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }
}

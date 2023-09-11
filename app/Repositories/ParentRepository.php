<?php

namespace App\Repositories;

use App\Models\ParentUser;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

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
        'added_on',
        'referral_from_positive_experience_with_tutor'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ParentUser::class;
    }

    /**
     * Paginate records for scaffold.
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->allQuery();
        $query = $query->join('users as u','parents.user_id','u.id');
        $query->join('users as u1','parents.user_id','u1.id');

        return $query->paginate($perPage, $columns);
    }

    /**
     * Find model record for given id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find(int $id, array $columns = ['*'])
    {
        $query = $this->model->newQuery();
        $query = $query->join('users as u','parents.user_id','u.id');
        $query = $query->join('users as u1','parents.user_id','u1.id');
        return $query->find($id, $columns);
    }
}

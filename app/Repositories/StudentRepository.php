<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class StudentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'school_id',
        'first_name',
        'last_name',
        'email_known',
        'testing_accommodation',
        'testing_accommodation_nature',
        'official_baseline_act_score',
        'official_baseline_sat_score',
        'test_anxiety_challenge',
        'parent_id',
        'added_by',
        'added_on',
        'status',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Student::class;
    }


    /**
     * Paginate records for scaffold.
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->allQuery();
        $query = $query->join('users as u','students.user_id','u.id');
        $query->join('users as u1','students.user_id','u1.id');

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
        $query = $query->join('users as u','students.user_id','u.id');
        $query = $query->join('users as u1','students.user_id','u1.id');
        return $query->find($id, $columns);
    }
}

<?php

namespace App\Repositories;

use App\Models\ParentUser;
use App\Repositories\BaseRepository;

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
}

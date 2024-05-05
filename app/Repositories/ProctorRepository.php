<?php

namespace App\Repositories;

use App\Models\Proctor;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ProctorRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'status'
    ];

    public static function getProctors(mixed $email): array
    {
        $email = trim($email);
        $proctorsCollection = Proctor::active()->select([DB::raw("concat('proctor','_',`id`) as concat_id"), 'proctors.email as text'])
            ->where('proctors.email', 'LIKE', "%{$email}%")
            ->limit(5)
            ->get();
        $proctors = [];
        foreach ($proctorsCollection as $proctor) {
            $proctors[] = [
                'id' => $proctor['concat_id'],
                'text' => $proctor['text']
            ];
        }
        return $proctors;
    }

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Proctor::class;
    }
}

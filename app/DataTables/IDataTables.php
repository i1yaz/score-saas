<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array;

    public static function totalFilteredRecords(mixed $search): int;

    public static function populateRecords($records): array;

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder;

    public static function totalRecords(): int;
}

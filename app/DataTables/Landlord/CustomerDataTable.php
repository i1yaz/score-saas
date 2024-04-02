<?php

namespace App\DataTables\Landlord;

use App\DataTables\IDataTables;
use App\Models\Landlord\Tenant;
use App\Repositories\Landlord\TenantsRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

class CustomerDataTable implements IDataTables
{
    protected static array $columns = [
        'id' =>'tenants.id',
        'name' =>'tenants.name',
        'created_at' => 'tenants.created_at',
        'account' => 'tenants.account_url',
        'plan' => 'packages.plan',
        'type' => 'subscriptions.type',
        'status' => 'packages.status',
    ];

    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $order = static::$columns[$order] ?? $order;
        $tenants = static::buildQuery();
        $columns = explode(',', $order);
        foreach ($columns as $column) {
            $tenants = $tenants->orderBy($column, $dir);
        }
        $tenants = $tenants->groupBy('tenants.id')->offset($start)
            ->limit($limit);

        return $tenants->get();
    }
    public static function buildQuery(): Builder
    {
        $tenants = Tenant::query();
        $tenants->select(['tenants.id', 'tenants.name', 'tenants.created_at', 'tenants.domain', 'packages.name as plan', 'subscriptions.type', 'packages.status as status']);

        $tenants->leftJoin('subscriptions', function($join) {
            $join->on('subscriptions.customer_id', '=', 'tenants.id')
                ->whereNotIn('subscriptions.status', ['cancelled'])
                ->limit(1);
        });
        $tenants->leftJoin('packages', 'packages.id', '=', 'subscriptions.package_id');
        return static::getModelQueryBySearch(request('search'),$tenants);


    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $tenants = static::buildQuery();
        return $tenants->groupBy('tenants.id')->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $customer) {
                $nestedData['id'] = $customer->id;
                $nestedData['name'] = $customer->name;
                $nestedData['created_at'] = $customer->created_at;
                $nestedData['account_url'] = $customer->domain;
                $nestedData['plan'] = $customer->plan;
                $nestedData['type'] = $customer->type;
                $nestedData['status'] = $customer->status;
                $nestedData['action'] = view('landlord.customers.actions', ['customer' => $customer])->render();
                $data[] = $nestedData;
            }
        }

        return $data;
    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (request()->filled('search')) {
            $records = $records->where(function ($query) use ($search) {
                $query->orWhere('tenants.status', '=', $search);
                $query->orWhere('tenants.email', '=', $search);
                $query->orWhere('subdomain', 'LIKE', '%' . $search . '%');
                $query->orWhere('packages.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('tenants.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('tenants.id', 'LIKE', '%' . $search . '%');
            });
        }
        return $records;
    }

    public static function totalRecords(): int
    {
        return Tenant::select('id')
            ->join('subscriptions', 'subscriptions.customer_id', '=', 'tenants.id')
            ->where('subscriptions.status', '!=', 'cancelled')->count();
    }
}

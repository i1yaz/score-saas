<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\Schema;

class TenantsRepository
{
    public function __construct(protected Tenant $tenant) {
    }

    public function search($id = '') {

        $tenants = $this->tenant->newQuery();

        // all client fields
        $tenants->selectRaw('*');


        //joins
        $tenants->leftJoin('subscriptions', function($join) {
            $join->on('subscriptions.customer_id', '=', 'tenants.id')
                ->whereNotIn('subscriptions.status', ['cancelled'])
                ->limit(1);
        });
        $tenants->leftJoin('packages', 'packages.id', '=', 'subscriptions.package_id');

        //filters: id
        if (request()->filled('filter_tenant_id')) {
            $tenants->where('tenants.id', request('filter_tenant_id'));
        }
        if (is_numeric($id)) {
            $tenants->where('tenants.id', $id);
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $tenants->where(function ($query) {
                $query->orWhere('tenants.status', '=', request('search_query'));
                $query->orWhere('tenants.email', '=', request('search_query'));
                $query->orWhere('subdomain', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('packages.name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('tenants.name', 'LIKE', '%' . request('search_query') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('tenants', request('orderby'))) {
                $tenants->orderBy(request('orderby'), request('sortorder'));
            }
        } else {
            $tenants->orderBy('tenants.id', 'asc');
        }

        return $tenants->paginate(config('system.system_pagination_limits'));
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create() {

        //save new user
        $tenant = new $this->tenants;

        //data
        $tenant->tenant_categoryid = request('tenant_categoryid');
        $tenant->tenant_creatorid = auth()->id();

        //save and return id
        if ($tenant->save()) {
            return $tenant->tenant_id;
        } else {
            Log::error("unable to create record - database error", ['process' => '[ItemRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id) {

        //get the record
        if (!$tenant = $this->tenants->find($id)) {
            return false;
        }

        //general
        $tenant->tenant_categoryid = request('tenant_categoryid');

        //save
        if ($tenant->save()) {
            return $tenant->tenant_id;
        } else {
            Log::error("unable to update record - database error", ['process' => '[ItemRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

    }

    /**
     * various feeds for ajax auto complete
     * @param string $type (company_name)
     * @param string $searchterm
     * @return object tenant model object
     */
    public function autocompleteFeed( $searchterm = '') {

        //validation
        if ($searchterm == '') {
            return [];
        }

        //start
        $query = $this->tenant->newQuery();

        $query->selectRaw('tenant_name AS value, tenant_id AS id');
        $query->where('tenant_name', 'LIKE', '%' . $searchterm . '%');

        //return
        return $query->get();
    }

}

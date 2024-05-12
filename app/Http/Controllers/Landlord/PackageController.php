<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\Packages\CreatePackage;
use App\Models\Landlord\Package;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('landlord.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('landlord.packages.create');
    }
    public function store(CreatePackage $request)
    {
        $package = new Package();
        $this->savePackageDetails($request, $package);
        Flash::success('Package saved successfully.');
        return redirect(route('landlord.packages.index'));
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('landlord.packages.edit', compact('package'));
    }

    public function update(CreatePackage $request,$id)
    {
        $package = Package::findOrFail($id);
        $this->savePackageDetails($request, $package);
        Flash::success('Package updated successfully.');
        return redirect(route('landlord.packages.index'));
    }

    /**
     * @param CreatePackage $request
     * @param Package $package
     * @return void
     */
    private function savePackageDetails(CreatePackage $request, Package $package): void
    {
        $package->name = $request->name;
        $package->amount_monthly = $request->price_monthly;
        $package->amount_yearly = $request->price_yearly;
        $package->is_featured = $request->is_featured == 'yes';
        $package->max_students = $request->max_students;
        $package->max_student_packages = $request->max_student_packages;
        $package->max_monthly_packages = $request->max_monthly_packages;
        $package->max_teacher = $request->max_teacher;
        $package->save();

        //reset featured
        if ($request->is_featured == 'yes') {
            Package::whereNotIn('id', [$package->id])
                ->update(['is_featured' => false]);
        }
    }
}

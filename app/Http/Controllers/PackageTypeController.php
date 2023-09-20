<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePackageTypeRequest;
use App\Http\Requests\UpdatePackageTypeRequest;
use App\Repositories\PackageTypeRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class PackageTypeController extends AppBaseController
{
    /** @var PackageTypeRepository */
    private $packageTypeRepository;

    public function __construct(PackageTypeRepository $packageTypeRepo)
    {
        $this->packageTypeRepository = $packageTypeRepo;
    }

    /**
     * Display a listing of the PackageType.
     */
    public function index(Request $request)
    {
        $packageTypes = $this->packageTypeRepository->paginate(10);

        return view('package_types.index')
            ->with('packageTypes', $packageTypes);
    }

    /**
     * Show the form for creating a new PackageType.
     */
    public function create()
    {
        return view('package_types.create');
    }

    /**
     * Store a newly created PackageType in storage.
     */
    public function store(CreatePackageTypeRequest $request)
    {
        $input = $request->all();

        $this->packageTypeRepository->create($input);

        Flash::success('Package Type saved successfully.');

        return redirect(route('package-types.index'));
    }

    /**
     * Display the specified PackageType.
     */
    public function show($id)
    {
        $packageType = $this->packageTypeRepository->find($id);

        if (empty($packageType)) {
            Flash::error('Package Type not found');

            return redirect(route('package-types.index'));
        }

        return view('package_types.show')->with('packageType', $packageType);
    }

    /**
     * Show the form for editing the specified PackageType.
     */
    public function edit($id)
    {
        $packageType = $this->packageTypeRepository->find($id);

        if (empty($packageType)) {
            Flash::error('Package Type not found');

            return redirect(route('package-types.index'));
        }

        return view('package_types.edit')->with('packageType', $packageType);
    }

    /**
     * Update the specified PackageType in storage.
     */
    public function update($id, UpdatePackageTypeRequest $request)
    {
        $packageType = $this->packageTypeRepository->find($id);

        if (empty($packageType)) {
            Flash::error('Package Type not found');

            return redirect(route('package-types.index'));
        }

        $packageType = $this->packageTypeRepository->update($request->all(), $id);

        Flash::success('Package Type updated successfully.');

        return redirect(route('package-types.index'));
    }

    /**
     * Remove the specified PackageType from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $packageType = $this->packageTypeRepository->find($id);

        if (empty($packageType)) {
            Flash::error('Package Type not found');

            return redirect(route('package-types.index'));
        }

        $this->packageTypeRepository->delete($id);

        Flash::success('Package Type deleted successfully.');

        return redirect(route('package-types.index'));
    }
}

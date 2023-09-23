<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTutoringPackageTypeRequest;
use App\Http\Requests\UpdateTutoringPackageTypeRequest;
use App\Models\TutoringPackageType;
use App\Models\ParentUser;
use App\Repositories\TutoringPackageTypeRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TutoringPackageTypeController extends AppBaseController
{
    /** @var TutoringPackageTypeRepository */
    private TutoringPackageTypeRepository $tutoringPackageTypeRepository;

    public function __construct(TutoringPackageTypeRepository $tutoringPackageTypeRepo)
    {
        $this->tutoringPackageTypeRepository = $tutoringPackageTypeRepo;
    }

    /**
     * Display a listing of the TutoringPackageType.
     */
    public function index(Request $request)
    {
        $tutoringPackageTypes = $this->tutoringPackageTypeRepository->paginate(10);

        return view('tutoring_package_types.index')
            ->with('tutoringPackageTypes', $tutoringPackageTypes);
    }

    /**
     * Show the form for creating a new TutoringPackageType.
     */
    public function create()
    {
        return view('tutoring_package_types.create');
    }

    /**
     * Store a newly created TutoringPackageType in storage.
     */
    public function store(CreateTutoringPackageTypeRequest $request)
    {
        $input = $request->all();

        $this->tutoringPackageTypeRepository->create($input);

        Flash::success('Tutoring Package Type saved successfully.');

        return redirect(route('tutoring-package-types.index'));
    }

    /**
     * Display the specified TutoringPackageType.
     */
    public function show($id)
    {
        $tutoringPackageType = $this->tutoringPackageTypeRepository->find($id);

        if (empty($tutoringPackageType)) {
            Flash::error('Tutoring Package Type not found');

            return redirect(route('tutoring-package-types.index'));
        }

        return view('tutoring_package_types.show')->with('tutoringPackageType', $tutoringPackageType);
    }

    /**
     * Show the form for editing the specified TutoringPackageType.
     */
    public function edit($id)
    {
        $tutoringPackageType = $this->tutoringPackageTypeRepository->find($id);

        if (empty($tutoringPackageType)) {
            Flash::error('Tutoring Package Type not found');

            return redirect(route('tutoring-package-types.index'));
        }

        return view('tutoring_package_types.edit')->with('tutoringPackageType', $tutoringPackageType);
    }

    /**
     * Update the specified TutoringPackageType in storage.
     */
    public function update($id, UpdateTutoringPackageTypeRequest $request)
    {
        $tutoringPackageType = $this->tutoringPackageTypeRepository->find($id);

        if (empty($tutoringPackageType)) {
            Flash::error('Tutoring Package Type not found');

            return redirect(route('tutoring-package-types.index'));
        }

        $tutoringPackageType = $this->tutoringPackageTypeRepository->update($request->all(), $id);

        Flash::success('Tutoring Package Type updated successfully.');

        return redirect(route('tutoring-package-types.index'));
    }

    /**
     * Remove the specified TutoringPackageType from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tutoringPackageType = $this->tutoringPackageTypeRepository->find($id);

        if (empty($tutoringPackageType)) {
            Flash::error('Tutoring Package Type not found');

            return redirect(route('tutoring-package-types.index'));
        }

        $this->tutoringPackageTypeRepository->delete($id);

        Flash::success('Tutoring Package Type deleted successfully.');

        return redirect(route('tutoring-package-types.index'));
    }
}

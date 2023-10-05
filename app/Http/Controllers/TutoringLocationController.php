<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTutoringLocationRequest;
use App\Http\Requests\UpdateTutoringLocationRequest;
use App\Repositories\TutoringLocationRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TutoringLocationController extends AppBaseController
{
    private TutoringLocationRepository $tutoringLocationRepository;

    public function __construct(TutoringLocationRepository $tutoringLocationRepo)
    {
        $this->tutoringLocationRepository = $tutoringLocationRepo;
    }

    /**
     * Display a listing of the TutoringLocation.
     */
    public function index(Request $request)
    {
        $tutoringLocations = $this->tutoringLocationRepository->paginate(10);

        return view('tutoring_locations.index')
            ->with('tutoringLocations', $tutoringLocations);
    }

    /**
     * Show the form for creating a new TutoringLocation.
     */
    public function create()
    {
        return view('tutoring_locations.create');
    }

    /**
     * Store a newly created TutoringLocation in storage.
     */
    public function store(CreateTutoringLocationRequest $request)
    {
        $input = $request->all();

        $this->tutoringLocationRepository->create($input);

        Flash::success('Tutoring Location saved successfully.');

        return redirect(route('tutoring-locations.index'));
    }

    /**
     * Display the specified TutoringLocation.
     */
    public function show($id)
    {
        $tutoringLocation = $this->tutoringLocationRepository->find($id);

        if (empty($tutoringLocation)) {
            Flash::error('Tutoring Location not found');

            return redirect(route('tutoring-locations.index'));
        }

        return view('tutoring_locations.show')->with('tutoringLocation', $tutoringLocation);
    }

    /**
     * Show the form for editing the specified TutoringLocation.
     */
    public function edit($id)
    {
        $tutoringLocation = $this->tutoringLocationRepository->find($id);

        if (empty($tutoringLocation)) {
            Flash::error('Tutoring Location not found');

            return redirect(route('tutoring-locations.index'));
        }

        return view('tutoring_locations.edit')->with('tutoringLocation', $tutoringLocation);
    }

    /**
     * Update the specified TutoringLocation in storage.
     */
    public function update($id, UpdateTutoringLocationRequest $request)
    {
        $tutoringLocation = $this->tutoringLocationRepository->find($id);

        if (empty($tutoringLocation)) {
            Flash::error('Tutoring Location not found');

            return redirect(route('tutoring-locations.index'));
        }

        $this->tutoringLocationRepository->update($request->all(), $id);

        Flash::success('Tutoring Location updated successfully.');

        return redirect(route('tutoring-locations.index'));
    }

    /**
     * Remove the specified TutoringLocation from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tutoringLocation = $this->tutoringLocationRepository->find($id);

        if (empty($tutoringLocation)) {
            Flash::error('Tutoring Location not found');

            return redirect(route('tutoring-locations.index'));
        }

        $this->tutoringLocationRepository->toggleStatus($id);

        Flash::success('Tutoring Location deleted successfully.');

        return redirect(route('tutoring-locations.index'));
    }
}

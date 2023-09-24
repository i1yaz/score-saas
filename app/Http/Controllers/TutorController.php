<?php

namespace App\Http\Controllers;

use App\DataTables\TutorDataTable;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateTutorRequest;
use App\Http\Requests\UpdateTutorRequest;
use App\Mail\TutorRegistrationMail;
use App\Models\Tutor;
use App\Repositories\TutorRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;

class TutorController extends AppBaseController
{
    private TutorRepository $tutorRepository;

    public function __construct(TutorRepository $tutorRepo)
    {
        $this->tutorRepository = $tutorRepo;
    }

    /**
     * Display a listing of the Tutor.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'email',
                'first_name',
                'last_name',
                'status',
                'start_date',
                'phone',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = TutorDataTable::totalRecords();
            $tutors = TutorDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = TutorDataTable::totalFilteredRecords($search);
            $data = TutorDataTable::populateRecords($tutors);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('tutors.index');
    }

    /**
     * Show the form for creating a new Tutor.
     */
    public function create()
    {
        return view('tutors.create');
    }

    /**
     * Store a newly created Tutor in storage.
     */
    public function store(CreateTutorRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $passwordString = Str::password(20);
            $register = new RegisterController();
            $input['password'] = $password = App::environment(['production']) ? Hash::make($passwordString) : Hash::make('abcd1234');
            $input['password_confirmation'] = $password;
            $input['added_by'] = Auth::id();
            $input['auth_guard'] = Auth::guard()->name;
            $input['status'] = $input['status'] == 'yes';
            $input['userData'] = true;
            $input['registrationType'] = 'tutor';
            unset($input['picture'],$input['resume']);
            $user = $register->register($request->merge($input), false);
            $this->storePictureOrResume($request, $user);
            $user->addRole('tutor');
            DB::commit();
            $input['password'] = App::environment(['production']) ? $passwordString : 'abcd1234';
            Mail::to($user)->send(new TutorRegistrationMail($input));
            Flash::success('Tutor saved successfully.');

            return redirect(route('tutors.index'));
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            Flash::error('something went wrong');

            return redirect(route('tutors.index'));
        }
    }

    /**
     * Display the specified Tutor.
     */
    public function show($id)
    {
        $tutor = $this->tutorRepository->find($id);

        if (empty($tutor)) {
            Flash::error('Tutor not found');

            return redirect(route('tutors.index'));
        }

        return view('tutors.show')->with('tutor', $tutor);
    }

    /**
     * Show the form for editing the specified Tutor.
     */
    public function edit($id)
    {
        $tutor = $this->tutorRepository->find($id);

        if (empty($tutor)) {
            Flash::error('Tutor not found');

            return redirect(route('tutors.index'));
        }

        return view('tutors.edit')->with('tutor', $tutor);
    }

    /**
     * Update the specified Tutor in storage.
     */
    public function update($id, UpdateTutorRequest $request)
    {
        $tutor = $this->tutorRepository->find($id);
        if (empty($tutor)) {
            Flash::error('Tutor not found');

            return redirect(route('tutors.index'));
        }
        $input = $request->all();
        $input['status'] = $input['status'] == 'yes';
        unset($input['picture'],$input['resume']);
        $this->tutorRepository->update($input, $id);
        $this->storePictureOrResume($request, $tutor);
        Flash::success('Tutor updated successfully.');

        return redirect(route('tutors.index'));
    }

    /**
     * Remove the specified Tutor from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tutor = $this->tutorRepository->find($id);

        if (empty($tutor)) {
            Flash::error('Tutor not found');

            return redirect(route('tutors.index'));
        }
        $this->tutorRepository->delete($id);
        Flash::success('Tutor deleted successfully.');

        return redirect(route('tutors.index'));
    }

    private function storePictureOrResume(Request $request, Tutor|TutorRepository|Model $tutor): void
    {
        $input = [];
        if ($request->has('picture')) {
            deleteFile($tutor->picture);
            $input['picture'] = storeFile("pictures/tutors/{$tutor->id}", $request->file('picture'));
        }
        if ($request->has('resume')) {
            deleteFile($tutor->resume);
            $input['resume'] = storeFile("resume/tutors/{$tutor->id}", $request->file('resume'));
        }
        if ($request->has('picture') || $request->has('resume')) {
            $this->tutorRepository->update($input, $tutor->id);

        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateProctorRequest;
use App\Http\Requests\UpdateProctorRequest;
use App\Mail\ProctorRegistrationMail;
use App\Models\MailTemplate;
use App\Repositories\ProctorRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProctorController extends AppBaseController
{
    /** @var ProctorRepository $proctorRepository*/
    private ProctorRepository $proctorRepository;

    public function __construct(ProctorRepository $proctorRepo)
    {
        $this->proctorRepository = $proctorRepo;
    }

    /**
     * Display a listing of the Proctor.
     */
    public function index(Request $request)
    {
        $proctors = $this->proctorRepository->paginate(10);

        return view('proctors.index')
            ->with('proctors', $proctors);
    }

    /**
     * Show the form for creating a new Proctor.
     */
    public function create()
    {
        return view('proctors.create');
    }

    /**
     * Store a newly created Proctor in storage.
     * @throws \Throwable
     */
    public function store(CreateProctorRequest $request)
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
            $input['registrationType'] = 'proctor';
            $user = $register->register($request->merge($input), false);
            $user->addRole('proctor');
            DB::commit();
            $input['password'] = App::environment(['production']) ? $passwordString : 'abcd1234';
            try {
                $template = MailTemplate::where('mailable', ProctorRegistrationMail::class)->firstOrFail();
                if ($template->status) {
                    Mail::to($user)->send(new ProctorRegistrationMail($input));
                }
            } catch (\Exception $exception) {
                report($exception);
            }
            \Laracasts\Flash\Flash::success('Proctor saved successfully.');

            return redirect(route('proctors.index'));
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            Flash::error('something went wrong');

            return redirect(route('proctors.index'));
        }

    }

    /**
     * Display the specified Proctor.
     */
    public function show($id)
    {
        $proctor = $this->proctorRepository->find($id);
        $this->authorize('view', $proctor);
        if (empty($proctor)) {
            Flash::error('Proctor not found');

            return redirect(route('proctors.index'));
        }

        return view('proctors.show')->with('proctor', $proctor);
    }

    /**
     * Show the form for editing the specified Proctor.
     */
    public function edit($id)
    {
        $proctor = $this->proctorRepository->find($id);
        $this->authorize('update', $proctor);
        if (empty($proctor)) {
            Flash::error('Proctor not found');

            return redirect(route('proctors.index'));
        }

        return view('proctors.edit')->with('proctor', $proctor);
    }

    /**
     * Update the specified Proctor in storage.
     */
    public function update($id, UpdateProctorRequest $request)
    {
        $proctor = $this->proctorRepository->find($id);
        $this->authorize('update', $proctor);
        if (empty($proctor)) {
            Flash::error('Proctor not found');

            return redirect(route('proctors.index'));
        }
        $input = array_filter($request->all());

        if (!empty($input['password'])){
            $input['password'] = Hash::make($request->password);
        }
        $this->proctorRepository->update($input, $id);
        if ($request->ajax()) {
            return response()->json(
                [
                    'message' => 'Proctor updated successfully.',
                ]);
        }
        Flash::success('Proctor updated successfully.');

        return redirect(route('proctors.index'));
    }

    /**
     * Remove the specified Proctor from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $proctor = $this->proctorRepository->find($id);

        if (empty($proctor)) {
            Flash::error('Proctor not found');

            return redirect(route('proctors.index'));
        }

        $this->proctorRepository->delete($id);

        Flash::success('Proctor deleted successfully.');

        return redirect(route('proctors.index'));
    }
    public function proctorsAjax(Request $request)
    {
        $proctors = ProctorRepository::getProctors($request->email);
        return response()->json($proctors);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Http\Controllers\AppBaseController;
use App\Mail\ParentRegisteredMail;
use App\Models\ParentUser;
use App\Repositories\ParentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ParentController extends AppBaseController
{
    /** @var ParentRepository $parentRepository*/
    private $parentRepository;

    public function __construct(ParentRepository $parentRepo)
    {
        $this->parentRepository = $parentRepo;
    }

    /**
     * Display a listing of the Parent.
     */
    public function index(Request $request)
    {
        $parents = ParentUser::select(['id','email','first_name','last_name','status','phone','added_on'])
        ->paginate(50);

        return view('parents.index')
            ->with('parents', $parents);
    }

    /**
     * Show the form for creating a new Parent.
     */
    public function create()
    {
        return view('parents.create');
    }

    /**
     * Store a newly created Parent in storage.
     */
    public function store(CreateParentRequest $request)
    {
        $input = $request->all();
        $register = new RegisterController();
        $input['password'] = $password = \App::environment(['production'])?Hash::make(\Str::password(20)):Hash::make('abcd1234');
        $input['first_name'] = $request['first_name'];
        $input['last_name'] = $request['last_name'];
        $input['email'] = $request['email'];
        $input['auth_guard'] =\Auth::guard()->name;
        $input['added_by'] = \Auth::id();
        $input['added_on'] = Carbon::now();
        $input['referral_from_positive_experience_with_tutor'] = $input['referral_from_positive_experience_with_tutor']=='yes';
        $input['status'] = $input['status']=='yes';
        $input['password_confirmation'] = $password;
        $input['userData'] = true;
        $input['registrationType']='parent';
        $user = $register->register($request->merge($input),false);
        $user->addRole('parent');
        Mail::to($user)->send(new ParentRegisteredMail($input));
        Flash::success('Parent saved successfully.');
        return redirect(route('parents.index'));
    }

    /**
     * Display the specified Parent.
     */
    public function show($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        return view('parents.show')->with('parent', $parent);
    }

    /**
     * Show the form for editing the specified Parent.
     */
    public function edit($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        return view('parents.edit')->with('parent', $parent);
    }

    /**
     * Update the specified Parent in storage.
     */
    public function update($id, UpdateParentRequest $request)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }
        $input = $request->all();
        $input['referral_from_positive_experience_with_tutor'] = $input['referral_from_positive_experience_with_tutor']=='yes';
        $input['status'] = $input['status']=='yes';
        $this->parentRepository->update($input, $id);
        Flash::success('Parent updated successfully.');
        return redirect(route('parents.index'));
    }

    /**
     * Remove the specified Parent from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        $this->parentRepository->delete($id);

        Flash::success('Parent deleted successfully.');

        return redirect(route('parents.index'));
    }
}

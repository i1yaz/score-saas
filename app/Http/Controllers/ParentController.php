<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ParentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;

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
        $parents = $this->parentRepository->paginate(10,['parents.*','u.email as email','u1.email as created_by_email']);
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
        $input['password'] = $password = \Str::password(20);
        $user = $register->register($request->merge(['password'=> $password,'password_confirmation' => $password,'userData'=>true]),false);
        $input['user_id'] = $user->id;
        $input['added_by'] = \Auth::id();
        $input['added_on'] = Carbon::now();
        $input['referral_from_positive_experience_with_tutor'] = $input['referral_from_positive_experience_with_tutor']=='yes';
        $input['status'] = $input['status']=='yes';
        $this->parentRepository->create($input);
        $user->addRole('parent');
        Flash::success('Parent saved successfully.');
        return redirect(route('parents.index'));
    }

    /**
     * Display the specified Parent.
     */
    public function show($id)
    {
        $parent = $this->parentRepository->find($id,['parents.*','u.email as email','u1.email as created_by_email']);

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
        $parent = $this->parentRepository->find($id,['parents.*','u.email as email','u1.email as created_by_email']);

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

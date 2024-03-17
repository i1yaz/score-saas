<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Mail\ClientMakePaymentMail;
use App\Mail\ClientRegisteredMail;
use App\Models\MailTemplate;
use App\Repositories\ClientRepository;
use Flash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientController extends AppBaseController
{
    /** @var ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     */
    public function index(Request $request)
    {
        $clients = $this->clientRepository->index(10);

        return view('clients.index')
            ->with('clients', $clients);
    }

    /**
     * Show the form for creating a new Client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created Client in storage.
     */
    public function store(CreateClientRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $passwordString = Str::password(20);
            $register = new RegisterController();
            $input['password'] = $password = App::environment(['production']) ? Hash::make($passwordString) : Hash::make('abcd1234');
            $input['password_confirmation'] = $password;
            $input['first_name'] = $request['first_name'];
            $input['last_name'] = $request['last_name'];
            $input['email'] = $request['email'];
            $input['auth_guard'] = Auth::guard()->name;
            $input['added_by'] = Auth::id();
            $input['userData'] = true;
            $input['registrationType'] = 'client';
            $user = $register->register($request->merge($input), false);
            $user->addRole('client');
            DB::commit();
            $input['password'] = App::environment(['production']) ? $passwordString : 'abcd1234';
            try {
                $template = MailTemplate::where('mailable', ClientRegisteredMail::class)->firstOrFail();
                if ($template->status){
                    Mail::to($user)->send(new ClientRegisteredMail($input));
                }
            } catch (\Exception $e) {
                report($e);
            }
            if ($request->ajax()) {
                return response()->json(
                    [
                        'message' => 'Parent saved successfully.',
                        'redirectTo' => route('clients.index')
                    ]);
            }
            \Laracasts\Flash\Flash::success('Parent saved successfully.');

            return redirect(route('clients.index'));

        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            if ($request->ajax()) {
                return response()->json(
                    [
                        'message' => 'something went wrong.',
                        'redirectTo' => route('clients.index')
                    ]);
            }
            Flash::error('something went wrong');

            return redirect(route('clients.index'));
        }
    }

    /**
     * Display the specified Client.
     */
    public function show($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        return view('clients.show')->with('client', $client);
    }

    /**
     * Show the form for editing the specified Client.
     */
    public function edit($id)
    {
        $client = $this->clientRepository->find($id);
        $this->authorize('update', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        return view('clients.edit')->with('client', $client);
    }

    /**
     * Update the specified Client in storage.
     */
    public function update($id, UpdateClientRequest $request)
    {
        $client = $this->clientRepository->find($id);
        $this->authorize('update', $client);
        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }
        $input = array_filter($request->all());

        if (!empty($input['password'])){
            $input['password'] = Hash::make($request->password);
        }
        $this->clientRepository->update($input, $id);
        if ($request->ajax()) {
            return response()->json(
                [
                    'message' => 'Client updated successfully.',
                ]);
        }
        Flash::success('Client updated successfully.');

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified Client from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $this->clientRepository->delete($id);

        Flash::success('Client deleted successfully.');

        return redirect(route('clients.index'));
    }
}

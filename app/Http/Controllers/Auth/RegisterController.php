<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ParentUser;
use App\Models\Proctor;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:parent');
        $this->middleware('guest:student');
        $this->middleware('guest:tutor');
        $this->middleware('guest:proctor');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse|User
     */
    public function register(Request $request, $autologin = true)
    {
        $this->validator($request->all())->validate();

        if ($request->registrationType == 'parent') {
            $user = $this->createParent($request->all());
        } elseif ($request->registrationType == 'student') {
            $user = $this->createStudent($request->all());
        } elseif ($request->registrationType == 'tutor') {
            $user = $this->createTutor($request->all());
        } elseif ($request->registrationType == 'client') {
            $user = $this->createClient($request->all());
        }elseif ($request->registrationType == 'proctor') {
            $user = $this->createProctor($request->all());
        } else {
            $user = $this->create($request->all());
        }

        event(new Registered($user));

        if ($autologin) {
            $this->guard()->login($user);
        }

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  mixed  $user
     * @return bool|User $user
     */
    protected function registered(Request $request, $user)
    {
        if ($request->userData) {
            return $user;
        }

        return false;
    }

    protected function createParent(array $request)
    {
        Validator::make($request, ParentUser::$rules);

        return ParentUser::create($request);
    }

    protected function createStudent(array $request)
    {
        Validator::make($request, Student::$rules);

        return Student::create($request);
    }

    private function createTutor(array $request)
    {
        Validator::make($request, Tutor::$rules);

        return Tutor::create($request);
    }

    private function createClient(array $request)
    {
        Validator::make($request, Client::$rules);

        return Client::create($request);
    }

    private function createProctor(array $request)
    {
        Validator::make($request, Proctor::$rules);

        return Proctor::create($request);
    }
}

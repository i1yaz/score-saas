<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Models\Session;

class SessionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Session::class);

        return Session::all();
    }

    public function store(SessionRequest $request)
    {
        $this->authorize('create', Session::class);

        return Session::create($request->validated());
    }

    public function show(Session $session)
    {
        $this->authorize('view', $session);

        return $session;
    }

    public function update(SessionRequest $request, Session $session)
    {
        $this->authorize('update', $session);

        $session->update($request->validated());

        return $session;
    }

    public function destroy(Session $session)
    {
        $this->authorize('delete', $session);

        $session->delete();

        return response()->json();
    }
}

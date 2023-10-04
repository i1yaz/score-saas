<?php

namespace App\Http\Controllers;

use App\DataTables\SessionDataTable;
use App\Http\Requests\SessionRequest;
use App\Mail\FlagSessionMail;
use App\Models\Session;
use App\Models\User;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;

class SessionController extends Controller
{
    public function __construct(private SessionRepository $sessionRepository)
    {
    }

    public function index(Request $request)
    {
        if ($request->has(['start', 'end'])) {
            $events = $this->sessionRepository->getFullCalenderEvents($request);

            return response()->json($events);
        }
        if ($request->ajax()) {
            $columns = [
                'scheduled_date',
                'location',
                'student',
                'completion_code',
                'homework_completed_80',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = SessionDataTable::totalRecords();
            $students = SessionDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = SessionDataTable::totalFilteredRecords($search);
            $data = SessionDataTable::populateRecords($students);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('sessions.index');
    }

    public function create()
    {
        //        $this->authorize('create', Session::class);
        $completionCodes = Session::SESSION_COMPLETION_CODE;

        return view('sessions.create', compact('completionCodes'));
    }

    public function store(SessionRequest $request)
    {
        $input = $request->all();
        $input['flag_session'] = isset($input['flag_session']) && $input['flag_session'] == 'yes';
        $input['home_work_completed'] = ($input['home_work_completed'] == 'yes');
        $input['scheduled_date'] = date('Y-m-d', strtotime($input['scheduled_date']));
        if (Auth::user()->hasRole(['tutor'])) {
            $input['tutor_id'] = Auth::user()->id;
        } else {
            $input['tutor_id'] = $input['tutor_id'];
        }

        $this->sessionRepository->create($input);
        if ($input['flag_session']) {
            $admins = User::whereHasRole(['super-admin'])->get(['email']);
            $admins = $admins->pluck('email')->toArray();
            Mail::to($admins)->send(new FlagSessionMail($input));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Session saved successfully.']);
        }
        Flash::success('Session saved successfully.');

        return redirect(route('sessions.index'));
    }

    public function show($session)
    {
        //        $this->authorize('view', $session);
        $session = Session::select(
            [
                'sessions.id as id', 'sessions.scheduled_date', 'sessions.start_time', 'sessions.end_time', 'tutoring_locations.name as tutoring_location_name',
                'sessions.session_completion_code', 'sessions.pre_session_notes', 'sessions.student_parent_session_notes', 'sessions.homework', 'sessions.internal_notes',
                'sessions.home_work_completed as percent_homework_completed_80',
            ])
            ->selectRaw("CONCAT(students.first_name,' ',students.last_name) as student_name")
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', '=', 'sessions.student_tutoring_package_id')
            ->leftJoin('students', 'students.id', '=', 'student_tutoring_packages.student_id')
            ->leftJoin('tutoring_locations', 'student_tutoring_packages.tutoring_location_id', '=', 'tutoring_locations.id')
            ->where('sessions.id', $session)
            ->first();
        if (request()->ajax()) {
            $session = $session->toArray();
            $session['scheduled_date'] = date('m/d/Y', strtotime($session['scheduled_date'] ?? ''));
            $session['start_time'] = date('H:i', strtotime($session['start_time'] ?? ''));
            $session['end_time'] = date('H:i', strtotime($session['end_time'] ?? ''));
            $session['session_completion_code'] = Session::SESSION_COMPLETION_CODE[$session['session_completion_code'] ?? ''];
            $session['percent_homework_completed_80'] = booleanToYesNo($session['percent_homework_completed_80'] ?? '');

            return response()->json($session);
        }

        return view('sessions.show', compact('session'));
    }

    public function edit(Session $session)
    {
        $completionCodes = Session::SESSION_COMPLETION_CODE;
        $session->scheduled_date = date('m/d/Y', strtotime($session->scheduled_date ?? ''));

        return view('sessions.edit', compact('session', 'completionCodes'));
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

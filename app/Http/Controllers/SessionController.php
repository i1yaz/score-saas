<?php

namespace App\Http\Controllers;

use App\DataTables\SessionDataTable;
use App\Http\Requests\CreateSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Mail\FlagSessionMail;
use App\Models\ListData;
use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoicePackageTutor;
use App\Models\Session;
use App\Models\StudentTutoringPackage;
use App\Models\StudentTutoringPackageTutor;
use App\Models\User;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
                'id',
                'tutoring_package',
                'scheduled_date',
                'location',
                'tutor',
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
        $listData = Cache::remember('list_data_session_completion_codes', 60 * 60 * 24, function () {
            return ListData::select(['id', 'name'])->where('list_id', Session::LIST_DATA_LIST_ID)->get();
        });
        $completionCodes = [];
        foreach ($listData as $data) {
            $completionCodes[$data->id] = $data->name;
        }

        return view('sessions.create', compact('completionCodes'));
    }

    public function store(CreateSessionRequest $request)
    {
        $input = $request->all();
        $input['flag_session'] = isset($input['flag_session']) && $input['flag_session'] == 'yes';
        $input['home_work_completed'] = ($input['home_work_completed'] == 'yes');
        $input['scheduled_date'] = date('Y-m-d', strtotime($input['scheduled_date']));
        $tutoringPackageId = $input['tutoring_package_id'];
        if (Auth::user()->hasRole(['tutor'])) {
            if (Str::startsWith($input['tutoring_package_id'], StudentTutoringPackage::PREFIX_START)) {
                $tutoringPackageTutor = StudentTutoringPackageTutor::where(['tutor_id' => Auth::user()->id, 'student_tutoring_package_id' => getOriginalStudentTutoringPackageIdFromCode($tutoringPackageId)])->first();
            }
            if (Str::startsWith($input['tutoring_package_id'], MonthlyInvoicePackage::PREFIX_START)) {
                $tutoringPackageTutor = MonthlyInvoicePackageTutor::where(['tutor_id' => Auth::user()->id, 'monthly_invoice_package_id' => getOriginalMonthlyInvoicePackageIdFromCode($tutoringPackageId)])->first();
            }
        }
        if (Auth::user()->hasRole(['tutor']) && ! empty($tutoringPackageTutor)) {
            $input['tutor_id'] = Auth::user()->id;
        } else if(!Auth::user()->hasRole(['super-admin','admin'])){
            return response()->json(['success' => false, 'message' => 'You are not allowed to create session for this student.'], 404);
        }
        if (Str::startsWith($input['tutoring_package_id'], StudentTutoringPackage::PREFIX_START)) {
            $input['student_tutoring_package_id'] = getOriginalStudentTutoringPackageIdFromCode($input['tutoring_package_id']);
            $input['is_billed'] = Session::BILLED;

        }
        if (Str::startsWith($input['tutoring_package_id'], MonthlyInvoicePackage::PREFIX_START)) {
            $input['monthly_invoice_package_id'] = getOriginalMonthlyInvoicePackageIdFromCode($input['tutoring_package_id']);
            MonthlyInvoicePackage::where('id', $input['monthly_invoice_package_id'])->firstOrFail();
        }
        if (isset($input['session_completion_code']) && (int) $input['session_completion_code'] === Session::PARTIAL_COMPLETION_CODE) {
            if (isset($input['charge_for_missed_time']) && (int) $input['charge_for_missed_time'] == Session::PARTIAL_COMPLETION_CODE) {
                $input['charge_missed_time'] = true;
            }
        } else {
            $input['charge_missed_time'] = false;
            if (isset($input['attended_start_time'])) {
                unset($input['attended_start_time']);
            }
            if (isset($input['attended_end_time'])) {
                unset($input['attended_end_time']);
            }
        }

        $this->sessionRepository->create($input);
        if ($input['flag_session']) {
            $admins = User::whereHasRole(['super-admin'])->get(['email']);
            $admins = $admins->pluck('email')->toArray();
            try {
                $input['package'] = $tutoringPackageId;
                Mail::to($admins)->send(new FlagSessionMail($input));

            } catch (\Exception $e) {
                report($e);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Session saved successfully.']);
        }
        Flash::success('Session saved successfully.');

        return redirect(route('sessions.index'));
    }

    public function show($session)
    {

        $session = Session::select(
            [
                'sessions.id as id', 'sessions.scheduled_date', 'sessions.start_time', 'sessions.end_time', 'tutoring_locations.name as tutoring_location_name',
                'sessions.session_completion_code', 'sessions.pre_session_notes', 'sessions.student_parent_session_notes', 'sessions.homework', 'sessions.internal_notes',
                'sessions.home_work_completed as percent_homework_completed_80',
                'list_data.name as completion_code', 'sessions.tutor_id',
            ])
            ->selectRaw("CONCAT(students.first_name,' ',students.last_name) as student_name")
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', '=', 'sessions.student_tutoring_package_id')
            ->leftJoin('students', 'students.id', '=', 'student_tutoring_packages.student_id')
            ->leftJoin('tutoring_locations', 'student_tutoring_packages.tutoring_location_id', '=', 'tutoring_locations.id')
            ->leftJoin('list_data', function ($join) {
                $join->on('list_data.id', '=', 'sessions.session_completion_code')
                    ->where('list_data.list_id', '=', Session::LIST_DATA_LIST_ID);
            })
            ->where('sessions.id', $session)
            ->first();

        $this->authorize('view', $session);
        if (request()->ajax()) {
            $totalSessionTimeCharged = getTotalChargedTimeOfSessionFromSessionInSeconds($session);
            $session = $session->toArray();
            $session['scheduled_date'] = date('m/d/Y', strtotime($session['scheduled_date'] ?? ''));
            $session['start_time'] = date('H:i', strtotime($session['start_time'] ?? ''));
            $session['end_time'] = date('H:i', strtotime($session['end_time'] ?? ''));
            $session['session_completion_code'] = $session['completion_code'];
            $session['percent_homework_completed_80'] = booleanToYesNo($session['percent_homework_completed_80'] ?? '');
            $session['total_session_time_charged'] = formatTimeFromSeconds($totalSessionTimeCharged);

            return response()->json($session);
        }

        return view('sessions.show', compact('session'));
    }

    public function edit($session)
    {
        $session = Session::select(['sessions.*', 'tutoring_locations.name as tutoring_location_name', 'student_tutoring_packages.hours as package_hours'])
            ->selectRaw("CONCAT(students.first_name,' ',students.last_name) as student_name")
            ->join('student_tutoring_packages', 'student_tutoring_packages.id', '=', 'sessions.student_tutoring_package_id')
            ->join('tutoring_locations', 'student_tutoring_packages.tutoring_location_id', '=', 'tutoring_locations.id')
            ->join('students', 'students.id', '=', 'student_tutoring_packages.student_id')
            ->where('sessions.id', $session)
            ->firstOrFail();
        $this->authorize('update', $session);
        $listData = Cache::remember('list_data_session_completion_codes', 60 * 60 * 24, function () {
            return ListData::select(['id', 'name'])->where('list_id', Session::LIST_DATA_LIST_ID)->get();
        });
        $completionCodes = [];
        foreach ($listData as $data) {
            $completionCodes[$data->id] = $data->name;
        }
        $session->scheduled_date = date('m/d/Y', strtotime($session->scheduled_date ?? ''));

        $selectedTutoringPackage = [
            $session->student_tutoring_package_id => getStudentTutoringPackageCodeFromId($session->tutoring_location_name).' - '.$session->student_name.' - '.$session->package_hours,

        ];
        $selectedLocation = [
            $session->tutoring_location_id => $session->tutoring_location_name,
        ];

        return view('sessions.edit', compact('session', 'completionCodes', 'selectedTutoringPackage', 'selectedLocation'));
    }

    public function update(UpdateSessionRequest $request, Session $session)
    {
        $this->authorize('update', $session);
        $input = $request->all();
        $input['flag_session'] = isset($input['flag_session']) && $input['flag_session'] == 'yes';
        $input['home_work_completed'] = ($input['home_work_completed'] == 'yes');
        $input['scheduled_date'] = date('Y-m-d', strtotime($input['scheduled_date']));
        $tutoringPackageId = $input['tutoring_package_id'];
        if (Auth::user()->hasRole(['tutor'])) {
            if (Str::startsWith($input['tutoring_package_id'], StudentTutoringPackage::PREFIX_START)) {
                $tutoringPackageTutor = StudentTutoringPackageTutor::where(['tutor_id' => Auth::user()->id, 'student_tutoring_package_id' => getOriginalStudentTutoringPackageIdFromCode($tutoringPackageId)])->first();
            }
            if (Str::startsWith($input['tutoring_package_id'], MonthlyInvoicePackage::PREFIX_START)) {
                $tutoringPackageTutor = MonthlyInvoicePackageTutor::where(['tutor_id' => Auth::user()->id, 'monthly_invoice_package_id' => getOriginalMonthlyInvoicePackageIdFromCode($tutoringPackageId)])->first();
            }
        }
        if (Auth::user()->hasRole(['tutor']) && ! empty($tutoringPackageTutor)) {
            $input['tutor_id'] = Auth::user()->id;
        } else if(!Auth::user()->hasRole(['super-admin','admin'])){
            return response()->json(['success' => false, 'message' => 'You are not allowed to create session for this student.'], 404);
        }
        if (Str::startsWith($input['tutoring_package_id'], StudentTutoringPackage::PREFIX_START)) {
            $input['student_tutoring_package_id '] = getPackageCodeFromModel($input['tutoring_package_id']);

        }
        if (Str::startsWith($input['tutoring_package_id'], MonthlyInvoicePackage::PREFIX_START)) {
            $input['monthly_invoice_package_id  '] = getPackageCodeFromModel($input['tutoring_package_id']);
        }
        if (isset($input['session_completion_code']) && (int) $input['session_completion_code'] === Session::PARTIAL_COMPLETION_CODE) {
            if (isset($input['charge_for_missed_time']) && (int) $input['charge_for_missed_time'] == Session::PARTIAL_COMPLETION_CODE) {
                $input['charge_missed_time'] = true;
            }
        } else {
            $input['charge_missed_time'] = false;
            $input['attended_start_time'] = null;
            $input['attended_end_time'] = null;
        }
        $session->update($input);

        return view('sessions.index');
    }

    public function destroy($session)
    {
        $this->authorize('delete', $session);

        $this->sessionRepository->toggleStatus($session);

        return response()->json();
    }
}

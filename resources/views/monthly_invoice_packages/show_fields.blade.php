<div class="col-md-6">
    <div class="card card-success">
        <div class="card-header">
            <h5>Invoice Details</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td><strong> Invoice ID</strong></td>
                    <td>{{getInvoiceCodeFromId($monthlyInvoicePackage->invoice_id)}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For Package</strong></td>
                    <td>{{getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id)}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For</strong></td>
                    <td>{{$monthlyInvoicePackage->invoice_package_type_name}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice Date</strong></td>
                    <td>{{formatDate($monthlyInvoicePackage->invoice_created_at)}}</td>
                </tr>
                <tr>
                    <td><strong> Due Date</strong></td>
                    <td>{{formatDate($monthlyInvoicePackage->due_date)}}</td>
                </tr>
                <tr>
                    <td><strong> General Description</strong></td>
                    <td>{{$monthlyInvoicePackage->general_description}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For Student</strong></td>
                    <td>{{$monthlyInvoicePackage->student_email}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For Parent</strong></td>
                    <td>{{$monthlyInvoicePackage->parent_email}}</td>
                </tr>
                <tr>
                    <td><strong> Created By</strong></td>
                    <td>{{formatDate($monthlyInvoicePackage->invoice_created_at)}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice Status</strong></td>
                    <td>{!!getInvoiceStatusFromId($monthlyInvoicePackage->invoice_status)!!}</td>
                </tr>
                <tr>
                    <td><strong> Invoice Total</strong></td>
                    <td>
                        <strong></strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="col-md-6">
    <div class="card card-cyan">
        <div class="card-header">
            <h5>Tutor Payment Details</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td><strong>Tutor Hourly Rate</strong></td>
                    <td>

                    </td>
                </tr>

                <tr>
                    <td><strong>Total Charged Time</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Total Tutor Payment For Package</strong></td>
                    <td>

                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

@if($monthlyInvoicePackage->sessions->isNotEmpty())
    <div class="col-sm-12">
        <div class="card card-gray">
            <div class="card-header">
                <h5>Sessions</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped" id="student-tutoring-packages-table">
                    <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>Session Date & Time</th>
                        <th>Tutor</th>
                        <th>Session Location</th>
                        <th>Student/Parent Note</th>
                        <th>Tutor Internal Note</th>
                        <th>Session Completion Code</th>
                        <th>Charged Lesson Time</th>
                        <th>Charged Missed Time</th>
                        <th>Total Session Time Charged</th>
                        <th>Tutor $/hr</th>
                        <th>Extra Location $</th>
                        <th>Tutor $ for session</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($monthlyInvoicePackage->sessions as $session)
                        <tr>
                            <td>{{ getSessionCodeFromId($session->id) }}</td>
                            <td>{{ formatDate($session->scheduled_date)  }} {{ formatTime($session->start_time)  }}
                                -{{formatTime($session->end_time)}}</td>
                            <td>
                                <p>{{$session->tutor_email}}</p>
                                <p>{{$session->tutor_name}}</p>
                            </td>
                            <td>{{ $monthlyInvoicePackage->location_name}}</td>
                            <td>{{ $session->student_parent_session_notes}}</td>
                            <td>{{ $session->internal_notes}}</td>
                            <td>{{ $session->completion_code_name}}</td>
                            <td>{{ formatTimeFromSeconds(getTotalChargedSessionTimeFromSessionInSeconds($session))}}</td>
                            <td>{{ formatTimeFromSeconds(getTotalChargedMissedSessionTimeFromSessionInSeconds($session))}} </td>
                            <td>{{ getTotalChargedTimeInHoursSecondsMinutesFromSession($session)}}</td>
                            <td>{{ formatAmountWithCurrency(getTutorHourlyRateForStudentTutoringPackage($monthlyInvoicePackage,$session->tutor_id))}}</td>
                            <td>{{ formatAmountWithCurrency(0)}}</td>
                            <td>{{ getTotalTutorChargedAmountFromSession($session,$monthlyInvoicePackage)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endif

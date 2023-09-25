<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h5>Package Details</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td> <strong> Tutoring Package ID</strong></td>
                    <td>{{getStudentTutoringPackageCodeFromId($studentTutoringPackage->id)}}</td>
                </tr>
                <tr>
                    <td> <strong> Tutoring Package Type</strong></td>
                    <td>{{$studentTutoringPackage->package_name}}</td>
                </tr>
                <tr>
                    <td> <strong> Tutor</strong></td>
                    <td>
                        @if($studentTutoringPackage->tutors)
                            @foreach($studentTutoringPackage->tutors as $tutor)
                                <span style="display: block">{{$tutor->fullName}}</span>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td> <strong> Subjects</strong></td>
                    <td>
                        @if($studentTutoringPackage->subjects())
                            @foreach($studentTutoringPackage->subjects as $subject)
                                <span style="display: block">{{$subject->name}}</span>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td> <strong> Notes</strong></td>
                    <td>{{$studentTutoringPackage->notes}}</td>
                </tr>
                <tr>
                    <td> <strong> Start Date</strong></td>
                    <td>{{ formatDate($studentTutoringPackage->start_date) }}</td>
                </tr>
                <tr>
                    <td> <strong> Primary Tutoring Location</strong></td>
                    <td>{{ $studentTutoringPackage->location_name }}</td>
                </tr>
                <tr>
                    <td> <strong> Number of Hours</strong></td>
                    <td>{{$studentTutoringPackage->hours}}</td>
                </tr>
                <tr>
                    <td> <strong> Hourly Rate</strong></td>
                    <td>{{formatAmountWithCurrency($studentTutoringPackage->hourly_rate)}}</td>
                </tr>
                <tr>
                    <td> <strong>Total Price</strong></td>
                    <td>{{getPriceFromHoursAndHourlyWithoutDiscount($studentTutoringPackage->hourly_rate, $studentTutoringPackage->hours)}}</td>
                </tr>

                <tr>
                    <td> <strong> Discounted Amount</strong></td>
                    <td>{{getDiscountedAmount($studentTutoringPackage->hourly_rate,$studentTutoringPackage->hours, $studentTutoringPackage->discount, $studentTutoringPackage->discount_type)}}</td>
                </tr>
                @if($studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::PERCENTAGE_DISCOUNT)
                    <tr>
                        <td> <strong> Percentage Discount</strong></td>
                        <td>{{$studentTutoringPackage->discount}}%</td>
                    </tr>
                @endif
                <tr>
                    <td> <strong> Final Price</strong></td>
                    <td><strong>{{getPriceFromHoursAndHourlyWithDiscount($studentTutoringPackage->hours, $studentTutoringPackage->hourly_rate, $studentTutoringPackage->discount, $studentTutoringPackage->discount_type)}}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

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
                    <td>{{getInvoiceCodeFromId($studentTutoringPackage->invoice_id)}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For</strong></td>
                    <td>{{$studentTutoringPackage->invoice_package_type_name}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice Date</strong></td>
                    <td>{{formatDate($studentTutoringPackage->invoice_created_at)}}</td>
                </tr>
                <tr>
                    <td><strong> Due Date</strong></td>
                    <td>{{formatDate($studentTutoringPackage->due_date)}}</td>
                </tr>
                <tr>
                    <td><strong> General Description</strong></td>
                    <td>{{$studentTutoringPackage->general_description}}</td>
                </tr>
                <tr>
                    <td><strong> Detailed Description</strong></td>
                    <td>{{$studentTutoringPackage->detailed_description}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For Student</strong></td>
                    <td>{{$studentTutoringPackage->student_email}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice For Parent</strong></td>
                    <td>{{$studentTutoringPackage->parent_email}}</td>
                </tr>
                <tr>
                    <td><strong> Created By</strong></td>
                    <td>{{formatDate($studentTutoringPackage->invoice_created_at)}}</td>
                </tr>
                <tr>
                    <td><strong> Invoice Status</strong></td>
                    <td>{!!getInvoiceStatusFromId($studentTutoringPackage->invoice_status)!!}</td>
                </tr>
                <tr>
                    <td><strong> Invoice Total</strong></td>
                    <td><strong>{{getPriceFromHoursAndHourlyWithDiscount($studentTutoringPackage->hourly_rate,$studentTutoringPackage->hours, $studentTutoringPackage->discount, $studentTutoringPackage->discount_type)}}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

{{--<div class="col-md-6">--}}
{{--    <div class="card">--}}
{{--        <div class="card-header">--}}
{{--            <h5>Tutor Payment Details</h5>--}}
{{--        </div>--}}
{{--        <div class="card-body p-0">--}}
{{--            <table class="table table-striped">--}}
{{--                <tbody>--}}
{{--                <tr>--}}
{{--                    <td>Hourly Rate</td>--}}
{{--                    <td>{{$studentTutoringPackage->hourly_rate}}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td>Total Charged Time</td>--}}
{{--                    <td></td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td>Total Tutor Payment For Package</td>--}}
{{--                    <td></td>--}}
{{--                </tr>--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--</div>--}}

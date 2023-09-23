<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h5>Package Details</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
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
                    <td> <strong> Price After Discount</strong></td>
                    <td>{{getPriceFromHoursAndHourlyWithDiscount($studentTutoringPackage->hours, $studentTutoringPackage->hourly_rate, $studentTutoringPackage->discount, $studentTutoringPackage->discount_type)}}</td>
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
                    <td> <strong> Notes</strong></td>
                    <td>{{$studentTutoringPackage->notes}}</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h5>Invoice Details</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>1.</td>
                    <td>Update software</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-danger">55%</span></td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Clean database</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-warning" style="width: 70%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-warning">70%</span></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h5>Tutor Payment Details</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>Hourly Rate</td>
                    <td>{{$studentTutoringPackage->hourly_rate}}</td>
                </tr>
                <tr>
                    <td>Total Charged Time</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Total Tutor Payment For Package</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

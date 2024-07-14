@foreach($packages as $package)
    <div class="col-sm-3">
        <div class="card card-outline card-primary">
            {!! Form::open(['route' => ['settings-billing.change-package', $package->id], 'method' => 'post','style' => 'display:unset']) !!}

            <div class="card-header" style="text-align: center">
                <h3 style="float: none">{{$package->name}}</h3>
                <p  style="float: none"><input type="radio" name="billing_cycle" value="monthly" class="mr-3" checked ><strong>{{formatAmountWithCurrency($package->amount_monthly)}}</strong>/Month</p>
                <p  style="float: none"><input type="radio" name="billing_cycle" value="yearly"  class="mr-3"><strong>{{formatAmountWithCurrency($package->amount_yearly)}}</strong>/Year</p>
            </div>
            <div class="card-body" style="text-align: center" >
                <p><strong>{{$package->max_students}} </strong> Students</p><hr>
                <p><strong>{{$package->max_tutors}} </strong> Teachers</p><hr>
                <p><strong>{{$package->max_student_packages}} </strong> Student Packages</p><hr>
                <p><strong>{{$package->max_monthly_packages}} </strong> Monthly Packages</p><hr>
            </div>
            <div class="card-footer" style="text-align: center">
                <div class=''>
                    {!! Form::button('Select Package'  , ['type' => 'submit', 'class' => 'btn btn-primary btn-sm confirm-action-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}

                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endforeach

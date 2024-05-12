@foreach($packages as $package)
    <div class="col-sm-3">
        <div class="card card-outline card-primary">
            <div class="card-header" style="text-align: center">
                <h3 style="float: none">{{$package->name}}</h3>
                <p  style="float: none"><strong>{{formatAmountWithCurrency($package->amount_monthly)}}</strong>/Month</p>
                <p  style="float: none"><strong>{{formatAmountWithCurrency($package->amount_yearly)}}</strong>/Year</p>
            </div>
            <div class="card-body" style="text-align: center" >
                <p><strong>{{$package->max_students}} </strong> Students</p><hr>
                <p><strong>{{$package->max_teachers}} </strong> Teachers</p><hr>
                <p><strong>{{$package->max_student_packages}} </strong> Student Packages</p><hr>
                <p><strong>{{$package->max_monthly_packages}} </strong> Monthly Packages</p><hr>
            </div>
            <div class="card-footer" style="text-align: center">
                {!! Form::open(['route' => ['landlord.packages.destroy', $package->id], 'method' => 'delete']) !!}
                <div class=''>
                    <a href="{{ route('landlord.packages.archive', [$package->id]) }}"
                       class='btn btn-warning btn-sm confirm-action-danger'>
                        Archive
                    </a>
                    <a href="{{ route('landlord.packages.edit', [$package->id]) }}"
                       class='btn btn-default btn-sm'>Edit
                    </a>
                    {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endforeach

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
                <div class=''>
                    {!! Form::open(['route' => ['landlord.packages.archive', $package->id], 'method' => 'post','style' => 'display:unset']) !!}
                    @php
                    if($package->status){
                        $name = 'Archive';
                    }else{
                        $name = 'Unarchive';
                    }
                    @endphp
                    {!! Form::button($name  , ['type' => 'submit', 'class' => 'btn btn-warning btn-sm confirm-action-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    {!! Form::close() !!}
                    <a href="{{ route('landlord.packages.edit', [$package->id]) }}"
                       class='btn btn-default btn-sm'>Edit
                    </a>

                    {!! Form::open(['route' => ['landlord.packages.destroy', $package->id], 'method' => 'delete','style' => 'display:unset']) !!}
                    @php
                        $disabled = $package->count() == 0;
                    @endphp
                    {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')",'disabled'=>$disabled]) !!}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="row">
    <div class="col-lg-3 col-6">
        <a href="{{route('students.index')}}" >
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$data['students']['active_students']+$data['students']['inactive_students']}}</h3>
                    <p>Total Students</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <div class="small-box-footer">Inactive Students: {{$data['students']['inactive_students']}}</div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="{{route('parents.index')}}">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$data['parents']['active_parents']+$data['parents']['inactive_parents']}}</h3>
                    <p>Total Parents</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <div  class="small-box-footer">Inactive Parents: {{$data['parents']['inactive_parents']}}</div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="{{route('tutors.index')}}">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$data['tutors']['active_tutors']+$data['tutors']['inactive_tutors']}}</h3>
                    <p>Total Tutors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <div class="small-box-footer">Inactive Tutors: {{$data['tutors']['inactive_tutors']}}</div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="{{route('schools.index')}}">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$data['schools']}}</h3>
                    <p>Total Schools</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <div class="small-box-footer">Inactive Schools: 0</div>
            </div>
        </a>
    </div>

</div>

<div class="row">
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$data['students']['active_students']+$data['students']['inactive_students']}}</h3>
                <p>Total Students</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{route('students.index')}}" class="small-box-footer">Inactive Students: {{$data['students']['inactive_students']}}</a>
        </div>
    </div>

    <div class="col-lg-3 col-6">

        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{$data['parents']['active_parents']+$data['parents']['inactive_parents']}}</h3>
                <p>Total Parents</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{route('parents.index')}}" class="small-box-footer">Inactive Parents: {{$data['parents']['inactive_parents']}}</a>
        </div>
    </div>

    <div class="col-lg-3 col-6">

        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{$data['tutors']['active_tutors']+$data['tutors']['inactive_tutors']}}</h3>
                <p>Total Tutors</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{route('tutors.index')}}" class="small-box-footer">Inactive Tutors: {{$data['tutors']['inactive_tutors']}}</a>
        </div>
    </div>

    <div class="col-lg-3 col-6">

        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$data['schools']}}</h3>
                <p>Total Schools</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{route('schools.index')}}" class="small-box-footer">Inactive Schools: 0</a>
        </div>
    </div>

</div>

<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
@permission('tutor_dashboard-index')
<li class="nav-item">
    <a href="{{ route('tutor-dashboard.index') }}" class="nav-link {{ Request::is('tutor/dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Tutor Dashboard</p>
    </a>
</li>
@endpermission
@if(in_array(Auth::id(),\App\Models\User::CAN_ACCESS_ACL) && Auth::user() instanceof \App\Models\User)
    <li class="nav-item {{Request::is('acl*')?'menu-is-opening menu-open active':''}}">
        <a href="#" class="nav-link {{Request::is('acl*')?'active':''}}">
            <i class="nav-icon fas fa-tree"></i>
            <p>
                Roles & Permissions
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{route('acl.permissions.index')}}" class="nav-link {{ Request::is('acl/permissions*') ? 'active' : '' }}">
                    <p>Permissions</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('acl.roles.index')}}" class="nav-link {{ Request::is('acl/roles*') ? 'active' : '' }}">
                    <p>Roles</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('acl.assignments.index')}}" class="nav-link  {{ Request::is('acl/assignments*') ? 'active' : '' }}">
                    <p>Assign Roles</p>
                </a>
            </li>
        </ul>
    </li>
@endif

@permission('parent-index')
<li class="nav-item">
    <a href="{{ route('parents.index') }}" class="nav-link {{ Request::is('parents*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>Parents</p>
    </a>
</li>
@endpermission

@permission('student-index')
<li class="nav-item">
    <a href="{{ route('students.index') }}" class="nav-link {{ Request::is('students*') ? 'active' : '' }}">
        <i class="nav-icon far fa-user-circle"></i>
        <p>Students</p>
    </a>
</li>
@endpermission
@permission('school-index')
<li class="nav-item">
    <a href="{{ route('schools.index') }}" class="nav-link {{ Request::is('schools*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-school"></i>
        <p>Schools</p>
    </a>
</li>
@endpermission
@permission('tutor-index')
<li class="nav-item">
    <a href="{{ route('tutors.index') }}" class="nav-link {{ Request::is('tutors*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book-reader"></i>
        <p>Tutors</p>
    </a>
</li>
@endpermission

@permission('student_tutoring_package-index')
<li class="nav-item">
    <a href="{{ route('student-tutoring-packages.index') }}" class="nav-link {{ Request::is('student-tutoring-packages*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Tutoring Packages</p>
    </a>
</li>

@endpermission

@permission('invoice-index')
<li class="nav-item">
    <a href="{{ route('invoices.index') }}" class="nav-link {{ Request::is('invoices*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Invoices</p>
    </a>
</li>
@endpermission
@permission('session-index')
<li class="nav-item">
    <a href="{{ route('sessions.index') }}" class="nav-link {{ Request::is('session*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>Sessions</p>
    </a>
</li>
@endpermission
@permission(['tutoring_package_type-index','subject-index','tutoring_location-index'])
<li class="nav-item {{Request::is(['tutoring-package-types*','subjects*','tutoring-locations*'])?'menu-is-opening menu-open active':''}}">
    <a href="#" class="nav-link {{Request::is(['tutoring-package-types*','subjects*','tutoring-locations*'])?'active':''}}">
        <i class="nav-icon fas fa-tree"></i>
        <p>
            Settings
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @permission('tutoring_package_type-index')

        <li class="nav-item">
            <a href="{{ route('tutoring-package-types.index') }}" class="nav-link {{ Request::is('tutoring-package-types*') ? 'active' : '' }}">
                <p>Tutoring Package Types</p>
            </a>
        </li>
        @endpermission
        @permission('subject-index')
        <li class="nav-item">
            <a href="{{ route('subjects.index') }}" class="nav-link {{ Request::is('subjects*') ? 'active' : '' }}">
                <p>Subjects</p>
            </a>
        </li>
        @endpermission
        @permission('tutoring_location-index')
        <li class="nav-item">
            <a href="{{ route('tutoring-locations.index') }}" class="nav-link {{ Request::is('tutoring-locations*') ? 'active' : '' }}">
                <p>Tutoring Locations</p>
            </a>
        </li>
        @endpermission
        @permission('invoice_package_type-index')
        <li class="nav-item">
            <a href="{{ route('invoice-package-types.index') }}" class="nav-link {{ Request::is('invoice-package-types*') ? 'active' : '' }}">
                <p>Invoice Package Types</p>
            </a>
        </li>
        @endpermission
    </ul>
</li>
@endpermission

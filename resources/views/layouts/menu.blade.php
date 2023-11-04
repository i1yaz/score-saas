@permission('super_admin-dashboard')
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
@endpermission
@permission('tutor_dashboard-index')
<li class="nav-item">
    <a href="{{ route('tutor-dashboard.index') }}" class="nav-link {{ Request::is('tutor/dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
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
        <i class="nav-icon fas fa-table"></i>
        <p>Tutoring Packages</p>
    </a>
</li>

@endpermission
@permission('monthly_invoice_package-index')
<li class="nav-item">
    <a href="{{ route('monthly-invoice-packages.index') }}" class="nav-link {{ Request::is('monthly-invoice-packages*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p style="white-space: nowrap;">Monthly Invoice Packages</p>
    </a>
</li>
@endpermission

@permission('invoice-index')
<li class="nav-item">
    <a href="{{ route('invoices.index') }}" class="nav-link {{ Request::is('invoices*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-bill-wave"></i>
        <p>Invoices</p>
    </a>
</li>
@endpermission
@permission('session-index')
<li class="nav-item">
    <a href="{{ route('sessions.index') }}" class="nav-link {{ Request::is('session*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar"></i>
        <p>Sessions</p>
    </a>
</li>
@endpermission
@permission('payment-index')
<li class="nav-item">
    <a href="{{ route('payments.index') }}" class="nav-link {{ Request::is('payments*') ? 'active' : '' }}">
        <i class="fas fa-money-check-alt"></i>
        <p>Payments</p>
    </a>
</li>
@endpermission
@permission(['tutoring_package_type-index','subject-index','tutoring_location-index','school-index','invoice_package_type-index','client-index','tax-index','line_item-index'])
<li class="nav-item {{Request::is(['tutoring-package-types*','subjects*','tutoring-locations*','schools*','invoice-package-types*','taxes*','line-items*','clients*'])?'menu-is-opening menu-open active':''}}">
    <a href="#" class="nav-link {{Request::is(['tutoring-package-types*','subjects*','tutoring-locations*','schools*','invoice-package-types*','taxes*','line-items*','clients*'])?'active':''}}">
        <i class="nav-icon fas fa-cog"></i>
        <p>
            Settings
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @permission('school-index')
        <li class="nav-item">
            <a href="{{ route('schools.index') }}" class="nav-link {{ Request::is('schools*') ? 'active' : '' }}">
                <p>Schools</p>
            </a>
        </li>
        @endpermission
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
        @permission('tax-index')
        <li class="nav-item">
            <a href="{{ route('taxes.index') }}" class="nav-link {{ Request::is('taxes*') ? 'active' : '' }}">
                <p>Taxes</p>
            </a>
        </li>
        @endpermission
        @permission('line_item-index')
        <li class="nav-item">
            <a href="{{ route('line-items.index') }}" class="nav-link {{ Request::is('line-items*') ? 'active' : '' }}">
                <p>Line Items</p>
            </a>
        </li>
        @endpermission
        @permission('client-index')
        <li class="nav-item">
            <a href="{{ route('clients.index') }}" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
                <p>Clients</p>
            </a>
        </li>
        @endpermission
    </ul>
</li>
@endpermission

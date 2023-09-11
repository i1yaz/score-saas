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
                <i class="far fa-circle nav-icon"></i>
                <p>Permissions</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('acl.roles.index')}}" class="nav-link {{ Request::is('acl/roles*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Roles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('acl.assignments.index')}}" class="nav-link  {{ Request::is('acl/assignments*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Assign Roles</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
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

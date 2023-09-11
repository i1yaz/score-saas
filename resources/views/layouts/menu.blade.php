<!-- need to remove -->
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

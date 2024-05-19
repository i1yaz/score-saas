<li class="nav-item">
    <a href="{{ route('landlord.home') }}" class="nav-link {{ Request::is('landlord.home') ? 'active' : (Request::is('landlord.root') ?'active':'') }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('landlord.customers.index') }}" class="nav-link {{ Request::is('landlord.customers.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Customers</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('landlord.packages.index') }}" class="nav-link {{ Request::is('landlord.packages.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-box"></i>
        <p>Packages</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('landlord.subscriptions.index') }}" class="nav-link {{ Request::is('landlord.subscriptions.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-sync"></i>
        <p>Subscriptions</p>
    </a>
</li>
<li class="nav-item {{Request::is(['app-admin/settings*'])?'menu-is-opening menu-open active':''}}">
    <a href="#" class="nav-link {{Request::is(['app-admin/settings*'])?'active':''}}">
        <i class="nav-icon fas fa-cog"></i>
        <p>
            Settings
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('landlord.settings.general.show')}}" class="nav-link {{ Request::is('app-admin/settings/general') ? 'active' : '' }}">
                <p>General Settings</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('landlord.settings.company.show')}}" class="nav-link {{ Request::is('app-admin/settings/company') ? 'active' : '' }}">
                <p>Company Details</p>
            </a>
        </li>
        <li class="nav-item {{Request::is(['app-admin/settings/email-templates*','app-admin/settings/email*','app-admin/settings/smtp*'])?'menu-is-opening menu-open active':''}}">
            <a href="#" class="nav-link {{Request::is(['app-admin/settings/email-templates*','app-admin/settings/email*','app-admin/settings/smtp*'])?'active':''}}">
                <p>
                    Email
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('landlord.settings.email-templates.show')}}" class="nav-link {{ Request::is('app-admin/settings/email-templates') ? 'active' : '' }}">
                        <p>Email Templates</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('landlord.settings.email.show')}}" class="nav-link {{ Request::is('app-admin/settings/email') ? 'active' : '' }}">
                        <p>Email Settings</p>
                    </a>
                </li>
            {{--            <a href="{{route('landlord.settings.logo.show')}}" class="nav-link {{ Request::is('app-admin/settings/email-log') ? 'active' : '' }}">--}}
            {{--                <p>Email Log</p>--}}
            {{--            </a>--}}
                <li class="nav-item">
                    <a href="{{route('landlord.settings.smtp.show')}}" class="nav-link {{ Request::is('app-admin/settings/smtp') ? 'active' : '' }}">
                        <p>SMTP Settings</p>
                    </a>
                </li>
            {{--            <a href="{{route('landlord.settings.cronjob.show')}}" class="nav-link {{ Request::is('app-admin/settings/cronjob') ? 'active' : '' }}">--}}
            {{--                <p>Cron Job</p>--}}
            {{--            </a>--}}
            </ul>
        </li>
        <li class="nav-item {{Request::is(['app-admin/settings/gateways*','app-admin/settings/stripe'])?'menu-is-opening menu-open active':''}}">
            <a href="#" class="nav-link {{Request::is(['app-admin/settings/gateways*','app-admin/settings/stripe'])?'active':''}}">
                <p>
                    Payment Gateways
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('landlord.settings.gateways.show')}}" class="nav-link {{ Request::is('app-admin/settings/gateways') ? 'active' : '' }}">
                        <p>General Settings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('landlord.settings.stripe.show')}}" class="nav-link {{ Request::is('app-admin/settings/stripe') ? 'active' : '' }}">
                        <p>Stripe</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

</li>

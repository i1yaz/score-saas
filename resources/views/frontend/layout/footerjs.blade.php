    <!--VENDORS-->
    <script src="{{asset('js/landlord/frontend/vendor.js')}}"></script>

    <!-- Mobile Menu JS -->
    <script src="{{asset('themes/frontend/assets/plugins/meanmenu/jquery.meanmenu.min.js')}}"></script>

    <!-- Main Script JS -->
    <script src="{{asset('themes/frontend/assets/js/script.js')}}"></script>

    <!--nextloop.core.js-->
    <script src="{{asset('js/landlord/frontend/ajax.js')}}"></script>

    <!--app.js-->
    <script src="{{asset('js/landlord/frontend/app.js')}}"></script>

    <!--events.js-->
    <script src="{{asset('js/landlord/frontend/events.js')}}"></script>

    <!--customer body code-->
    {!! _clean(config('system.settings_code_body')) !!}

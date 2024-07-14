<!DOCTYPE html>
<html lang="en">

@include('frontend.layout.header')

<body class="inner-page pricing">

    @include('frontend.layout.menu')

    @include('frontend.layout.preloader')


    <!--page heading-->
    <div class="container page-wrapper pricing">

        <div class="page-header pricing-header text-center">
            <h2>{!! _clean($content->data_1) !!}</h2>
            <h5>{!! _clean($content->data_2) !!}</h5>
        </div>


        <!--pricing toggle-->
        <div class="switch-container">
            <!-- Rounded switch -->
            <h4 class="pricing-toggle-period active" id="pricing-toggle-monthly">@lang('lang.monthly')</h4>

            <label class="switch">
                <input id="price-cycle-switch" type="checkbox">
                <span class="slider round"></span>
            </label>

            <h4 class="pricing-toggle-period" id="pricing-toggle-yearly">@lang('lang.yearly')</h4>
        </div>



        <!--[MONTHLY]-->
        <div class="pricing-table-wrapper" id="pricing-tables-monthly">
            <div class="pricing-table card-deck row" id="pricing-tables-monthly">

                <!--each plan-->
                @foreach($packages as $package)
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card box-shadow pricing-plan pricing-featured-{{ $package->featured }}">
                        <div class="card-body">
                            <h4 class="my-0 font-weight-normal">{{ $package->name }}</h4>
                            <h1 class="card-title pricing-card-title">
                                {{ runtimeMoneyFormatPricing($package->amount_monthly) }} <small
                                    class="text-muted">/
                                    @lang('lang.month')</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li>
                                    @if($package->max_students > 0 || $package->max_students == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_students') -
                                        {{ runtimeCheckUnlimited($package->max_students) }}</span>
                                </li>
                                <li>
                                    @if($package->max_tutors > 0 || $package->max_tutors == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_tutors') -
                                        {{ runtimeCheckUnlimited($package->max_tutors) }}</span>
                                </li>

                                <li>
                                    @if($package->max_student_packages > 0 || $package->max_student_packages == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_student_packages') -
                                        {{ runtimeCheckUnlimited($package->max_student_packages) }}</span>
                                </li>

                                <li>
                                    @if($package->max_monthly_packages > 0 || $package->max_monthly_packages == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_monthly_packages') -
                                        {{ runtimeCheckUnlimited($package->max_monthly_packages) }}</span>
                                </li>


                            </ul>
                            @if($package->subscription_options == 'free')
                            <a type="button" href="{{ url('account/signup?ref=free_'.$package->id) }}"
                                class="frontent-pricing-button">{{ $content->data_4 ?? '' }}</a>
                            @else
                            <a type="button" href="{{ url('account/signup?ref=monthly_'.$package->id) }}"
                                class="frontent-pricing-button">{{ $content->data_4 ?? '' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>


        <!--[YEARLY]-->
        <div class="pricing-table-wrapper hidden" id="pricing-tables-yearly">
            <div class="pricing-table card-deck row">

                <!--each plan-->
                @foreach($packages as $package)
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card box-shadow pricing-plan pricing-featured-{{ $package->featured }}">
                        <div class="card-body">
                            <h4 class="my-0 font-weight-normal">{{ $package->name }}</h4>
                            <h1 class="card-title pricing-card-title">
                                {{ runtimeMoneyFormatPricing($package->amount_yearly) }} <small
                                    class="text-muted">/
                                    @lang('lang.year')</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">

                                <li>
                                    @if($package->max_students > 0 || $package->max_students == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_students') -
                                        {{ runtimeCheckUnlimited($package->max_students) }}</span>
                                </li>
                                <li>
                                    @if($package->max_tutors > 0 || $package->max_tutors == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_tutors') -
                                        {{ runtimeCheckUnlimited($package->max_tutors) }}</span>
                                </li>

                                <li>
                                    @if($package->max_student_packages > 0 || $package->max_student_packages == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_student_packages') -
                                        {{ runtimeCheckUnlimited($package->max_student_packages) }}</span>
                                </li>

                                <li>
                                    @if($package->max_monthly_packages > 0 || $package->max_monthly_packages == -1)
                                        <span class="x-icon x-icon-yes"><i class="mdi mdi-check"></i></span>
                                    @else
                                        <span class="x-icon x-icon-no"><i class="mdi mdi-window-close"></i></span>
                                    @endif
                                    <span class="x-text font-weight-500">@lang('lang.max_monthly_packages') -
                                        {{ runtimeCheckUnlimited($package->max_monthly_packages) }}</span>
                                </li>

                            </ul>
                            @if($package->subscription_options == 'free')
                            <a type="button" href="{{ url('account/signup?ref=free_'.$package->id) }}"
                                class="frontent-pricing-button">{{ $content->data_4 ?? '' }}</a>
                            @else
                            <a type="button" href="{{ url('account/signup?ref=yearly_'.$package->id) }}"
                                class="frontent-pricing-button">{{ $content->data_4 ?? '' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach



            </div>
        </div>

    </div>

    @include('frontend.layout.footer')

    @include('frontend.layout.footerjs')
</body>

</html>

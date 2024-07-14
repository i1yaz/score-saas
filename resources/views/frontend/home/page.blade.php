<!DOCTYPE html>
<html lang="en">

@include('frontend.layout.header')

<body class="home-page">

    @include('frontend.layout.menu')

    @include('frontend.layout.preloader')

    <main>

        <div class="corner-image" {!! _clean(dynamicStyleBackgroundImage($payload['hero']->data_8,
            $payload['hero']->data_9)) !!}></div>

        <!-- HERO AREA START -->
        <section class="hero_area">
            <div class="hero_area_image" {!! _clean(dynamicStyleBackgroundImage($payload['hero']->directory,
                $payload['hero']->filename)) !!}></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="hero_area_inner">
                            <h1 class="x-heading-1">{{ $payload['hero']->data_1 }}</h1>
                            <h2 class="x-heading-2">{{ $payload['hero']->data_2 }}</h2>
                            <div class="x-description">{{ $payload['hero']->data_3 }}</div>
                            <a href="{{ $payload['hero']->data_5 }}"
                                class="site_btn x-button-1">{{ $payload['hero']->data_4 }}</a>
                            <a href="{{ $payload['hero']->data_7 }}"
                                class="site_btn_2">{{ $payload['hero']->data_6 }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- HERO AREA END -->

        <!-- SECTION - 1 -->
        <section class="section-1 section_padding_off features features_padding section_1 m-t-10" {!!
            _clean(dynamicStyleBackgroundImage($payload['section1_title']->directory,
            $payload['section1_title']->filename)) !!}>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section_title">
                            <span class="section_title_meta">{{ $payload['section1_title']->data_1 }}</span>
                            <h2>{{ $payload['section1_title']->data_2 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    @foreach($payload['section1_content'] as $content)
                    <div class="col-md-3">
                        <div class="">
                            <div class="features_items_icon">
                                <img
                                    src="{{ url('frontend/'.$content->directory.'/'.$content->filename) }}">
                            </div>
                            <div class="features_items_inner">
                                <h4>{{ $content->data_1 }}</h4>
                                <p>{{ $content->data_2 }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>


        <!-- SECTION - 2 -->
        <section class="featured_section section_padding about_us section_2 m-t-70">
            <div class="row">
                <div class="col-lg-6">
                    <div class="featured-section-image section-image-left p-l-50">
                        <img
                            src="{{ url('frontend/'.$payload['section2']->directory.'/'.$payload['section2']->filename) }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about_us_inner p-r-40">
                        <span>{{ $payload['section2']->data_1 }}</span>
                        <h3>{{ $payload['section2']->data_2 }}</h3>
                        {!! _clean($payload['section2']->data_3) !!}
                    </div>
                </div>
            </div>
        </section>



        <!-- SECTION - 3 -->
        <section class="section_padding cta_area section_3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="cta_area_inner">
                            <h3>{{ $payload['section3']->data_1 }}</h3>
                            <div class="p-t-0 p-b-40">
                                {!! _clean($payload['section3']->data_2) !!}
                            </div>
                            @if($payload['section3']->data_3 != '' && $payload['section3']->data_5 != '')
                            <div class="cta_area_inner_btns">
                                @if($payload['section3']->data_3 != '')
                                <a href="{{ $payload['section3']->data_4 }}"
                                    class="site_btn">{{ $payload['section3']->data_3 }}</a>
                                @endif
                                @if($payload['section3']->data_5 != '')
                                <a href="{{ $payload['section3']->data_6 }}"
                                    class="site_btn_2">{{ $payload['section3']->data_5 }}</a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION - 4 -->
        <section class="featured_section section_padding about_us section_4 m-t-60">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about_us_inner p-l-40">
                        <span>{{ $payload['section4']->data_1 }}</span>
                        <h3>{{ $payload['section4']->data_2 }}</h3>
                        {!! _clean($payload['section4']->data_3) !!}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="featured-section-image section-image-left p-r-50">
                        <img
                            src="{{ url('frontend/'.$payload['section4']->directory.'/'.$payload['section4']->filename) }}">
                    </div>
                </div>
            </div>
        </section>


        <!-- SECTION - 5 -->
        <section class="section_padding features latest_features">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section_title">
                            <span class="section_title_meta">{{ $payload['section5_title']->data_1 }}</span>
                            <h2>{{ $payload['section5_title']->data_2 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    @foreach($payload['section5_content'] as $content)
                    <div class="col-md-4">
                        <div class="featured_box">
                            <div class="features_items_icon">
                                <img
                                    src="{{ url('frontend/'.$content->directory.'/'.$content->filename) }}">
                            </div>
                            <div class="features_items_inner">
                                <h4 class="highlighted_title">{{ $content->data_1 }}</h4>
                                <p>{{ $content->data_2 }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>


        <!-- SECTION - 6 -->
        {{-- <section class="section_padding cta_area cta_area_showcase m-t-50">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="cta_area_inner">
                            <h3 class="m-b-10">{{ $payload['splash_title']->data_1 }}</h3>
                            <div class="p-t-0 p-b-20">
                                {!! _clean($payload['splash_title']->data_2) !!}
                            </div>

                            <div class="x-menu">
                                <ul>
                                    <li><a class="js_home_showcase active" href="javascript:void(0);"
                                        data-element="splash-image-1">{{ $payload['splash1']->data_1 }}</a>
                                    </li>
                                    <li><a class="js_home_showcase" href="javascript:void(0);"
                                            data-element="splash-image-2">{{ $payload['splash2']->data_1 }}</a>
                                    </li>
                                    <li><a class="js_home_showcase" href="javascript:void(0);"
                                            data-element="splash-image-3">{{ $payload['splash3']->data_1 }}</a>
                                    </li>
                                    <li><a class="js_home_showcase" href="javascript:void(0);"
                                            data-element="splash-image-4">{{ $payload['splash4']->data_1 }}</a>
                                    </li>
                                    <li><a class="js_home_showcase" href="javascript:void(0);"
                                            data-element="splash-image-5">{{ $payload['splash5']->data_1 }}</a>
                                    </li>
                                    <li><a class="js_home_showcase" href="javascript:void(0);"
                                            data-element="splash-image-6">{{ $payload['splash6']->data_1 }}</a>
                                    </li>
                                </ul>
                            </div>


                            <div class="x-image-container">
                                <!--splash-image-1-->
                                <div class="splash-images" id="splash-image-1">
                                    <img
                                        src="{{ url('frontend/'.$payload['splash1']->directory.'/'.$payload['splash1']->filename) }}">
                                </div>

                                <!--splash-image-2-->
                                <div class="splash-images hidden" id="splash-image-2" class="hidden">
                                    <img
                                        src="{{ url('frontend/'.$payload['splash2']->directory.'/'.$payload['splash2']->filename) }}">
                                </div>

                                <!--splash-image-3-->
                                <div class="splash-images hidden" id="splash-image-3" class="hidden">
                                    <img
                                        src="{{ url('frontend/'.$payload['splash3']->directory.'/'.$payload['splash3']->filename) }}">
                                </div>

                                <!--splash-image-4-->
                                <div class="splash-images hidden" id="splash-image-4" class="hidden">
                                    <img
                                        src="{{ url('frontend/'.$payload['splash4']->directory.'/'.$payload['splash4']->filename) }}">
                                </div>

                                <!--splash-image-5-->
                                <div class="splash-images hidden" id="splash-image-5" class="hidden">
                                    <img
                                        src="{{ url('frontend/'.$payload['splash5']->directory.'/'.$payload['splash5']->filename) }}">
                                </div>

                                <!--splash-image-6-->
                                <div class="splash-images hidden" id="splash-image-6" class="hidden">
                                    <img
                                        src="{{ url('frontend/'.$payload['splash6']->directory.'/'.$payload['splash6']->filename) }}">
                                </div>

                            </div>
                            @if($payload['splash_title']->data_3 != '' && $payload['splash_title']->data_5 != '')
                            <!--call to action-->
                            <div class="p-t-70 p-b-50 cta_buttons">
                                @if($payload['splash_title']->data_3 != '')
                                <a href="{{ $payload['splash_title']->data_4 }}"
                                    class="site_btn">{{ $payload['splash_title']->data_3 }}</a>
                                @endif
                                @if($payload['splash_title']->data_5 != '')
                                <a href="{{ $payload['splash_title']->data_6 }}"
                                    class="site_btn_2">{{ $payload['splash_title']->data_5 }}</a>
                                @endif
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
        <!-- CTA AREA END -->
    </main>

    @include('frontend.layout.footer')

    @include('frontend.layout.footerjs')
</body>

</html>

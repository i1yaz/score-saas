<!-- CTA PANEL -->
@if(isset($payload['show_footer_cta']) && $payload['show_footer_cta'])
<section class="section_padding cta_area section_3 m-t-80" id="footer_cta_panel">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="cta_area_inner">
                    <h3>{{ $cta->data_1 }}</h3>
                    <div class="p-t-0 p-b-40">
                        {!! _clean($cta->data_2) !!}
                    </div>

                    @if($cta->data_3 != '' && $cta->data_5 != '')
                    <div class="cta_area_inner_btns">
                        @if($cta->data_3 != '')
                        <a href="{{ $cta->data_4 }}"
                            class="site_btn">{{ $cta->data_3 }}</a>
                        @endif
                        @if($cta->data_5 != '')
                        <a href="{{ $cta->data_6 }}"
                            class="site_btn_2">{{ $cta->data_5 }}</a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif



<!-- FOOTER AREA START -->
<footer class="footer"  id="footer_wrapper">
    <div class="container">
        <div class="row">

            <!--section 1-->
            @if($footer->data_1 != '')
            <div class="col-sm-12 col-lg-3" id="footer_section_1">
                <div class="footer-content">
                    {!! _clean($footer->data_1) !!}
                </div>
            </div>
            @endif

            <!--section 2-->
            @if($footer->data_2 != '')
            <div class="col-sm-12 col-lg-3" id="footer_section_2">
                <div class="footer-content">
                    {!! _clean($footer->data_2) !!}
                </div>
            </div>
            @endif


            <!--section 3-->
            @if($footer->data_3 != '')
            <div class="col-sm-12 col-lg-3" id="footer_section_3">
                <div class="footer-content">
                    {!! _clean($footer->data_3) !!}
                </div>
            </div>
            @endif


            <!--section 4-->
            @if($footer->data_4 != '')
            <div class="col-sm-12 col-lg-3" id="footer_section_4">
                <div class="footer-content">
                    {!! _clean($footer->data_4) !!}
                </div>
            </div>
            @endif



        </div>
    </div>
</footer>
<!-- FOOTER AREA END -->

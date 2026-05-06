<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Home Page')


@section('content')
<style>
    /* Ensure the fixed header is always on top of the tabs during scroll */
    .menu-head.fix-menu {
        z-index: 1050 !important;
    }

    /* Clear the absolute header on page load */
    .perpration-page-banner.common-banner {
        margin-top: 110px !important;
    }

    /* Prevent tabs from creating a higher stacking context than the header */
    .carrier-glance .nav-tabs {
        position: relative;
        z-index: 10;
    }

    @media (max-width: 991px) {
        .perpration-page-banner.common-banner {
            margin-top: 80px !important;
        }
    }
</style>
<div class="perpration-page-banner common-banner" style="    margin-bottom: 55px;">
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
        @foreach($banners as $banner)
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('home/aboutus/'.$banner->banner) }}" class="img-fluid" alt="img">
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="carrier-glance">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($founderThoughts as $key => $founderThought)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $key == 0 ? 'active' : '' ?>" id="thought-tab{{$key+1}}" data-toggle="tab" href="#thought{{$key+1}}" role="tab" aria-controls="thought" aria-selected="<?= $key == 0 ? 'true' : 'false' ?>">
                            <span class="mr-1 tab-icon">
                                <img class="img-fluid" src="{{ asset('home/aboutus/'.$founderThought?->icon) }}" alt="img">
                                {{$founderThought?->title}}
                            </span>
                        </a>
                    </li>
                    @endforeach
                    @foreach($bannerSectionTwos as $sectionKey => $sectionTwo)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " id="home-tab" data-toggle="tab" href="#section-tab{{$sectionKey+1}}" role="tab" aria-controls="section-tab{{$sectionKey+1}}" aria-selected="false">
                            <span class="mr-1 tab-icon">
                                <img src="{{ asset('home/aboutus/'.$sectionTwo->banner) }}" alt="img"></span>
                            {{$sectionTwo->title}}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach($founderThoughts as $key => $founderThought)
                    <div class="tab-pane <?= $key == 0 ? ' active show ' : '' ?>" id="thought{{$key+1}}" role="tabpanel" aria-labelledby="thought-tab{{$key+1}}">
                        <div class="container">
                            <!-- About 1 - Bootstrap Brain Component -->
                            <section class="">
                                <div class="container">
                                    <div class="row gy-3 gy-md-4 gy-lg-0 " style="border-radius: 8px;border: 2px solid #dae1ef;padding: 18px;background-color: #f9fbff;">
                                        <div class="col-12 col-lg-3">
                                            <div class="row text-center">
                                                <div class="col">
                                                    <img src="{{asset('home/aboutus/'.$founderThought?->picture)}}" alt="maximize-icon" class="img-fluid rounded" loading="lazy">
                                                </div>
                                            </div>
                                            <div class="row text-center mt-5" style="margin-top: 41%;">
                                                <div class="col">
                                                    <div class="title">
                                                        <h6>{{$founderThought?->name}}</h6>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-12 col-lg-9" style="border-left: 1px solid #848e8e;">
                                            {!! $founderThought?->message !!}
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    @endforeach
                    @foreach($bannerSectionTwos as $sectionKey => $sectionTwo)
                    <div class="tab-pane" id="section-tab{{$sectionKey+1}}" role="tabpanel" aria-labelledby="section-tab{{$sectionKey+1}}">
                        <div class="custom-tooltip-container">
                            <div class="add">
                                <div class="custom-grid">
                                    <div class="dummy-image">
                                        <img class="img-fluid" src="{{ asset('website/assets/images/prepration/dummy-img.jpg') }}" alt="img">
                                    </div>
                                    <div class="custom-tooltip-content specialised">
                                        {!! $sectionTwo->description !!}
                                    </div>
                                </div>
                            </div>
                            <div class="grid-list">
                                <div class="custom-grid">
                                    <div class="click" data-id="specialised">
                                        <div class="cusom-tooltip-list-item">
                                            <span class="tooltip-icon me-3">
                                                <img src="{{ asset('home/aboutus/'.$sectionTwo->service_a_image) }}" alt="img">
                                            </span>

                                            <div class="custom-tooltip-list-content">
                                                <h6>{{$sectionTwo->service_a}}</h6>
                                                <p>{!! $sectionTwo->service_a_description !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="click" data-id="onlineAppointement">
                                        <div class="cusom-tooltip-list-item">
                                            <span class="tooltip-icon tooltip-icon-1 me-3">
                                                <img src="{{ asset('home/aboutus/'.$sectionTwo->service_b_image) }}" alt="img">
                                            </span>

                                            <div class="custom-tooltip-list-content">
                                                <h6>{{$sectionTwo->service_b}}</h6>
                                                {!! $sectionTwo->service_b_description !!}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="click" data-id="emergency">
                                        <div class="cusom-tooltip-list-item">
                                            <span class="tooltip-icon tooltip-icon-2 me-3">
                                                <img src="{{ asset('home/aboutus/'.$sectionTwo->service_c_image) }}" alt="img">
                                            </span>

                                            <div class="custom-tooltip-list-content">
                                                <h6>{{$sectionTwo->service_c}}</h6>
                                                {!! $sectionTwo->service_c_description !!}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="click" data-id="suppport">
                                        <div class="cusom-tooltip-list-item">
                                            <span class="tooltip-icon tooltip-icon-3 me-3">
                                                <img src="{{ asset('home/aboutus/'.$sectionTwo->service_d_image) }}" alt="img">
                                            </span>

                                            <div class="custom-tooltip-list-content">
                                                <h6>{{$sectionTwo->service_d}}</h6>
                                                {!! $sectionTwo->service_d_description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="custom-tooltip-list">

                </div>
            </div>
        </div>
    </div>
</div>

<!--SECOND SECTION-->
<div class="maximize-value">
    <section>
        <div class="subscribe-off comm-p-t-b">
            <div class="container">
                <div class="comm-tit-ani tit ani-tit pb-2">
                    <p>{{$bannerSectionThreeHeader?->section_remarks}}</p>
                    @if($bannerSectionThreeHeader?->section_title)
                    <h2>{!! wrapHalfTitleInSpan($bannerSectionThreeHeader?->section_title) !!}<br></h2><span class="line"></span>
                    @endif
                </div>
                <div class="row">
                    @foreach($bannerSectionThrees as $bannerSectionThree)
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 meet-experts wow-ani ani-strt">
                        <div class="maximize-value-widget" style="max-height: 260px;min-height: 260px;">
                            <div class="maximize-icon">
                                <img src="{{ asset('home/aboutus/'.$bannerSectionThree->image) }}" alt="maximize-icon">
                            </div>
                            <h6>{{$bannerSectionThree->title}}</h6>
                            <p style="max-height: 88px;overflow:hidden">{!!$bannerSectionThree->description!!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </section>
</div>

<section>
    <div class="travel-experts single-pg-experts our-way">
        <!--TRAVEL EXPERTS BACKGROUND DOT AND ANIMATIONS -->
        <div class="experts-bg-dt">
            <div class="exp-dt-1"></div>
        </div>
        <!-- END -->
        <!--ALL SECTION COMMEN TITTLE-->
        <div class="comm-tit-ani tit ani-tit">
            <h2>{!! wrapHalfTitleInSpan($bannerSectionFourHeader?->section_title) !!}<br></h2><span class="line"></span>
            <h6>{{$bannerSectionFourHeader?->section_remarks}}</h6>
        </div>
        <div class="container">
            <div class="row">
                <!--TRAVEL EXPERTS IMG AND NAME -->
                @foreach($bannerSectionFours as $bannerSectionFour)
                <div class="ani-eql col-lg-3 col-md-6 meet-experts wow-ani ani-strt">
                    <!--TRAVEL EXPERTS IMG-->
                    <div class="travel-exp-1">
                        <h4>{{$bannerSectionFour->title}}</h4>
                        <img src="{{ asset('home/aboutus/'.$bannerSectionFour->image)}}" alt="img">
                        <h5>{!!$bannerSectionFour->description!!}</h5>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="our-values">
    <div class="container">
        <div class="comm-tit-ani tit ani-tit text-center pb-2">
            <p>{{$bannerSectionFiveHeader?->section_remarks}}</p>
            <h2>{!! wrapHalfTitleInSpan($bannerSectionFiveHeader?->section_title) !!}<br></h2><span class="line"></span>
        </div>
        <div class="row">
            @foreach($bannerSectionFives as $bannerSectionFive)
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="our-values-widget d-flex" style="max-height: 137px;">
                    <div class="our-values-icon mr-3">
                        <img src="{{ asset('home/aboutus/'.$bannerSectionFive->image) }}" alt="icon">
                    </div>
                    <div class="our-values-widget-content" style="max-height: 78px;overflow: hidden;">
                        <h6>{{$bannerSectionFive->title}}</h6>
                        {!!$bannerSectionFive->description!!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- SECTION: TESTIMONIALS-->
@if($corporateTestimonials->count())
<section>
    <div class="testimonails-head what-client-say">
        <div class="testimonails">
            <div class="container">
                <div class="comm-tit-ani tit ani-tit text-center pb-2">
                    <p>What</p>
                    <h2>Our Institute <span>say</span><br></h2><span class="line"></span>
                </div>

                <div class="splide splide-corporate-testimonials" id="splide-corporate-testimonials" role="group">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($corporateTestimonials as $corporateTestimonial)
                                <li class="splide__slide">
                                    <div class="card h-100 d-flex flex-column justify-content-between">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center studentImage">
                                                <img class="img-thumbnail rounded"
                                                    src="{{ $corporateTestimonial->corporate->attachment ? asset('/storage/' . $corporateTestimonial->corporate->attachment) : asset('student/images/th_5.png') }}"
                                                    alt="{{ $corporateTestimonial->name }}"
                                                    style="height: 80px;width: 80px;">

                                                <p class="card-text pl-2">
                                                    <b>{{ $corporateTestimonial->name }}</b>{!! isset($corporateTestimonial->corporate, $corporateTestimonial->corporate->institute_name)
                                                        ? ',<br /> ' . $corporateTestimonial->corporate?->institute_name
                                                        : '' !!}{{ isset($corporateTestimonial->corporate, $corporateTestimonial->corporate->district, $corporateTestimonial->corporate->district->name) ? ', ' . $corporateTestimonial->corporate->district->name : '' }}
                                                </p>
                                            </div>
                                            <img class="img-fluid img-thumbnail w-100 mt-3"
                                                src="{{ $corporateTestimonial->image ? asset('/storage/' . $corporateTestimonial->image) : '/website/assets/images/placeholder.webp' }}"
                                                alt="{{ $corporateTestimonial->corporate?->institute_name ? $corporateTestimonial->corporate?->institute_name : $corporateTestimonial->name }}">
                                            <p class="card-text mt-3">{!! $corporateTestimonial->message !!}</p>
                                        </div>

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- SECTION: BRANDS -->

<div class="our-values strategy-consult">
    <div class="container">
        <div class="comm-tit-ani tit ani-tit text-center pb-2">
            <h2>{!! wrapHalfTitleInSpan($bannerSectionSixHeader?->section_title) !!}<br></h2><span class="line"></span>
            <h6 class="mb-5">{{$bannerSectionSixHeader?->section_remarks}}</h6>
        </div>
        <div class="row">
            @foreach($bannerSectionSixs as $bannerSectionSix)
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="our-values-widget d-flex">
                    <div class="our-values-icon mr-3">
                        <img src="{{ asset('home/aboutus/'.$bannerSectionSix->image) }}" alt="icon">
                    </div>
                    <div class="our-values-widget-content" style="max-height: 102px;overflow: hidden;">
                        <h6>{{$bannerSectionSix->title}}</h6>
                        {!!$bannerSectionSix->description!!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- <div class="contact-wave-effect ">
        <div class="ocean">
            <div class="wave"></div>
        </div>
    </div> --}}
@endsection
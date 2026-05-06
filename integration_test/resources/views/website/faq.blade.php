<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Home Page')


@section('content')
<style>
    .faq-accordion-cont .title h6 {
    font-size: 18px;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #000;
    padding-bottom: 30px;
}
.faq-accordion-cont .accordion {
    border: 0;
}
.faq-accordion-cont .accordion .card {
    border: 0;
    border-radius: 0;
    border-bottom: 1px solid #cecece !important;
}
.faq-accordion-cont .accordion .card:last-child {
    border-bottom: 0 !important;
}
.faq-accordion-cont .accordion .card .card-header {
    padding: 0;
    border-bottom: 0;
}
.faq-accordion-cont .accordion .card .card-header a {
    overflow: hidden;
    display: block;
    padding: 17px 25px;
    background-color: #07294d;
}
.faq-accordion-cont .accordion .card .card-header a.collapsed {
    background-color: #fff;
    border: none;
}
.faq-accordion-cont .accordion .card .card-header a::before, .faq-accordion-cont .accordion .card .card-header a.collapsed::before {
    content: "\f107";
    font-family: FontAwesome;
    font-size: 22px;
    color: #fff;
    position: absolute;
    top: 15px;
    right: 25px;
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg);
    -webkit-transition: all 0.2s linear;
    transition: all 0.2s linear;
}
.faq-accordion-cont .accordion .card .card-header a.collapsed:before {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
    color: #8a8a8a;
}
.faq-accordion-cont .accordion .card .card-header a ul li {
    display: inline-block;
}
.faq-accordion-cont .accordion .card .card-header a ul li:last-child {
    float: right;
}
.faq-accordion-cont .accordion .card .card-header a ul li>i {
    color: #ffc600;
    font-size: 16px;
    margin-right: 5px;
}
.faq-accordion-cont .accordion .card .card-header a ul li>.lecture {
    font-size: 15px;
    color: #8a8a8a;
}
.faq-accordion-cont .accordion .card .card-header a ul li>.head {
    font-size: 16px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    margin-left: 15px;
    color: #fff;
}
.faq-accordion-cont .accordion .card .card-header a.collapsed ul li>.head {
    color: #07294d;
}
.faq-accordion-cont .accordion .card .card-header a ul li>.time {
    font-size: 15px;
    color: #8a8a8a;
    text-align: right;
    padding-right: 30px;
}
.faq-accordion-cont .accordion .card .card-header a ul li>.time i {
    margin-right: 5px;
}
.faq-accordion-cont .accordion .card .card-body {
    padding: 30px;
}
/*=====================================================
    33. PRIVACY POLICY
======================================================*/

.privacy-desc p {
    margin-bottom: 25px;
}
.privacy-list ul {
    list-style: disc;
    padding-left: 20px;
}
.privacy-list ul li {
    padding: 6px 0;
}
.privacy-list ul li a {
    color: #07294d;
    font-weight: 700;
    transition: .3s;
}
.privacy-list ul li a:hover {
    color: #ffc600;
    transition: .3s;
}
</style>
<section>
        <div class="common-banner contact-us-banner">
            <div class="container">
                <div class="row">
                    <h2>Faq List</h2>
                    <h4><a href="{{ route('home.front') }}">Home > </a> <span>Faq</span></h4>
                    <i class="fly-icon"></i>
                    <div class="comm-ban-im">
                        <img src="{{ asset('website/assets/images/bg-icons/contact-banner.png') }}" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </section>
 <!--====== PROVACY PART START ======-->
    <section class="pt-105 pb-120 gray-bg faq comm-p-t-b" style="background:#edf0f2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-30">Frequently asked questions</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="faq-accordion" role="tabpanel" aria-labelledby="faq-accordion-tab">
                        <div class="faq-accordion-cont">
                            <div class="accordion" id="accordionExample">
                                @foreach ($faq as $index => $faqs)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $index }}">
                                        <a href="#" class="collapsed" data-toggle="collapse" data-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}" style="padding-bottom:1px">
                                            <ul>
                                                <li><i class="fa fa-question-circle" aria-hidden="true"></i></li>
                                                <li><span class="head">{{ $faqs->title }}</span></li>
                                                <li></li>
                                            </ul>
                                        </a>
                                    </div>

                                    <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>{!! strip_tags($faqs->details) !!}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div> <!-- faq-accordion-cont -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!--====== FAQ PART ENDS ======-->

{{-- <div class="contact-wave-effect ">
        <div class="ocean">
            <div class="wave"></div>
        </div>
    </div> --}}
<script>
   $(document).ready(function() {
    $('#accordionExample .card-header a').click(function() {
        var $this = $(this);
        var $target = $($this.attr('data-target'));

        // If the clicked item is not collapsed
        if (!$this.hasClass('collapsed')) {
            // Do nothing if the clicked item is already open
            return;
        }

        // Collapse all items
        $('#accordionExample .collapse').collapse('hide');
        $('#accordionExample .card-header a').removeClass('collapsed');

        // Show the clicked item
        $this.addClass('collapsed');
        $target.collapse('show');
    });
});


</script>
@endsection
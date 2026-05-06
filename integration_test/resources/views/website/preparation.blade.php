<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Home Page')


@section('content')
    <section class="main-content inner container" id="main-container">
        <a class="mobile-sidebar-btn d-lg-none btn-left" href="javascript:void(0)"><i class="ti-menu-alt"></i></a>
        <div class="mobile-sidebar-panel-overlay"></div>
        <div class="row">
            <div class="sidebar-wrapper sidebar-course col-lg-3 col-12">
                <aside class="sidebar sidebar-left" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
                    <div class="close-sidebar-btn d-lg-none"> <i class="ti-close"></i> <span>Close</span></div>
                    {{-- <aside class="widget widget_apus_course_filter_keywords">
                    <form class="search-courses" method="get" action="https://demoapus1.com/skillup/lp-courses/">
                        <input type="hidden" name="post_type" value="lp_course">
                        <input type="hidden" name="taxonomy" value="">
                        <input type="hidden" name="term_id" value="">
                        <input type="hidden" name="term" value="">
                        <input type="text" class="form-control" placeholder="Search courses..." name="c_search" value="">
                        <button class="btn" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        <input type="hidden" name="filter-category" value="42">
                    </form>
                </aside> --}}
                    <style>
                        .course-list-check label:before {
                            display: none;
                        }

                        .course-list-check label {
                            padding-left: 0;
                        }
                    </style>
                    @foreach ($featuredCourses->groupBy('scholarship_category') as $courses)
                        <aside class="widget widget_apus_course_filter_category">
                            <?php $scholarshipName = $courses->first()?->scholarshipCategory?->name; ?>
                            <h6 class="widget-title"><span>{{ $scholarshipName }}</span></h6>

                            <div class="filter-categories-widget">
                                <form action="https://demoapus1.com/skillup/lp-courses/" method="get">
                                    <ul class="course-category-list course-list-check">
                                        @foreach ($courses as $course)
                                            <li>
                                                <label for="filter-category-42">
                                                    <a href="{{ route('home.career', encodeId($course->id)) }}">
                                                        {{ $course->title }}
                                                    </a>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </form>
                            </div>
                        </aside>
                    @endforeach

                </aside>
            </div>

            <div class="col-sm-12 col-lg-9 col-12 pull-right" id="main-content">
                <main class="site-main layout-courses display-mode-grid" id="main" role="main">
                    <div class="course-top-wrapper d-md-flex align-items-center">
                        <div class="course-found"><span>{{ count($featuredCourses) }}</span> courses found</div>
                        {{-- <div class="lp-courses-filter d-flex align-items-center ml-auto">

                        <div class="orderby d-flex align-items-center">
                            <label class="mb-0 me-3">Short By:</label>
                            <form class="courses-ordering" method="get">
                                <select name="orderby" class="orderby">
                                    <option value="" selected="selected">Default</option>
                                    <option value="newest">Newest</option>
                                    <option value="oldest">Oldest</option>
                                </select>
                                <input type="hidden" name="paged" value="1">
                                <input type="hidden" name="filter-category" value="42">
                            </form>
                        </div>
                        <div class="display-mode d-flex align-items-center"><a href="#" class=" change-view active">
                                <i class="fa fa-th" aria-hidden="true"></i>
                            </a><a href="#" class=" change-view "><i class="fa fa-list-ul" aria-hidden="true"></i></a></div>
                    </div> --}}
                    </div>
                    <div class="learn-press-courses row">
                        @foreach ($featuredCourses as $featuredCourse)
                            <div class="col-lg-6 col-md-6 col-12">
                                <div
                                    class="course-grid post-1092 lp_course type-lp_course status-publish has-post-thumbnail hentry course_category-it-software course_category-web-designing course">
                                    <div class="course-layout-item">
                                        <div class="course-entry">

                                            <div class="course-cover">
                                                <div class="course-cover-thumb">
                                                    <figure class="entry-thumb"><a class="post-thumbnail"
                                                            href="{{ route('home.career', encodeId($featuredCourse->id)) }}"
                                                            aria-hidden="true">
                                                            <div class="image-wrapper">
                                                                <center><img class="img-fluid"
                                                                        src="{{ asset('home/course/' . $featuredCourse->featured_image) }}"
                                                                        alt="" style="width:250px;height:250px;">
                                                                </center>
                                                            </div>
                                                        </a></figure>
                                                </div>
                                            </div>

                                            <div class="course-layout-content">
                                                <h3 class="course-title"><a
                                                        href="{{ route('home.career', encodeId($featuredCourse->id)) }}">Education
                                                        {{ $featuredCourse->course_full_name }} (
                                                        {{ $featuredCourse->vacancies }} Vacancies)</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </main>
            </div>

        </div>
    </section>
@endsection

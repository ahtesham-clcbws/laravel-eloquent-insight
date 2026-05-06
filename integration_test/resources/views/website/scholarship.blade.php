<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Home Page')


@section('content')
    <style>
        .scolarship-programe {
            padding: 80px 0;
            background-color: #fff;
        }

        .scolarship-interactive-container {
            background-color: #f7f9fc;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            position: relative;
        }

        .scolarship-leftpanel-widget {
            background: transparent;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .scolarship-leftpanel-widget:hover {
            background: rgba(242, 107, 60, 0.03);
        }

        .scolarship-leftpanel-widget.active {
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-color: #eee;
        }

        .scolarship-leftpanel-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            margin-right: 10px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scolarship-leftpanel-img img {
            max-width: 100%;
            height: auto;
        }

        .scolarship-leftpanel-content {
            text-align: left !important;
            flex-grow: 1;
        }

        .scolarship-leftpanel-content h4 {
            font-size: 16px;
            font-weight: 700;
            color: #0d172a;
            margin: 0;
            line-height: 1.4;
        }

        .scolarship-leftpanel-content p {
            font-size: 13px;
            color: #475569;
            margin: 2px 0 0 0;
            line-height: 1.5;
        }

        .scolarship-leftpanel-content .subtitle {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #f26b3c;
            margin-top: 4px;
        }

        .scolarship-rightpanel-container {
            border-radius: 15px;
            overflow: hidden;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .scolarship-rightpanel-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .career-overview {
            padding: 60px 0;
            background: #fff;
        }

        .career-overview-header h2 {
            border-left: 4px solid #f26b3c;
            padding-left: 18px;
            font-weight: 800;
            font-size: 28px;
            color: #0f172a;
        }

        .e-prospectus-link {
            background: #fdf2ee;
            color: #f26b3c !important;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .e-prospectus-link:hover {
            background: #f26b3c;
            color: #fff !important;
        }
    </style>
    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container text-center py-5 pb-4">
            <h2 style="font-size:32px" class="text-white">CAREER without BARRIER</h2>
            <p class="text-white" style="font-size:20px">14th Certified Annual Scholarship Program - 2026</p>
        </div>
    </div>

    <div class="scolarship-programe">
        <div class="container">
            <div class="scolarship-interactive-container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            @foreach ($scholarShips as $key => $scholarShip)
                                <div class="scolarship-leftpanel-widget {{ $key == 0 ? 'active' : '' }}" data-target="content{{ $key + 1 }}">
                                    <div class="scolarship-leftpanel-img">
                                        <img class="img-fluid" src="{{ asset('home/aboutus/' . $scholarShip->icon) }}"
                                            alt="icon">
                                    </div>
                                    <div class="scolarship-leftpanel-content">
                                        @if($scholarShip->subtitle)
                                            <span class="subtitle">{{ $scholarShip->subtitle }}</span>
                                        @endif
                                        <h4>{{ $scholarShip->educationType?->name }}</h4>
                                        <p>{{ $scholarShip->remark }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12">
                        <div class="scolarship-rightpanel-container">
                            @foreach ($scholarShips as $key => $scholarShip)
                                <div class="scolarship-rightpanel-content content{{ $key + 1 }}"
                                    style="<?= $key == 0 ? '' : 'display:none' ?>">
                                    <img class="img-fluid" src="{{ asset('home/aboutus/' . $scholarShip->picture) }}"
                                        alt="{{ $scholarShip->educationType?->name }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($scholarShips as $key => $scholarShip)
        <?php
        $imageExtensions = ['jpeg', 'jpg', 'png', 'jpeg', 'gif'];
        $scholarOverview = $scholarShip->overview;
        $prospectusUrl = null;
        $guidelineUrl = null;
        $prospectusExtension = null;
        $guidelineExtension = null;

        if ($scholarOverview) {
            $pPath = 'home/eprospectus/' . $scholarOverview->prospectus;
            $gPath = 'home/eprospectus/' . $scholarOverview->guideline;

            $prospectusUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($pPath) 
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($pPath) 
                : asset($pPath);

            $guidelineUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($gPath) 
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($gPath) 
                : asset($gPath);

            $prospectusExtension = pathinfo($scholarOverview->prospectus, PATHINFO_EXTENSION);
            $guidelineExtension = pathinfo($scholarOverview->guideline, PATHINFO_EXTENSION);
        }
        ?>

        <div class="career-overview career-overview-content content{{ $key + 1 }}"
            style="<?= $key == 0 ? '' : 'display:none' ?>">
            <div class="container">
                <div class="career-overview-header d-flex align-items-center mb-5 flex-wrap">
                    <h2 class="mb-0 mr-auto">{{ $scholarShip->educationType?->name }}</h2>
                    <div class="e-prospectus d-flex align-items-center">
                        @if ($scholarOverview?->prospectus)
                            @if (in_array($prospectusExtension, $imageExtensions))
                                <a class="d-flex align-items-center e-prospectus-link mr-3" href="#">
                                    <img class="mr-2" src="{{ $prospectusUrl }}" alt="icon">
                                    <span>E-prospectus</span>
                                </a>
                            @else
                                <a class="d-flex align-items-center e-prospectus-link mr-3" href="{{ $prospectusUrl }}"
                                    target="_blank">
                                    <span>E-prospectus (PDF)</span>
                                </a>
                            @endif
                        @endif

                        @if ($scholarOverview?->guideline)
                            @if (in_array($guidelineExtension, $imageExtensions))
                                <a class="d-flex align-items-center e-prospectus-link" href="#">
                                    <img class="mr-2" src="{{ $guidelineUrl }}" alt="icon">
                                    <span>Guidelines</span>
                                </a>
                            @else
                                <a class="d-flex align-items-center e-prospectus-link" href="{{ $guidelineUrl }}"
                                    target="_blank">
                                    <span>Guidelines (PDF)</span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
                @if ($scholarOverview?->overview)
                    <div class="career-overview-content content{{ $key + 1 }}">
                        {!! str_replace('img', 'img style="max-width: 100%;"', $scholarOverview->overview) !!}
                    </div>
                @endif
            </div>
        </div>
    @endforeach

@endsection

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('.scolarship-leftpanel-widget').on('click', function() {
                var targetId = $(this).data('target');
                
                if ($(this).hasClass('active')) return;

                $('.scolarship-leftpanel-widget').removeClass('active');
                $(this).addClass('active');

                // Smooth transitions
                $('.scolarship-rightpanel-content').fadeOut(200, function() {
                    $('.scolarship-rightpanel-content.' + targetId).fadeIn(300);
                });
                
                $('.career-overview-content').fadeOut(200, function() {
                    $('.career-overview-content.' + targetId).fadeIn(300);
                });
                
                $('.career-overview').fadeOut(200, function() {
                    $('.career-overview.' + targetId).fadeIn(300);
                });
            });
        });
    </script>
@endpush

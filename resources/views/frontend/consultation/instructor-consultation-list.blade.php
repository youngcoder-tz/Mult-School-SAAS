@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('consultation');
    @endphp

    <meta name="description" content="{{ __($metaData['meta_description']) }}">
    <meta name="keywords" content="{{ __($metaData['meta_keyword']) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($metaData['meta_title']) }}">
    <meta property="og:description" content="{{ __($metaData['meta_description']) }}">
    <meta property="og:image" content="{{ __($metaData['og_image']) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($metaData['meta_title']) }}">
    <meta name="twitter:description" content="{{ __($metaData['meta_description']) }}">
    <meta name="twitter:image" content="{{ __($metaData['og_image']) }}">
@endsection
@section('content')
    <div class="bg-page">
        <!-- Consultation Page Header Start -->
        <header class="page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading text-white pb-15">{{ __('Instructor Consultation') }}</h3>
                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Instructor Consultation') }}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Consultation Page Header End -->

        <!-- instructor consultation Page Area Start -->
        <section class="consultation-page-area section-t-space">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Courses Filter Bar Start-->
                        <div class="courses-filter-bar">
                            <div class="row">
                                <div class="filter-bar-left col-lg-8">
                                    <div class="d-inline-flex align-items-center">
                                        <div id="filter" class="actions-filter cursor sidebar-filter-btn color-gray d-flex align-items-center me-2">
                                            <img src="{{ asset('frontend/assets/img/courses-img/filter-icon.png') }}" alt="short" class="me-2">
                                            {{ __('Filter') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-bar-right col-lg-4 text-end">
                                    <div class="filter-box align-items-center justify-content-end">
                                        <div class="filter-box-short-icon color-gray font-15"><p>{{ __('Sort By') }}:</p></div>
                                        <select class="form-select form-select-sm filterSortBy">
                                            <option value="1" selected>{{ __('Default') }}</option>
                                            <option value="2">{{ __('Newest Instructor') }}</option>
                                            <option value="3">{{ __('Oldest Instructor') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Courses Filter Bar End-->
                    </div>
                </div>
                <div class="row shop-content">
                    @include('frontend.consultation.render-sidebar-filter-part')
                    <!-- Show all course area start-->
                    <div class="col-md-8 col-lg-9 col-xl-9 show-all-course-area-wrap">
                        <div class="show-all-course-area">
                            <!-- all courses grid Start-->
                            <div id="loading" class="no-course-found text-center d-none">
                                <div id="inner-status"><img src="{{ asset('frontend/assets/img/loader.svg') }}" alt="img" /></div>
                            </div>
                            <div class="row courses-grids appendConsultationList">
                                @include('frontend.consultation.render-consultation-instructor-list')
                            </div>
                            <!-- all courses grid End-->

                        </div>
                    </div>
                    <!-- Show all course area End-->
                </div>
            </div>
        </section>
        <!-- instructor consultation Page Area End -->

        @include('frontend.home.partial.consultation-booking-schedule-modal')

        <!-- some important hidden id for filter.js -->
        <input type="hidden" class="route" value="{{ route('getFilterConsultationInstructor') }}">
        <input type="hidden" class="fetch-data-route" value="{{ route('consultation-instructor.fetch-data') }}">
    </div>
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/custom/booking.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/consultation-filter.js') }}"></script>
@endpush

@extends('frontend.layouts.app')

@section('meta')
    <meta name="description" content="{{ __($category->meta_description) }}">
    <meta name="keywords" content="{{ __($category->meta_keywords) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($category->meta_title) }}">
    <meta property="og:description" content="{{ __($category->meta_description) }}">
    <meta property="og:image" content="{{ getImageFile($category->og_image) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($category->meta_title) }}">
    <meta name="twitter:description" content="{{ __($category->meta_description) }}">
    <meta name="twitter:image" content="{{ getImageFile($category->og_image) }}">
@endsection
@section('content')

    <div class="bg-page">

        <!-- Course Single Page Header Start -->
        <header class="page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading text-white pb-15">{{ __($category->name) }}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                        <li class="breadcrumb-item font-14"><a href="#">{{ __('Category') }}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{ __($category->name) }}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Course Single Page Header End -->

        <!-- Courses Page Area Start -->
        <section class="courses-page-area section-t-space">
            <div class="container">
                <div class="row">
                    <div class="col-12">

                        <!-- Courses Filter Bar Start-->
                        <div class="courses-filter-bar">
                            <div class="row">
                                <div class="filter-bar-left col-lg-8">
                                    <div class="filter-bar-left-top color-heading mb-3">
                                        {{ __('People also search') }}:
                                        <ul class="d-inline-flex align-items-center flex-wrap">
                                            @foreach(@$random_four_categories as $random_four_category)
                                                <li class="search-tag-list color-hover font-13 font-medium"><a href="{{ route('category-courses', $random_four_category->slug) }}">{{ __($random_four_category->name) }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="d-inline-flex align-items-center">
                                        <div id="filter" class="actions-filter cursor sidebar-filter-btn color-gray d-flex align-items-center me-2">
                                            <img src="{{ asset('frontend/assets/img/courses-img/filter-icon.png') }}" alt="short" class="me-2">
                                            {{ __('Filter') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-bar-right col-lg-4 text-end">
                                    <div class="filter-bar-left-top color-gray mb-3 d-none">
                                        <span class="color-heading">{{ __('Search result for') }}:</span> <span class="color-hover">“{{ $category->name }}”</span>
                                        {{ $total_courses }} {{ __('result found') }}
                                    </div>
                                    <div class="filter-box align-items-center justify-content-end">
                                        <div class="filter-box-short-icon color-gray font-15"><p>{{ __('Sort By') }}:</p></div>
                                        <select class="form-select form-select-sm filterSortBy">
                                            <option value="1" selected>{{ __('Default') }}</option>
                                            <option value="2">{{ __('Newest course') }}</option>
                                            <option value="3">{{ __('Oldest course') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Courses Filter Bar End-->

                    </div>
                </div>
                <div class="row shop-content">
                    <!-- Courses Sidebar start-->
                    @include('frontend.course.render-sidebar-filter-part')
                    <!-- Courses Sidebar End-->
                    <!-- Show all course area start-->
                    <div class="col-md-8 col-lg-9 col-xl-9 show-all-course-area-wrap">
                        <div class="show-all-course-area">
                            <!-- all courses grid Start-->
                            <div id="loading" class="no-course-found text-center d-none">
                                <div id="inner-status"><img src="{{ asset('frontend/assets/img/loader.svg') }}" alt="img" /></div>
                            </div>
                        </div>
                        @include('frontend.course.render-course-list')
                        <!-- all courses grid End-->
                    </div>
                </div>
                <!-- Show all course area End-->
            </div>
        </section>
        <!-- Courses Page Area End -->
    </div>

    <!-- some important hidden id for filter.js -->
    <input type="hidden" class="category_id" value="{{ $category->id }}">
    <input type="hidden" class="route" value="{{ route('getFilterCourse') }}">
    <input type="hidden" class="fetch-data-route" value="{{ route('course.fetch-data') }}">
@endsection
@push('script')
    <script>
        var paginateRoute = "{{ route('courses') }}"
    </script>
    <script src="{{ asset('frontend/assets/js/course/filter.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/addToCart.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/addToWishlist.js') }}"></script>
@endpush

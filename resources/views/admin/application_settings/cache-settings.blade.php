@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{ __('Application Settings') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    @include('admin.application_settings.sidebar')
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="email-inbox__area bg-style">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <div class="custom-form-group mb-3 row">
                            <label for="" class="col-lg-3 text-lg-right text-black">{{ __('Clear View Cache') }}</label>
                            <div class="col-lg-9">
                                <a href="{{ route('settings.cache-update', 1) }}" class="btn btn-primary">{{ __('Click Here') }}</a>
                            </div>
                        </div>
                        <div class="custom-form-group mb-3 row">
                            <label for="" class="col-lg-3 text-lg-right text-black">{{ __('Clear Route Cache') }} </label>
                            <div class="col-lg-9">
                                <a href="{{ route('settings.cache-update', 2) }}" class="btn btn-primary">{{ __('Click Here') }}</a>
                            </div>
                        </div>
                        <div class="custom-form-group mb-3 row">
                            <label for="" class="col-lg-3 text-lg-right text-black">{{ __('Clear Config Cache') }} </label>
                            <div class="col-lg-9">
                                <a href="{{ route('settings.cache-update', 3) }}" class="btn btn-primary">{{ __('Click Here') }}</a>
                            </div>
                        </div>
                        <div class="custom-form-group mb-3 row">
                            <label for="" class="col-lg-3 text-lg-right text-black">{{ __('Application Clear Cache') }} </label>
                            <div class="col-lg-9">
                                <a href="{{ route('settings.cache-update', 4) }}" class="btn btn-primary">{{ __('Click Here') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection

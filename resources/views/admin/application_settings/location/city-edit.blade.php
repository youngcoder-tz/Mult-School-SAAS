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
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Edit City') }}</h2>
                            <a href="{{ route('settings.location.city.index') }}" class="btn btn-success btn-sm" >
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                        <form action="{{route('settings.location.city.update', [$city->id])}}" method="post" class="form-horizontal">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="name">{{ __('State Name') }}</label>
                                        <select name="state_id" required>
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('state_id'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('state_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" placeholder="{{ __('Type name') }}" value="{{ $city->name }}" required>
                                        @if ($errors->has('name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>


                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    @updateButton
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection

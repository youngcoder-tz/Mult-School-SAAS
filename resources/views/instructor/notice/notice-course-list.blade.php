
@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Notice Board')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Notice Board Course List') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-notice-board-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{__('Notice Board')}}</h6>
            </div>

            <div class="row">
                <div class="col-12">
                    @if(count($courses) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Course Name')}}</th>
                                <th scope="col">{{__('Total Notice')}}</th>
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>
                                    <div class="table-data-with-img d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="table-data-img-wrap"><img src="{{ getImageFile($course->image_path) }}" alt="img" class="img-fluid"></div>
                                        </div>
                                        <div class="flex-grow-1 table-data-course-name">{{ Str::limit($course->title, 50) }}</div>
                                    </div>
                                </td>
                                <td>{{ @$course->notices->count() }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <a href="{{ route('notice-board.create', $course->uuid) }}" class="theme-btn theme-button1 default-hover-btn">{{__('Add Notice')}}</a>
                                        <a href="{{ route('notice-board.index', $course->uuid) }}" class="theme-btn theme-button1 green-theme-btn default-hover-btn">{{ __('View List') }}</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                        <!-- Pagination Start -->
                        @if(@$courses->hasPages())
                            {{ @$courses->links('frontend.paginate.paginate') }}
                        @endif
                        <!-- Pagination End -->
                    @else
                        <!-- If there is no data Show Empty Design Start -->
                        <div class="empty-data">
                            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                            <h5 class="my-3">{{__('Empty Course Notice')}}</h5>
                        </div>
                        <!-- If there is no data Show Empty Design End -->
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

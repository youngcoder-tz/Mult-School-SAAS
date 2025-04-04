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
                                <h2>{{__('Instructors')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Approved Instructors')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{__('Approved Instructors')}}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Professional_title')}}</th>
                                    <th>{{__('Phone Number')}}</th>
                                    <th>{{__('Country')}}</th>
                                    <th>{{__('State')}}</th>
                                    <th>{{__('Auto Content Approval')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($instructors as $instructor)
                                    <tr class="removable-item">
                                        <td>
                                            <a href="{{route('instructor.view', [$instructor->uuid])}}"> <img src="{{getImageFile($instructor->user ? $instructor->user->image_path : '')}}" width="80"> </a>
                                        </td>
                                        <td>
                                            {{$instructor->name}}
                                        </td>
                                        <td>
                                            {{$instructor->professional_title}}
                                        </td>

                                        <td>
                                            {{$instructor->phone_number}}
                                        </td>
                                        <td>
                                            {{$instructor->country ? $instructor->country->country_name : '' }}
                                        </td>
                                        <td>
                                            {{$instructor->state ? $instructor->state->name : '' }}
                                        </td>
                                        <td>
                                            <span id="hidden_id" style="display: none">{{$instructor->id}}</span>
                                            <select name="auto_content_approval" class="status  change-auto-content label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($instructor->auto_content_approval == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($instructor->auto_content_approval == 0) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{route('instructor.view', [$instructor->uuid])}}" class="btn-action mr-30" title="View Details">
                                                    <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                                </a>
                                                <a href="{{route('instructor.edit', [$instructor->uuid])}}" class="btn-action mr-30" title="Edit Details">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                <a href="javascript:void(0);" data-url="{{route('instructor.delete', [$instructor->uuid])}}" title="Delete" class="btn-action deleteBtn">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$instructors->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
    <script src="{{ asset('admin/js/custom/instructor-delete.js') }}"></script>

    <script>
         $(".change-auto-content").change(function () {
            var id = $(this).closest('tr').find('#hidden_id').html();
            var status_value = $(this).val();
            Swal.fire({
                title: "{{ __('Are you sure to change?') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{__('Yes, Change it!')}}",
                cancelButtonText: "{{__('No, cancel!')}}",
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('admin.instructor.changeAutoContentStatus')}}",
                        data: {"auto_content_approval": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.success('', "{{ __('Auto content status has been updated') }}");
                            location.reload();
                        },
                        error: function () {
                            alert("Error!");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                    location.reload();
                }
            });
        });
    </script>
@endpush

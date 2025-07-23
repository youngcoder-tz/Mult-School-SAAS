@extends('frontend.layouts.app')

@section('content')
    <div class="bg-page">
        <!-- Page Header Start -->
        @include('frontend.student.settings.header')
        <!-- Page Header End -->

        <!-- Student Profile Page Area Start -->
        <section class="student-profile-page">
            <div class="container">
                <div class="student-profile-page-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="row bg-white">
                                <!-- Student Profile Left part -->
                                @include('frontend.student.settings.sidebar')

                                <!-- Student Profile Right part -->
                                <div class="col-lg-9 p-0">
                                    <div class="student-profile-right-part">
                                        <h6>{{__('Profile')}}</h6>
                                        <form id="profileForm" action="{{route('student.save-profile', [$student->uuid])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            
                                            <!-- Debugging Panel - Visible in Development -->
                                            @if(config('app.debug'))
                                            <div class="alert alert-warning mb-4" id="debugPanel">
                                                <h5 class="font-bold">Debug Information</h5>
                                                <div id="fieldStatus"></div>
                                                <pre id="formDataPreview" class="bg-light p-2 mt-2 small"></pre>
                                            </div>
                                            @endif

                                            <div class="profile-top mb-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="profile-image radius-50">
                                                        <img class="avater-image" id="target1" src="{{getImageFile($user->image_path)}}" alt="img">
                                                        <div class="custom-fileuplode">
                                                            <label for="fileuplode" class="file-uplode-btn bg-hover text-white radius-50">
                                                                <span class="iconify" data-icon="bx:bx-edit"></span>
                                                            </label>
                                                            <input type="file" id="fileuplode" name="image" accept="image/*" class="putImage1" onchange="previewFile(this)">
                                                        </div>
                                                    </div>
                                                    <div class="author-info">
                                                        <p class="font-medium font-15 color-heading">{{__('Select Your Picture')}}</p>
                                                        <p class="font-14">{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br> {{ __('Accepted Size') }}: 300 x 300 (1MB)</p>
                                                    </div>
                                                </div>
                                                @if ($errors->has('image'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('image') }}</span>
                                                @endif
                                            </div>

                                            <!-- Personal Information Section -->
                                            <div class="row">
                                                <div class="col-md-6 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('First Name')}} <span class="text-danger">*</span></label>
                                                    <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="form-control" placeholder="{{__('First Name')}}" required>
                                                    @if ($errors->has('first_name'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('first_name') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Last Name')}} <span class="text-danger">*</span></label>
                                                    <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="form-control" placeholder="{{__('Last Name')}}" required>
                                                    @if ($errors->has('last_name'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('last_name') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Email')}} <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" placeholder="{{ __('Type your email') }}" required>
                                                    @if ($errors->has('email'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Phone Number')}} <span class="text-danger">*</span></label>
                                                    <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" class="form-control" placeholder="{{ __('Type your phone number') }}" required>
                                                    @if ($errors->has('phone_number'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('phone_number') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Bio')}}</label>
                                                    <textarea class="form-control" name="about_me" rows="3" placeholder="{{ __('Type about yourself') }}">{{ old('about_me', $student->about_me) }}</textarea>
                                                    @if ($errors->has('about_me'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('about_me') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mb-30">
                                                <div class="col-md-12">
                                                    <label class="font-medium font-15 color-heading">{{__('Gender')}} <span class="text-danger">*</span></label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Male" {{ old('gender', $student->gender) == 'Male' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="inlineRadio1">{{__('Male')}}</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Female" {{ old('gender', $student->gender) == 'Female' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="inlineRadio2">{{__('Female')}}</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="Others" {{ old('gender', $student->gender) == 'Others' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="inlineRadio3">{{__('Others')}}</label>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('gender'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('gender') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Meta Information Section -->
                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Profile Page Meta Title')}}</label>
                                                    <input type="text" name="meta_title" value="{{ old('meta_title', $user->meta_title) }}" class="form-control" placeholder="{{ __('Meta Title') }}">
                                                    @if ($errors->has('meta_title'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_title') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Profile Page Meta Description')}}</label>
                                                    <textarea class="form-control" name="meta_description" rows="3" placeholder="{{ __('Type Meta Description') }}">{{ old('meta_description', $user->meta_description) }}</textarea>
                                                    @if ($errors->has('meta_description'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_description') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Profile Page Meta Keywords')}}</label>
                                                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $user->meta_keywords) }}" class="form-control" placeholder="{{ __('Type meta keywords (comma separated)') }}">
                                                    @if ($errors->has('meta_keywords'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_keywords') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- OG Image Upload -->
                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <div class="input__group mb-25">
                                                        <label>{{ __('Profile Page OG Image') }}</label>
                                                        <div class="upload-img-box">
                                                            @if($user->og_image)
                                                                <img src="{{ getImageFile($user->og_image) }}" id="ogImagePreview">
                                                            @else
                                                                <img src="" id="ogImagePreview" style="display:none;">
                                                            @endif
                                                            <input type="file" name="og_image" id="og_image" accept="image/*" onchange="previewOgImage(this)">
                                                            <div class="upload-img-box-icon">
                                                                <i class="fa fa-camera"></i>
                                                                <p class="m-0">{{__('OG Image')}}</p>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('og_image'))
                                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('og_image') }}</span>
                                                        @endif
                                                        <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG, JPG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 1200 x 627</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold" id="submitBtn">
                                                    {{__('Save Profile Now')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Student Profile Page Area End -->
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('admin/css/custom/image-preview.css') }}">
    <style>
        /* Debug Panel Styles */
        #debugPanel {
            display: none;
            border-left: 4px solid #f39c12;
        }
        .field-status {
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 3px;
        }
        .field-valid {
            background-color: #d4edda;
            color: #155724;
        }
        .field-invalid {
            background-color: #f8d7da;
            color: #721c24;
        }
        #formDataPreview {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('admin/js/custom/image-preview.js') }}"></script>
    <script>
        // Enhanced Form Debugging
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profileForm');
            const debugPanel = document.getElementById('debugPanel');
            const fieldStatus = document.getElementById('fieldStatus');
            const formDataPreview = document.getElementById('formDataPreview');
            const submitBtn = document.getElementById('submitBtn');

            // Show debug panel in development
            @if(config('app.debug'))
                debugPanel.style.display = 'block';
            @endif

            // Analyze form on load
            analyzeForm();

            // Analyze form before submission
            form.addEventListener('submit', function(e) {
                if (!analyzeForm()) {
                    e.preventDefault();
                    alert('Please fix the validation errors before submitting.');
                }
            });

            // Real-time form analysis
            form.addEventListener('change', analyzeForm);
            form.addEventListener('keyup', analyzeForm);

            function analyzeForm() {
                const formData = new FormData(form);
                const requiredFields = [
                    'first_name', 'last_name', 'email', 
                    'phone_number', 'gender'
                ];
                
                let allValid = true;
                fieldStatus.innerHTML = '';
                
                // Check required fields
                requiredFields.forEach(field => {
                    const value = formData.get(field);
                    const isValid = value && value.toString().trim() !== '';
                    allValid = allValid && isValid;
                    
                    const statusDiv = document.createElement('div');
                    statusDiv.className = `field-status ${isValid ? 'field-valid' : 'field-invalid'}`;
                    statusDiv.innerHTML = `${field}: ${isValid ? '✅ Valid' : '❌ Missing'}`;
                    fieldStatus.appendChild(statusDiv);
                });
                
                // Check file fields
                const fileFields = ['image', 'og_image'];
                fileFields.forEach(field => {
                    const fileInput = form.querySelector(`[name="${field}"]`);
                    const statusDiv = document.createElement('div');
                    statusDiv.className = 'field-status';
                    
                    if (fileInput.files.length > 0) {
                        statusDiv.className += ' field-valid';
                        statusDiv.innerHTML = `${field}: ✅ Selected (${fileInput.files[0].name})`;
                    } else {
                        statusDiv.className += ' field-valid';
                        statusDiv.innerHTML = `${field}: ⚠️ Optional`;
                    }
                    
                    fieldStatus.appendChild(statusDiv);
                });
                
                // Preview form data
                const formDataObj = {};
                formData.forEach((value, key) => {
                    formDataObj[key] = value;
                });
                formDataPreview.textContent = JSON.stringify(formDataObj, null, 2);
                
                return allValid;
            }

            // OG Image Preview
            window.previewOgImage = function(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    const preview = document.getElementById('ogImagePreview');
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                    analyzeForm();
                }
            };

            // Disable submit button after click to prevent double submission
            submitBtn.addEventListener('click', function() {
                setTimeout(() => {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                }, 100);
            });
        });
    </script>
@endpush
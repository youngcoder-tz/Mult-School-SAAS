@extends('layouts.auth')

@section('content')
    <!-- Sing Up Area Start -->
    <section class="sign-up-page p-0">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-5">
                    <div class="sign-up-left-content">
                        <div class="sign-up-top-logo">
                            <a href="{{ route('main.index') }}"><img src="{{getImageFile(get_option('app_logo'))}}" alt="logo"></a>
                        </div>
                        <p>{{ __(get_option('sign_up_left_text')) }}</p>
                        @if(get_option('sign_up_left_image'))
                            <div class="sign-up-bottom-img">
                                <img src="{{getImageFile(get_option('sign_up_left_image'))}}" alt="hero" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="sign-up-right-content bg-white">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <h5 class="mb-1">{{__('Sign In')}}</h5>
                            <p class="font-14 mb-30">{{__('New User')}} ? <a href="{{route('sign-up')}}" class="color-hover text-decoration-underline font-medium">{{__('Create an Account')}}</a></p>

                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Email or Phone')}}</label>
                                    <input type="text" name="email" value="{{old('email')}}" class="form-control email" placeholder="{{ __('Type your email or phone number') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Password')}}</label>
                                    <div class="form-group mb-0 position-relative">
                                        <input class="form-control password" name="password" value="{{old('password')}}" placeholder="*********" type="password">
                                        <span class="toggle cursor fas fa-eye pass-icon"></span>
                                    </div>

                                    @if ($errors->has('password'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-30">
                                <div class="col-md-12"><a href="{{ route('forget-password') }}" class="color-hover text-decoration-underline font-medium">{{__('Forgot Password')}}?</a></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{__('Sign In')}}</button>
                                </div>
                            </div>

                            @if(env('GOOGLE_LOGIN_STATUS') == 1 || env('FACEBOOK_LOGIN_STATUS') == 1 || env('TWITTER_LOGIN_STATUS') == 1)
                            <div class="social-login-separator mb-4">
                                <span class="separator-text">or continue with</span>
                            </div>
                            @endif

                            <div class="social-media-login-wrap">
                                <div class="row">
                                    @if(env('GOOGLE_LOGIN_STATUS') == 1)
                                        <div class="col-md-4 mb-2">
                                            <a href="{{ route('login.google') }}" class="social-login-btn google-login-btn w-100">
                                                <div class="social-login-content d-flex align-items-center justify-content-center">
                                                    <i class="fab fa-google social-icon"></i>
                                                    <span class="social-text">Google</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if(env('FACEBOOK_LOGIN_STATUS') == 1)
                                        <div class="col-md-4 mb-2">
                                            <a href="{{ route('login.facebook') }}" class="social-login-btn facebook-login-btn w-100">
                                                <div class="social-login-content d-flex align-items-center justify-content-center">
                                                    <i class="fab fa-facebook-f social-icon"></i>
                                                    <span class="social-text">Facebook</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if(env('TWITTER_LOGIN_STATUS') == 1)
                                        <div class="col-md-4 mb-2">
                                            <a href="{{ route('login.twitter') }}" class="social-login-btn twitter-login-btn w-100">
                                                <div class="social-login-content d-flex align-items-center justify-content-center">
                                                    <i class="fab fa-twitter social-icon"></i>
                                                    <span class="social-text">Twitter</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(env('LOGIN_HELP') == 'active')
                                <div class="table-responsive login-info-table mt-3">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td id="adminCredentialShow" class="login-info"><b>Admin:</b> admin@gmail.com | 123456</td>
                                            <td id="instructorCredentialShow" class="login-info"><b>Instructor:</b> instructor@gmail.com | 123456</td>
                                        </tr>
                                        <tr>
                                            <td id="studentCredentialShow" class="login-info"><b>Student:</b> student@gmail.com | 123456</td>
                                            <td id="affiliatorCredentialShow" class="login-info"><b>Affiliator:</b> affiliator@gmail.com | 123456</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" id="organizationCredentialShow" class="login-info"><b>Organization:</b> organization@gmail.com | 123456</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sing Up Area End -->
@endsection

@push('style')
<style>
    /* Social Login Styles */
    .social-login-separator {
        position: relative;
        text-align: center;
        margin: 20px 0;
    }
    
    .social-login-separator::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #e0e0e0;
        z-index: 1;
    }
    
    .separator-text {
        position: relative;
        display: inline-block;
        padding: 0 15px;
        background-color: white;
        color: #6c757d;
        z-index: 2;
        font-size: 14px;
    }
    
    .social-login-btn {
        display: block;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        height: 100%;
    }
    
    .social-login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .google-login-btn {
        background-color: white;
        color: #757575;
    }
    
    .google-login-btn:hover {
        background-color: #f8f9fa;
        border-color: #d1d1d1;
    }
    
    .facebook-login-btn {
        background-color: #1877f2;
        color: white;
    }
    
    .facebook-login-btn:hover {
        background-color: #166fe5;
        border-color: #166fe5;
    }
    
    .twitter-login-btn {
        background-color: #1da1f2;
        color: white;
    }
    
    .twitter-login-btn:hover {
        background-color: #1a91da;
        border-color: #1a91da;
    }
    
    .social-login-content {
        gap: 10px;
    }
    
    .social-icon {
        font-size: 18px;
    }
    
    .social-text {
        font-weight: 500;
        font-size: 14px;
    }
    
    /* Form Enhancements */
    .form-control {
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: border-color 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }
    
    .pass-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        cursor: pointer;
    }
    
    .theme-btn {
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .theme-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .login-info-table {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .login-info {
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .login-info:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('script')
    <script>
        "use strict"
        $('#adminCredentialShow').on('click', function (){
            $('.email').val('admin@gmail.com');
            $('.password').val('123456');
        });

        $('#instructorCredentialShow').on('click', function (){
            $('.email').val('instructor@gmail.com');
            $('.password').val('123456');
        });

        $('#studentCredentialShow').on('click', function (){
            $('.email').val('student@gmail.com');
            $('.password').val('123456');
        });

        $('#affiliatorCredentialShow').on('click', function (){
            $('.email').val('affililator@gmail.com');
            $('.password').val('123456');
        });

        $('#organizationCredentialShow').on('click', function (){
            $('.email').val('organization@gmail.com');
            $('.password').val('123456');
        });
    </script>
@endpush
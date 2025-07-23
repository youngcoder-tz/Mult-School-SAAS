@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('support_faq');
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
        <!-- Modern Page Header Start -->
        <header class="page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <div class="support-header-icon mb-3">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <h3 class="page-banner-heading text-white pb-15">{{__('Support Center')}}</h3>
                                <p class="text-white mb-4">{{__('We are here to help you 24/7')}}</p>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}" class="text-white-50"><i class="fas fa-home me-1"></i> {{__('Home')}}</a></li>
                                        <li class="breadcrumb-item font-14 active text-white" aria-current="page"><i class="fas fa-question-circle me-1"></i> {{__('Support')}}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Modern Page Header End -->

        <!-- Modern FAQ Area Start -->
        <section class="faq-area support-tickets-page section-t-space">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <div class="section-title">
                            <div class="support-icon mb-3">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h3 class="section-heading mb-3">{{ __(get_option('support_faq_title')) }}</h3>
                            <p class="section-sub-heading text-muted">{{ __(get_option('support_faq_subtitle')) }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Search Box -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <div class="faq-search-box">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search FAQs...">
                                <button class="btn btn-primary" type="button">Search</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Categories -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="faq-categories">
                            <div class="row g-3 justify-content-center">
                                <div class="col-md-3 col-sm-6">
                                    <a href="#" class="faq-category-card">
                                        <div class="category-icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <h5>Student Help</h5>
                                        <p>12 articles</p>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="#" class="faq-category-card">
                                        <div class="category-icon">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <h5>Instructor Help</h5>
                                        <p>8 articles</p>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="#" class="faq-category-card">
                                        <div class="category-icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <h5>Payments</h5>
                                        <p>5 articles</p>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="#" class="faq-category-card">
                                        <div class="category-icon">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <h5>Technical</h5>
                                        <p>7 articles</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modern Accordion -->
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="modern-accordion" id="faqAccordion">
                            @php $count = true @endphp
                            @foreach($faqQuestions as $key => $faqQuestion)
                                <div class="accordion-card">
                                    <div class="accordion-header" id="heading{{ $faqQuestion->id }}">
                                        <button class="accordion-button {{ $count ? null : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faqQuestion->id }}"
                                                aria-expanded="{{ $count ? 'true' : 'false' }}" aria-controls="collapse{{ $faqQuestion->id }}">
                                            <div class="accordion-icon me-3">
                                                <i class="fas fa-question"></i>
                                            </div>
                                            <div class="accordion-title">
                                                <h5>{{ __($faqQuestion->question) }}</h5>
                                            </div>
                                            <div class="accordion-arrow">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </button>
                                    </div>
                                    <div id="collapse{{ $faqQuestion->id }}" class="accordion-collapse collapse {{ $count ? 'show' : null }}" aria-labelledby="heading{{ $faqQuestion->id }}" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <div class="answer-icon me-3">
                                                <i class="fas fa-lightbulb"></i>
                                            </div>
                                            <div class="answer-content">
                                                {{ __($faqQuestion->answer) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $count = false @endphp
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Support Ticket CTA -->
                <div class="row justify-content-center mt-5 pt-5">
                    <div class="col-lg-8 text-center">
                        <div class="support-cta-box">
                            <div class="cta-icon mb-4">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <h3 class="mb-3">{{ __(get_option('ticket_title')) }}</h3>
                            <p class="mb-4 text-muted">{{ __(get_option('ticket_subtitle')) }}</p>
                            <div class="cta-buttons">
                                <a href="{{ route('student.support-ticket.create') }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-plus-circle me-2"></i> {{ __('Create New Ticket') }}
                                </a>
                                <a href="{{ route('student.support-ticket.create') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-ticket-alt me-2"></i> {{ __('View Tickets') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="row mt-5 pt-4">
                    <div class="col-12">
                        <div class="contact-info-box">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="contact-method">
                                        <div class="contact-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <h5>Email Us</h5>
                                        <p>learnspace@learnspace.co.tz</p>
                                        <a href="mailto:learnspace@learnspace.co.tz" class="contact-link">Send Message <i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="contact-method">
                                        <div class="contact-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <h5>Call Us</h5>
                                        <p>+255 (073) 837- 0786</p>
                                        <a href="tel:+15551234567" class="contact-link">Call Now <i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="contact-method">
                                        <div class="contact-icon">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <h5>Live Chat</h5>
                                        <p>Available 24/7</p>
                                        <a href="#" class="contact-link">Start Chat <i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modern FAQ Area End -->
    </div>

@endsection

@push('style')
<style>
    /* Modern Header Styles */

    
    .support-header-icon {
        font-size: 50px;
        color: rgba(255,255,255,0.9);
    }
    
    .support-header-icon i {
        background: rgba(255,255,255,0.1);
        width: 100px;
        height: 100px;
        line-height: 100px;
        border-radius: 50%;
        display: inline-block;
    }
    
    /* Section Title Styles */
    .section-title {
        position: relative;
        padding-bottom: 20px;
    }
    
    .section-title:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }
    
    .support-icon {
        font-size: 40px;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 50%;
        display: inline-block;
        margin-bottom: 20px;
    }
    
    .section-heading {
        font-weight: 700;
        color: #2c3e50;
        font-size: 36px;
    }
    
    .section-sub-heading {
        color: #7f8c8d;
        font-size: 18px;
    }
    
    /* FAQ Search Box */
    .faq-search-box {
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .faq-search-box .input-group-text {
        background: #fff;
        border: none;
        padding: 0 20px;
        color: #667eea;
    }
    
    .faq-search-box .form-control {
        height: 60px;
        border: none;
        box-shadow: none;
        padding-left: 0;
    }
    
    .faq-search-box .btn {
        padding: 0 25px;
        font-weight: 600;
    }
    
    /* FAQ Categories */
    .faq-category-card {
        display: block;
        background: #fff;
        border-radius: 10px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        color: #2c3e50;
        height: 100%;
    }
    
    .faq-category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        color: #667eea;
    }
    
    .category-icon {
        font-size: 30px;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        width: 70px;
        height: 70px;
        line-height: 70px;
        border-radius: 50%;
        display: inline-block;
        margin-bottom: 15px;
    }
    
    .faq-category-card h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .faq-category-card p {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    /* Modern Accordion */
    .modern-accordion {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .accordion-card {
        background: #fff;
        border-bottom: 1px solid #eee;
    }
    
    .accordion-card:last-child {
        border-bottom: none;
    }
    
    .accordion-button {
        width: 100%;
        padding: 25px;
        text-align: left;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) {
        background: rgba(102, 126, 234, 0.05);
    }
    
    .accordion-button:hover {
        background: rgba(102, 126, 234, 0.05);
    }
    
    .accordion-icon {
        font-size: 20px;
        color: #667eea;
        min-width: 30px;
    }
    
    .accordion-title {
        flex: 1;
    }
    
    .accordion-title h5 {
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }
    
    .accordion-arrow {
        transition: transform 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) .accordion-arrow {
        transform: rotate(180deg);
    }
    
    .accordion-body {
        padding: 0 25px 25px 70px;
        display: flex;
    }
    
    .answer-icon {
        font-size: 20px;
        color: #2ecc71;
        min-width: 30px;
    }
    
    .answer-content {
        color: #7f8c8d;
        line-height: 1.7;
    }
    
    /* Support CTA Box */
    .support-cta-box {
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-top: 3px solid #667eea;
    }
    
    .cta-icon {
        font-size: 50px;
        color: #667eea;
    }
    
    .cta-buttons .btn {
        padding: 12px 25px;
        font-weight: 600;
        border-radius: 8px;
    }
    
    /* Contact Info Box */
    .contact-info-box {
        margin-top: 30px;
    }
    
    .contact-method {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .contact-method:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .contact-icon {
        font-size: 30px;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        width: 70px;
        height: 70px;
        line-height: 70px;
        border-radius: 50%;
        display: inline-block;
        margin-bottom: 20px;
    }
    
    .contact-method h5 {
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .contact-method p {
        color: #7f8c8d;
        margin-bottom: 15px;
    }
    
    .contact-link {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .contact-link:hover {
        color: #764ba2;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .section-heading {
            font-size: 28px;
        }
        
        .accordion-button {
            padding: 20px 15px;
        }
        
        .accordion-body {
            padding: 0 15px 20px 60px;
        }
        
        .cta-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .cta-buttons .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>
@endpush
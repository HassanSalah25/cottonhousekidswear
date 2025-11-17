@extends('frontend.layout.app')
@push('styles')
    <style>
        .lined {
            position: relative;
            padding-bottom: 0.7rem;
        }
        .lined::after {
            content: "";
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 6.25rem;
            height: 2px;
            background: red;
        }
        #contactMap { height: 250px; }

    </style>

@endpush
@section('content')

    <section class="py-5">
        <!-- MAP SECTION-->
        <div class="row">
            <div class="border-top border-primary col-md-6" >

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.3337680685877!2d31.33942459999999!3d30.0559659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583f09cd2ae76b%3A0x885fa9971cea8fa5!2sCotton%20House%20Kids%20Wear!5e0!3m2!1sar!2seg!4v1708771533731!5m2!1sar!2seg" height="450" style="border:0;width: 100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            </div>
            <div class="border-top border-primary col-md-6" >

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3452.321694263651!2d31.285758599999994!3d30.0849719!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583fc1d1666ab3%3A0x7e1d6c13ea6d671a!2sCotton%20House%20kids%20Wear!5e0!3m2!1sar!2seg!4v1708771554491!5m2!1sar!2seg" height="450" style="border:0;width: 100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            </div>
        </div>
        <div class="container py-4">
            <div class="row gy-5">
                <div class="col-lg-8">
                    <h2 class="text-uppercase lined mb-4">{{translate('We are here to help you')}}</h2>
                    <p class="lead mb-5">{{translate('cottonhousekidswear platform offer tailored shopping experiences. With features like personalized product recommendations, customizable interfaces, and dynamic pricing, they enhance user engagement and drive sales. From individualized product configurations to tailored marketing campaigns, custom e-commerce solutions empower businesses to cater to unique customer needs and preferences, ensuring a seamless and satisfying shopping journey.')}}</p>
                    <p class="text-sm mb-4">{{translate('Please feel free to contact us, our customer service center is working for you 24/7.')}}</p>
                    <!-- CONTACT FORM-->
                    <h2 class="lined text-uppercase mb-4">{{translate('Contact form')}}</h2>
                    <form action="{{route('page.contact_us.store')}}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="firstname">{{translate('First Name')}}</label>
                                <input class="form-control" name="first_name" type="text">
                                @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="lastname">{{translate('Last Name')}}</label>
                                <input class="form-control" name="last_name" type="text">
                                @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="emailaddress">{{translate('Email Address')}}</label>
                                <input class="form-control" name="email" type="email">
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="subject">{{translate('Subject')}}</label>
                                <input class="form-control" name="subject" type="text">
                                @error('subject')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="message">{{translate('Message')}}</label>
                                <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                                @error('message')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-outline-primary rounded" type="submit"><i class="fa fa-envelope mx-2"></i>{{translate('Send message')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- CONTACT INFO-->
                <div class="col-lg-4">
                    <h3 class="text-uppercase mb-3">{{translate('Address')}}</h3>
                    <p class="text-sm mb-4"><li class="fa fa-map-marker"></li> {{translate(get_setting('contact_address'))}}</p>
                    <p class="text-sm mb-4"><li class="fa fa-map-marker"></li> {{translate(get_setting('contact_address_2'))}}</p>
                    <h3 class="text-uppercase mb-3">{{translate('Call center')}}</h3>
                    <p class="text-sm mb-4">
                        @if(count(explode('-', get_setting('contact_phone'))) > 1)
                            @foreach(explode('-', get_setting('contact_phone')) as $phone)
                                <a href="tel:{{trim($phone)}}">
                                    {{trim($phone)}}

                                </a>
                                @if(!$loop->last)
                                    -
                                @endif
                            @endforeach

                        @else
                            <a href="tel:{{trim(get_setting()['contact_info']['contact_phone'])}}">
                                {{trim(get_setting('contact_phone'))}}
                            </a>
                        @endif
                    </p>
                    <ul class="text-sm mb-0">
                        <li><strong><a href="mailto:{{get_setting('contact_email')}}">{{get_setting('contact_email')}}</a></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>



@endsection

@push('scripts')
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <script>

    var map = L.map('contactMap').setView([51.505, -0.09], 13);
    var marker = L.marker([51.5, -0.09]).addTo(map);
    var circle = L.circle([51.508, -0.11], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 500
    }).addTo(map);
</script>

@endpush

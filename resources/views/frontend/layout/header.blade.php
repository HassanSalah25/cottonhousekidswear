<!-- Header -->
<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-12">
                    <!-- Top Left -->
                    <div class="top-left">
                        <ul class="list-main">
                            <li>
                                <i class="ti-location-pin"></i> {{translate($settings['contact_info']['contact_address_2'])}}
                            </li>
                        </ul>
                    </div>

                    <!--/ End Top Left -->
                </div>
                <div class="col-lg-7 col-md-12 col-12">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            <li>
                                <i class="ti-location-pin"></i> {{translate($settings['contact_info']['contact_address'])}}
                            </li>

                            {{--                            <li><i class="ti-alarm-clock"></i> <a href="#">Daily deal</a></li>      --}}
                            @auth
                                <li>
                                    <i class="ti-user"></i>
                                    <a class="dropdown-toggle"
                                       id="userDropdown" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="true"
                                       href="#">
                                        @if(count(explode(' ',Auth::user()->name)) > 1)
                                            {{explode(' ',Auth::user()->name)[0]}}
                                        @else
                                            {{Auth::user()->name}}
                                        @endif

                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <!-- Dropdown items -->
                                        <div class="dropdown-divider"></div>
                                        <form action="{{route('logout')}}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">{{translate('Logout')}}</button>
                                        </form>
                                    </div>
                                </li>
                            @else
                                <li><i class="ti-power-off"></i><a
                                        href="{{route('customer.login')}}">{{translate('Login')}}</a></li>
                            @endauth
                            <li>
                                <!-- language -->
                                @php
                                    if(Session::has('locale')){
                                        $locale = Session::get('locale', Config::get('app.locale'));
                                    }
                                    else{
                                        $locale = env('DEFAULT_LANGUAGE');
                                    }
                                    $language = \App\Models\Language::where('code', $locale)->first();
                                @endphp
                                <div class="align-items-end ml-3 mr-0">
                                    <div class="align-items-center d-flex dropdown" id="lang-change">
                                        <a class="dropdown-toggle no-arrow" data-toggle="dropdown"
                                           href="javascript:void(0);" role="button" aria-haspopup="false"
                                           aria-expanded="false">
                                                <span>
                                                    <img
                                                        src="{{ static_asset('assets/img/flags/'.$language->flag.'.png') }}"
                                                        height="11">
                                                    <span
                                                        class="fw-500 fs-13 ml-2 mr-0 opacity-60  d-none d-md-inline-block">{{ $language->name }}</span>
                                                </span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-xs">

                                            @foreach (\App\Models\Language::where('status',1)->get() as $key => $language)
                                                <li>
                                                    <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                                       class="dropdown-item @if($locale == $language->code) active @endif">
                                                        <img
                                                            src="{{ static_asset('assets/img/flags/'.$language->flag.'.png') }}"
                                                            class="mr-2">
                                                        <span class="language">{{ $language->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
        <div class="container mobile_view">
            <div class="row">

                <!-- language -->
                @php
                    if(Session::has('locale')){
                        $locale = Session::get('locale', Config::get('app.locale'));
                    }
                    else{
                        $locale = env('DEFAULT_LANGUAGE');
                    }
                    $language = \App\Models\Language::where('code', $locale)->first();
                @endphp
                <div class="align-items-end ml-3 mr-0">
                    <div class="align-items-center d-flex dropdown" id="lang-change">
                        <a class="dropdown-toggle no-arrow" data-toggle="dropdown"
                           href="javascript:void(0);" role="button" aria-haspopup="false"
                           aria-expanded="false">
                                                <span>
                                                    <img
                                                        src="{{ static_asset('assets/img/flags/'.$language->flag.'.png') }}"
                                                        height="11">
                                                    <span
                                                        class="fw-500 fs-13 ml-2 mr-0 opacity-60  d-none d-md-inline-block">{{ $language->name }}</span>
                                                </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-xs">

                            @foreach (\App\Models\Language::where('status',1)->get() as $key => $language)
                                <li>
                                    <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                       class="dropdown-item @if($locale == $language->code) active @endif">
                                        <img
                                            src="{{ static_asset('assets/img/flags/'.$language->flag.'.png') }}"
                                            class="mr-2">
                                        <span class="language">{{ $language->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12 text-header text-center " style="background-color: red;height: 24px">

                </div>
                <div class="col-lg-2 col-md-2 col-12 my-1 mainn">
                        @include('frontend.partials.components.profile-section')
                    <!-- Logo -->
                       <div class="logo">
                           <a href="{{$settings['top_banner']['link']}}"><img src="{{$settings['appLogo']}}"
                                                                              alt="logo"></a>
                       </div>

                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <form action="{{route('page.search')}}" class="search-top">
                        <div class="top-search"><a><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top st d-flex">
                            <form class="search-form">
                                <input type="text" placeholder="Search {{translate('search_by_product_name')}}"
                                       value="{{request('keyword')}}"
                                       name="keyword">
                                <button value="search" type="submit" style="padding: 10px;"><i class="ti-search"></i>
                                </button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </form>
                    <!--/ End Search Form -->
                    <div class="mobile-nav">


                    </div>

                </div>
                <div class="col-lg-2 col-md-2 col-12 text-header text-center " style="background-color: red;">
                    <p style="font-size: 10px;color: white;font-weight: bold;">{{translate('Lovely Little Clothes For Lovely Little Pepople')}}</p>
                </div>
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <form action="{{route('page.search')}}" class="search-bar">
                            <select name="category_id">
                                <option selected="selected">{{translate('all_categories')}}</option>
                                @foreach($settings['all_categories'] as $category)
                                    <option value="{{$category->id}}"
                                        {{ $category->id == request('category_id') ? 'selected' : '' }}
                                    >{{$category->getTranslation('name')}}</option>
                                @endforeach
                            </select>
                            <div class="row" style="flex-wrap: nowrap !important;">

                                <input name="keyword" placeholder="{{translate('search_by_product_name')}}"
                                       value="{{request('keyword')}}"
                                       type="search">
                                <button class="btnn"><i class="ti-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-12" id="profile-sec">
                    @include('frontend.partials.components.profile-section')
                </div>
            </div>
        </div>
    </div>

    @include('frontend.layout.nav')
</header>
<!--/ End Header -->

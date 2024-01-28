<!-- Header -->
<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-12">
                    <!-- Top Left -->
                    <div class="top-left">
                        <ul class="list-main">
                            <li><i class="ti-headphone-alt"></i> {{$settings['contact_info']['contact_phone']}}</li>
                            <li><i class="ti-email"></i> {{$settings['contact_info']['contact_email']}}</li>
                        </ul>
                    </div>
                    <!--/ End Top Left -->
                </div>
                <div class="col-lg-7 col-md-12 col-12">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            <li><i class="ti-location-pin"></i> {{$settings['contact_info']['contact_address']}}</li>
                            {{--                            <li><i class="ti-alarm-clock"></i> <a href="#">Daily deal</a></li>      --}}
                            @auth
                                <li>
                                    <i class="ti-user"></i>
                                    <a class="dropdown-toggle"
                                       id="userDropdown" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="true"
                                       href="#">{{Auth::user()->name}}</a>

                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <!-- Dropdown items -->
                                        <div class="dropdown-divider"></div>
                                        <form action="{{route('logout')}}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item" >{{translate('Logout')}}</button>
                                        </form>
                                    </div>
                                </li>
                            @else
                                <li><i class="ti-power-off"></i><a href="{{route('customer.login')}}">{{translate('Login')}}</a></li>
                            @endauth
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12">
                    <!-- Logo -->
                    <div class="logo">
                        <a href=""><img src="{{$settings['appLogo']}}" alt="logo"></a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <form action="{{route('page.search')}}" class="search-top">
                        <div class="top-search"><a href=""><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top">
                            <form class="search-form">
                                <input type="text" placeholder="Search {{translate('search_by_product_name')}}"
                                       value="{{request('keyword')}}"
                                       name="keyword">
                                <button value="search" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </form>
                    <!--/ End Search Form -->
                    <div class="mobile-nav"></div>
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
                            <div >

                                <input name="keyword" placeholder="{{translate('search_by_product_name')}}"
                                       value="{{request('keyword')}}"
                                       type="search">
                                <button class="btnn"><i class="ti-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-12">
                    @include('frontend.partials.components.profile-section')
                </div>
            </div>
        </div>
    </div>

    @include('frontend.layout.nav')

</header>
<!--/ End Header -->

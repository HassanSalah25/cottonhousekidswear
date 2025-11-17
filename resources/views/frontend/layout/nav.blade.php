<!-- Header Inner -->
<div class="header-inner">
    <div class="container">
        <div class="cat-nav-head">
            <div class="row">
                <div class="col-lg-3">
                    <div class="all-category">
                        <a href="{{route('page.all_categories')}}">
                            <h3 class="cat-heading"><i class="fa fa-bars"
                                                       aria-hidden="true"></i>{{strtoupper(translate('all_categories'))}}

                            </h3>
                        </a>
                        <ul class="main-category">
                            <li class="main-mega"><a href="#">{{translate('Best Selling')}} <i class="fa fa-angle-right"
                                                                                               aria-hidden="true"></i></a>
                                <ul class="mega-menu">
                                    @if(count($settings['best_selling_categories']) > 0)
                                        @foreach($settings['best_selling_categories'] as $category)
                                            <li class="single-menu">
                                                <a href="{{ route('page.all_sub_category', $category->slug) }}" class="title-link">{{$category->getTranslation('name')}}</a>
                                                <div class="image">
                                                    <img src="{{api_asset($category->banner)}}" alt="#">
                                                </div>
                                                <div class="inner-link">
                                                    @foreach($category->products->take(4) as $product)
                                                        <a href="{{ route('product.details.show', $product->id) }}">{{$product->getTranslation('name')}}</a>
                                                    @endforeach
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>

                            @foreach($settings['all_categories'] as $category)
                                @if($category->childrenCategories->count() != 0 )
                                    <li class="main-mega"><a href="{{ route('page.all_sub_category', $category->slug) }}">{{$category->getTranslation('name')}}<i
                                                class="fa fa-angle-right" aria-hidden="true"></i></a>
                                        <ul class="mega-menu">
                                            <li class="single-menu">
                                                <div class="inner-link">
                                                    @foreach($category->childrenCategories as $category)
                                                        <a href="{{ route('page.category', $category->slug) }}">{{$category->getTranslation('name')}}</a>
                                                    @endforeach
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                @else
                                    <li><a href="#">{{$category->getTranslation('name')}}</a></li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="menu-area">
                        <!-- Main Menu -->
                        <nav class="navbar navbar-expand-lg">
                            <div class="navbar-collapse">
                                <div class="nav-inner">
                                    <ul class="nav main-menu menu navbar-nav">
                                        <li class="{{ areActiveRoute('/') }}"><a
                                                href="/">{{translate('Home')}}</a></li>
                                        <li class="{{ areActiveRoute(route('page.all_products')) }}"><a
                                                href="{{route('page.all_products')}}">{{translate('All Products')}}</a>
                                        @if($settings['existing_offers'])
                                            <li class="{{ areActiveRoute(route('page.offers')) }}"><a
                                                    href="{{route('page.offers')}}">{{translate('Offers')}}</a></li>
                                        @endif
                                        @if(count($settings['header_menu']) > 0)
                                            @foreach($settings['header_menu'] as $name => $li)
                                                <li class="{{$li ? areActiveRoute($li) : null }}"><a
                                                        href="{{$li}}">{{translate($name)}}</a></li>
                                            @endforeach
                                        @endif
                                        <li class="{{ areActiveRoute(route('page.contact_us')) }}"><a
                                                href="{{route('page.contact_us')}}">{{translate('Contact Us')}}</a>
                                    </ul>
                                </div>
                            </div>

                        </nav>

                        <!--/ End Main Menu -->
                    </div>

                </div>
                <div class="col-lg-3 col-md-3 col-12 d-none right-bar-container">
                    @include('frontend.partials.components.profile-section')
                </div>

            </div>

        </div>
    </div>
</div>
<!--/ End Header Inner -->

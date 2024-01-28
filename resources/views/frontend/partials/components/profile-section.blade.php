<div class="right-bar">
    <div class="sinlge-bar">
        @if(Auth::check())
            <a href="{{route('profile.dashboard',['tab'=>'v-pills-messages-tab'])}}" class="single-icon">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
                <span class="total-count2">
                                        {{auth()->user()->wishlists->count()}}
                                </span>
            </a>
        @else
            <a href="{{route('customer.login')}}" class="single-icon">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
                <span class="total-count2">
                                        0
                                </span>
            </a>
        @endif
    </div>
    @if(Auth::check())
        <div class="sinlge-bar">
            <a class="single-icon" href="{{route('profile.dashboard')}}"><i class="fa fa-user-circle-o"
                                                                            aria-hidden="true"></i></a>
        </div>
    @else
        <div class="sinlge-bar">
            <a class="single-icon" href="{{route('customer.login')}}"><i class="fa fa-user-circle-o"
                                                                         aria-hidden="true"></i></a>
        </div>
    @endif
    @include('frontend.partials.components.cart')
</div>

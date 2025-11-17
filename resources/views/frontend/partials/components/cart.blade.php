<div class="sinlge-bar shopping">
    <a href="#" class="single-icon"><i class="ti-bag"></i> <span
            class="total-count">0</span></a>
    <!-- Shopping Item -->
    <div class="shopping-item">
        <div class="dropdown-cart-header">
            <span>0</span> {{translate('Items')}}
            <a href="{{route('page.cart')}}">{{translate('View Cart')}}</a>
        </div>
        <ul class="shopping-list">

        </ul>
        <div class="bottom">
            <div class="total">
                <span>{{translate('Total')}}</span>
                <span class="total-amount">0 {{$settings['general_settings']['currency']['code']}}</span>
            </div>
            <a href="{{route('page.cart')}}" class="btn animate">{{translate('Checkout')}}</a>
        </div>
    </div>
    <!--/ End Shopping Item -->
</div>

@push('scripts')

@endpush

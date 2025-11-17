@extends('frontend.layout.app')
@section('content')

    <section>
        <div class="container mt-5">
            <h2>{{translate('My Account')}}</h2>
            <br><br>
            <div class="row rounded nav-div">
                <div class="col-3 mb-4 mt-3 pt-4 pb-3 bg-light w-auto h-100">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill"
                           href="#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                           aria-selected="false">
                            <i class="fa fa-user"></i> {{translate('Your Profile')}}
                        </a>
                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages"
                           role="tab" aria-controls="v-pills-messages" aria-selected="false">
                            <i class="fa fa-heart"></i> {{translate('My Wishlist')}}
                        </a>
                        <a class="nav-link" id="v-pills-orders-tab" data-toggle="pill" href="#v-pills-orders"
                           role="tab" aria-controls="v-pills-orders" aria-selected="false">
                            <i class="fa fa-shopping-cart"></i> {{translate('My Orders')}}
                        </a>
                    </div>
                </div>
                <div class="col-9 mt-3">
                    <form action="{{route('profile.info.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content bg-light pb-5" id="v-pills-tabContent">
                            <div class="tab-pane fade p-3" id="v-pills-profile" role="tabpanel"
                                 aria-labelledby="v-pills-profile-tab">
                                <img id="profilePic"
                                     style="
                                height:200px;
                                width:200px"
                                     src="{{auth()->user()->avatar ? api_asset(auth()->user()->avatar) : 'https://icon-library.com/images/default-user-icon/default-user-icon-8.jpg'}}"
                                     class="rounded-circle mx-2"><br>
                                <h5 class="d-inline-block">{{auth()->user()->name}}
                                    <small class="text-success m-2">
                                        <i class="fa fa-pencil"></i> <input id="img_profile"
                                                                            name="avatar"
                                                                            onchange="previewImage(this)"
                                                                            class="m-2" type="file"></small>
                                    <small class="text-danger m-2">
                                        <i class="fa fa-times"></i>
                                        <a style="cursor: pointer" onclick="deleteImg()">
                                            {{translate('Remove')}}
                                        </a></small></h5>
                                <br>
                                <br>
                                <hr>
                                <br>
                                <form>
                                    <div class="row pb-5">
                                        <div class="col">
                                            <label class="font-weight-bold d-inline-block">{{translate('Full Name')}}
                                                ></label>
                                            <input type="text" class="form-control" name="name"
                                                   value="{{auth()->user()->name}}">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <p class="font-weight-bold d-inline-block">{{translate('Email Address')}}</p>
                                            {{--                                    <p class="text-muted d-inline-block float-right">For notifications and logging in</p>--}}
                                            <input type="email" class="form-control"
                                                   name="email"
                                                   placeholder="email@example.com"
                                                   value="{{auth()->user()->email}}">
                                        </div>
                                        <div class="col">
                                            <p class="font-weight-bold d-inline-block">{{translate('Phone')}}</p>
                                            {{--                                    <p class="text-muted d-inline-block float-right">For receiving notifications</p>--}}
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control" placeholder="Type here"
                                                       name="phone"
                                                       value="{{auth()->user()->phone}}">

                                            </div>

                                        </div>
                                    </div>
                                </form>
                                <hr>

                                {{-- <div class="delAccount pt-3">
                                     <h5 class="text-dark">Delete Account</h5>
                                     <a href="" class="text-muted float-right font-weight-bold">Delete Your Account</a>
                                     <p class="text-muted">By deleting your account, you will lose all your data.</p>
                                 </div>
                                 <hr class="mt-4">--}}
                                <br><br>

                                <button type="submit" class="btn  float-right mt-3 mb-5"
                                        data-toggle="modal"
                                        data-target=".bd-example-modal-md">{{translate('Save Changes')}}
                                </button>
                                <br>
                            </div>
                            <div class="tab-pane fade p-3" id="v-pills-messages" role="tabpanel"
                                 aria-labelledby="v-pills-messages-tab">
                                <div class="row">
                                    @if(count($wishlist_products) > 0)
                                        @foreach($wishlist_products as $product)
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                                @include('frontend.partials.components.product_card')
                                            </div>

                                            {{$wishlist_products->links()}}

                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="la la-frown-o d-block heading-1 text-danger mb-3"></i>
                                                    <h3>{{ translate('No product found') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade p-3" id="v-pills-orders" role="tabpanel"
                                 aria-labelledby="v-pills-messages-tab">
                                <div class="row justify-content-center">

                                    <table class="table aiz-table mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ translate('Order Code') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Products') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Attributes') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Amount') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Payment Status') }}</th>
                                            <th data-breakpoints="lg" class="text-right"
                                                width="15%">{{ translate('options') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($orders as $key => $order)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}
                                                </td>
                                                <td>
                                                    @if (addon_is_activated('multi_vendor'))
                                                        <div>{{ translate('Package') }} {{ $order->code }} {{ translate('of') }}</div>
                                                    @endif
                                                    <div class="fw-600">{{ $order->combined_order->code ?? '' }}</div>
                                                </td>
                                                <td>
                                                    {{ count($order->orderDetails) }}
                                                </td>
                                                <td>
                                                    @foreach ($order->orderDetails as $order_details)
                                                        @if($order_details->product)
                                                            <a style="font-size: 12px"
                                                               href="{{route('product.details.show',$order_details->product->slug)}}">{{$order_details->product->getTranslation('name')}}
                                                            </a>
                                                            <hr>
                                                        @else
                                                            <span
                                                                class="text-danger">{{ translate('Product Unavailable') }}</span>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($order_details->variation)
                                                    @foreach($order_details->variation->combinations as $combine)
                                                        @if($order_details->product)
                                                            <span class="text-dark mt-4">{{$combine->attribute->getTranslation('name')}}
                                                                    : {{$combine->attribute_value->getTranslation('name')}}</span>
                                                        @else
                                                            <span
                                                                class="text-danger">{{ translate('Product Unavailable') }}</span>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ format_price($order->grand_total) }}
                                                </td>
                                                <td>
                                                <span
                                                    class="text-capitalize">{{ translate(str_replace('_', ' ', $order->delivery_status)) }}</span>
                                                </td>
                                                <td>
                                                    @if ($order->payment_status == 'paid')
                                                        <span
                                                            class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a class="text-info"
                                                       href="{{ route('order.show', $order->id) }}"
                                                       title="{{ translate('View') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{ $orders->appends(request()->input())->links() }}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

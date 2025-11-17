@extends('frontend.layout.app')
@push('styles')
    <style>
        .card {
            z-index: 0;
            background-color: #eceff1;
            padding-bottom: 20px;
            margin-top: 90px;
            margin-bottom: 90px;
            border-radius: 10px;
        }

        .top {
            padding-top: 40px;
            padding-left: 13% !important;
            padding-right: 13% !important;
        }

        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: #455a64;
            padding-left: 0px;
            margin-top: 30px;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 13px;
            width: 25%;
            float: left;
            position: relative;
            font-weight: 400;
        }

        #progressbar .step0:before {
            font-family: FontAwesome;
            content: "\f10c";
            color: #fff;
            transition: all 0.2s;
            -webkit-transition: all 0.2s;
            -moz-transition: all 0.2s;
            -ms-transition: all 0.2s;
            -o-transition: all 0.2s;
        }

        #progressbar li:before {
            width: 40px;
            height: 40px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            background: #c5cae9;
            border-radius: 50%;
            margin: auto;
            padding: 0px;
        }

        #progressbar li:after {
            content: "";
            width: 100%;
            height: 12px;
            background: #c5cae9;
            position: absolute;
            left: 0;
            top: 16px;
            z-index: -1;
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            position: absolute;
            left: -50%;
        }

        #progressbar li:nth-child(2):after,
        #progressbar li:nth-child(3):after {
            left: -50%;
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            position: absolute;
            left: 50%;
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #progressbar li.active:before,
        #progressbar li.active:after {
            background: red;
        }

        #progressbar li.active:before {
            font-family: FontAwesome;
            content: "\f00c";
        }

        .icon_order {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .icon-content {
            padding-bottom: 20px;
        }

        @media screen and (max-width: 992px) {
            .icon-content {
                width: 50%;
            }
        }

    </style>
@endpush
@section('content')
    <div class="container px-1 px-md-4 py-5 mx-auto">
        <h2>{{translate('Order Tracking')}}</h2>
        <div class="card">
            <div class="row d-flex justify-content-between px-3 top">
                <div class="d-flex">
                    <h5>{{translate('ORDER')}} <span class="text-danger font-weight-bold">{{$order->code}}</span></h5>
                </div>
                <div class="d-flex flex-column text-sm-right">
                    <p>{{translate('Created At')}} <span
                            class="font-weight-bold">{{$order   ->created_at->toFormattedDateString()}}</span></p>
                    <p class="mb-0">{{translate('Expected Arrival')}} <span></span></p>
                </div>
            </div> <!-- Add class 'active' to progress -->
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <ul id="progressbar" class="text-center">
                        @foreach ($order->orders as $key => $order)
                            @if($order->delivery_status == 'processed')
                                <li class="step0 active "></li>
                                <li class="step0"></li>
                                <li class="step0"></li>
                                <li class="step0"></li>
                            @endif
                            @if($order->delivery_status == 'confirmed')
                                    <li class="step0 active "></li>
                                    <li class="step0 active "></li>
                                    <li class="step0"></li>
                                    <li class="step0"></li>
                            @endif
                            @if($order->delivery_status == 'shipped')
                                    <li class="step0 active "></li>
                                    <li class="step0 active "></li>
                                    <li class="step0 active "></li>
                                    <li class="step0"></li>
                            @endif
                            @if($order->delivery_status == 'delivered')
                                    <li class="step0 active "></li>
                                    <li class="step0 active "></li>
                                    <li class="step0 active "></li>
                                    <li class="step0 active "></li>
                            @endif
                            @if($order->delivery_status == "order_placed")
                                    <li class="step0"></li>
                                    <li class="step0"></li>
                                    <li class="step0"></li>
                                    <li class="step0"></li>
                                @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row justify-content-between top">
                <div class="row d-flex icon-content text-dark"><img class="icon_order"
                                                                    src="https://i.imgur.com/9nnc9Et.png">
                    <div class="d-flex flex-column"><br>
                        <p class="font-weight-bold">{{translate('Order Processed')}}</p>
                    </div>
                </div>
                <div class="row d-flex icon-content text-dark"><i class="fa fa-check-circle fa-4x mx-3"></i>
                    <div class="d-flex flex-column"><br>
                        <p class="font-weight-bold">{{translate('Order Confirmed')}}</p>
                    </div>
                </div>
                <div class="row d-flex icon-content text-dark"><img class="icon_order"
                                                                    src="https://i.imgur.com/TkPm63y.png">
                    <div class="d-flex flex-column"><br>
                        <p class="font-weight-bold">{{translate('Order Shipped')}}</p>
                    </div>
                </div>
                <div class="row d-flex icon-content text-dark"><img class="icon_order"
                                                                    src="https://i.imgur.com/HdsziHP.png">
                    <div class="d-flex flex-column"><br>
                        <p class="font-weight-bold">{{translate('Order delivered')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

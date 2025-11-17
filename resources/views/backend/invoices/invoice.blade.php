<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <style type="text/css">
        :root {
            --theme-color: #000000;
            --white: #ffffff;
            --black: #000000;
        }

        body {
            font-family: 'Lato', sans-serif;
        }

        p,
        li,
        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
            line-height: 1.5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th {
            padding: 0.75rem;
            text-align: left;
        }

        table tr td {
            padding: 0.75rem;
            text-align: left;
        }

        table th small {
            display: block;
            font-size: 12px;
        }

        .invoice-preview-main {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            background: #ffff;
            box-shadow: 0 0 10px #ddd;
        }

        .invoice-logo {
            max-width: 200px;
            width: 100%;
        }

        .invoice-header table td {
            padding: 15px 30px;
        }

        .text-right {
            text-align: right;
        }

        .no-space tr td {
            padding: 0;
            white-space: nowrap;
        }

        .vertical-align-top td {
            vertical-align: top;
        }

        .view-qrcode {
            max-width: 139px;
            height: 139px;
            width: 100%;
            margin-left: auto;
            margin-top: 15px;
            background: var(--white);
            padding: 13px;
            border-radius: 10px;
        }

        .view-qrcode img {
            width: 100%;
            height: 100%;
        }

        .invoice-body {
            padding: 30px 25px 0;
        }



        table.add-border tr {
            border-top: 1px solid var(--theme-color);
        }

        tfoot tr:first-of-type {
            border-bottom: 1px solid var(--theme-color);
        }

        .total-table tr:first-of-type td {
            padding-top: 0;
        }

        .total-table tr:first-of-type {
            border-top: 0;
        }

        .sub-total {
            padding-right: 0;
            padding-left: 0;
        }

        .border-0 {
            border: none !important;
        }

        .invoice-summary td,
        .invoice-summary th {
            font-size: 13px;
            font-weight: 600;
        }

        .total-table td:last-of-type {
            width: 146px;
        }

        .invoice-footer {
            padding: 15px 20px;
        }

        .itm-description td {
            padding-top: 0;
        }
        html[dir="rtl"] table tr td,
        html[dir="rtl"] table tr th{
            text-align: right;
        }
        html[dir="rtl"]  .text-right{
            text-align: left;
        }
        html[dir="rtl"] .view-qrcode{
            margin-left: 0;
            margin-right: auto;
        }
    </style>


</head>

<body id="boxes">
    <div>
        <div style="padding:0px 19px;">
            <table>
                <thead>
                    <tr>
                        <th width="50%"></th>
                        <th width="50%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if (get_setting('invoice_logo') != null)
                                                <img src="{{ uploaded_asset(get_setting('invoice_logo')) }}"
                                                    height="30" style="display:inline-block;margin-bottom:10px">
                                            @else
                                                <img src="{{ static_asset('assets/img/logo.png') }}" height="30"
                                                    style="display:inline-block;margin-bottom:10px">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="" class="bold">{{ get_setting('site_name') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ get_setting('invoice_address') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Email') }}:
                                            {{ get_setting('invoice_email') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Phone') }}:
                                            {{ get_setting('invoice_phone') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="text-right">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 2rem;" class="bold">{{ translate('INVOICE') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ translate('Order Code') }}:</span>
                                            <span class="bold"
                                                style="color: #ED2939">{{ $order->combined_order->code }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ translate('Order Date') }}:</span>
                                            <span
                                                class="bold">{{ $order->created_at->format('d.m.Y') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ translate('Delivery type') }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ translate($order->delivery_type) }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin:8px 8px 15px 8px; clear:both">
            <div style="padding:10px 14px; border:1px solid #DEDEDE;border-radius:3px;width:45%;float:left;">
                <table class="">
                    <tbody>
                        @php
                            $billing_address = json_decode($order->billing_address);
                        @endphp
                        <tr>
                            <td class="bold">{{ translate('Billing address') }}:</td>
                        </tr>
                        <tr>
                            <td class="">{{ $billing_address->address }},
                                {{ $billing_address->postal_code }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ $billing_address->city }},
                                {{ $billing_address->state }}, {{ $billing_address->country }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ translate('Phone') }}: {{ $billing_address->phone }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="padding:10px 14px; border:1px solid #DEDEDE;border-radius:3px;width:45%;float:right">
                <table class="text-right">
                    <tbody>
                        @php
                            $shipping_address = json_decode($order->shipping_address);
                        @endphp
                        <tr>
                            <td class="bold">{{ translate('Shipping address') }}:</td>
                        </tr>
                        <tr>
                            <td class="">{{ $shipping_address->address }},
                                {{ $shipping_address->postal_code }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ $shipping_address->city }},
                                {{ $shipping_address->state }}, {{ $shipping_address->country }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ translate('Phone') }}: {{ $shipping_address->phone }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin:0 8px;border:1px solid #DEDEDE;border-radius:3px;padding:0 7px">
            <table class="padding">
                <thead>
                    <tr>
                        <td width="5%" class="text-left bold">{{ translate('S/L') }}</td>
                        <td width="45%" class="text-left bold">{{ translate('Product Name') }}</td>
                        <td width="13%" class="text-left bold">{{ translate('Qty') }}</td>
                        <td width="15%" class="text-left bold">{{ translate('Unit Price') }}</td>
                        <td width="10%" class="text-left bold">{{ translate('Unit Tax') }}</td>
                        <td width="12%" class="text-right bold">{{ translate('Total') }}</td>
                    </tr>
                </thead>
            </table>
        </div>
        <div style="margin:8px;">
            <table class="lg-padding" style="border-collapse: collapse">
                <tr>
                    <th width="6%" class="text-left"></th>
                    <th width="44%" class="text-left"></th>
                    <th width="13%" class="text-left"></th>
                    <th width="15%" class="text-left"></th>
                    <th width="9%" class="text-left"></th>
                    <th width="14%" class="text-right"></th>
                </tr>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->product != null)
                            <tr>
                                <td style="border-bottom:1px solid #DEDEDE;padding-left:20px">{{ $key + 1 }}</td>
                                <td style="border-bottom:1px solid #DEDEDE;">
                                    <span style="display: block">{{ $orderDetail->product->name }}</span>
                                    @if ($orderDetail->variation && $orderDetail->variation->combinations->count() > 0)
                                        @foreach ($orderDetail->variation->combinations as $combination)
                                            <span style="margin-right:10px">
                                                <span
                                                    class="">{{ optional($combination->attribute)->getTranslation('name') }}</span>:
                                                <span>{{ optional($combination->attribute_value)->getTranslation('name') }}</span>
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="" style="border-bottom:1px solid #DEDEDE;">
                                    {{ $orderDetail->quantity }}</td>
                                <td class="" style="border-bottom:1px solid #DEDEDE;">
                                    {{ format_price($orderDetail->price) }}</td>
                                <td class="" style="border-bottom:1px solid #DEDEDE;">
                                    {{ format_price($orderDetail->tax) }}</td>
                                <td class="text-right bold" style="border-bottom:1px solid #DEDEDE;padding-right:20px;">
                                    {{ format_price($orderDetail->total) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin:15px 8px;clear:both">
            <div style="float: left; width:43%;padding:14px 20px;">
                @if ($order->payment_status == 'paid')
                    <div class="mt-5">
                        <img src="{{ static_asset('assets/img/paid_sticker.svg') }}">
                    </div>
                @elseif($order->payment_type == 'cash_on_delivery')
                    <div class="mt-5">
                        <img src="{{ static_asset('assets/img/cod_sticker.svg') }}">
                    </div>
                @endif
            </div>
            <div style="float: right; width:43%;padding:14px 20px; border:1px solid #DEDEDE;border-radius:3px;">
                <table class="text-right sm-padding" style="border-collapse:collapse">
                    <tbody>
                        @php
                            $totalTax = 0;
                            foreach ($order->orderDetails as $item) {
                                $totalTax += $item->tax * $item->quantity;
                            }
                        @endphp
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ translate('Sub Total') }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ format_price($order->orderDetails->sum('total') - $totalTax) }}</td>
                        </tr>
                        <tr class="">
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ translate('Total Tax') }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ format_price($totalTax) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ translate('Shipping Cost') }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ format_price($order->shipping_cost) }}</td>
                        </tr>
                        <tr class="">
                            <td class="text-left" style="border-bottom:1px solid #DEDEDE">
                                {{ translate('Coupon Discount') }}</td>
                            <td class="bold" style="border-bottom:1px solid #DEDEDE">
                                {{ format_price($order->coupon_discount) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left bold">{{ translate('Grand Total') }}</td>
                            <td class="bold">{{ format_price($order->grand_total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="{{ asset('assets/frontend/js/jquery.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('assets/frontend/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        function closeScript() {
            setTimeout(function () {
                window.open(window.location, '_self').close();
            }, 1000);
        }

        $(window).on('load', function () {
            var element = document.getElementById('boxes');
            var opt = {
                filename: 'dsdsds',
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A4'}
            };
            html2pdf().set(opt).from(element).save().then(closeScript);
        });

    </script>

</body>

</html>

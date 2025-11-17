@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" id="sort_orders" action="{{ route('bulks.index') }}" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
                </div>

                <div class="col-xl-2 col-md-3">
                    <select class="form-control aiz-selectpicker" name="status" onchange="sort_orders()"
                            data-selected="{{ $status }}">
                        <option value="">{{ translate('Filter by Status') }}</option>
                        <option value="1">{{ translate('Contacted') }}</option>
                        <option value="0">{{ translate('Waiting') }}</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search"
                               @isset($sort_search) value="{{ $sort_search }}" @endisset
                               placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search_column" name="search_column"
                               @isset($search_column) value="{{ $search_column }}" @endisset
                               placeholder="{{ translate('Type (order bulk code , product Name , Email Address , phone , notes , quantity) & hit Enter') }}">
                    </div>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Order Bulk Code') }}</th>
                    <th data-breakpoints="lg">{{ translate('Product Name') }}</th>
                    <th data-breakpoints="lg">{{ translate('Email Address') }}</th>
                    <th data-breakpoints="lg">{{ translate('Phone') }}</th>
                    <th data-breakpoints="lg">{{ translate('Notes') }}</th>
                    <th>{{ translate('Quantity') }}</th>
                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th data-breakpoints="lg" class="text-right" width="15%">{{ translate('options') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bulks as $key => $bulk)
                    <tr>
                        <td>
                            {{ $key + 1 + ($bulks->currentPage() - 1) * $bulks->perPage() }}
                        </td>
                        <td>
                            {{ $bulk->id }}
                        </td>
                        <td>
                            {{$bulk->product ? $bulk->product->name : 'Removed'}}
                        </td>
                        <td>
                            {{ $bulk->email }}
                        </td>
                        <td>
                            {{ $bulk->phone }}
                        </td>
                        <td>
                            {{ $bulk->notes }}
                        </td>
                        <td>
                            {{ $bulk->quantity }}
                        </td>

                        <td>
                            @if ($bulk->status == 1)
                                <span class="badge badge-inline badge-success">{{ translate('Contacted') }}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{ translate('Waiting') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
{{--                            @can('view_bulks')--}}
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="{{ route('bulks.show', $bulk) }}" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
{{--                            @endcan--}}
{{--                            @can('delete_bulks')--}}
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                   data-href="{{ route('bulks.destroy', $bulk) }}"
                                   title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
{{--                            @endcan--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $bulks->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el) {
            $('#sort_orders').submit();
        }

        function print_invoice(url) {
            var h = $(window).height();
            var w = $(window).width();
            window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
        }
    </script>
@endsection

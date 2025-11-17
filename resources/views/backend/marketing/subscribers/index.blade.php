@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('All Subscribers')}}</h5>
    </div>
    <div class="pull-right clearfix">
        <form class="" id="sort_offers" action="" method="GET">
            <div class="form-inline pad-rgt pull-left">
                <div class="">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search_column" name="search_column"
                               @isset($search_column) value="{{ $search_column }}" @endisset
                               placeholder="{{ translate('Type 1 (Email ) & hit Enter') }}">
                    </div>
                </div>
                <div class="">
                    <div class="input-group">
                        <input type="date" class="form-control" id="search_date" name="search_date"
                               @isset($search_date) value="{{ $search_date }}" @endisset
                               placeholder="{{ translate('Type 1 (Date ) & hit Enter') }}">
                    </div>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Date')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscribers as $key => $subscriber)
                  <tr>
                      <td>{{ ($key+1) + ($subscribers->currentPage() - 1)*$subscribers->perPage() }}</td>
											<td>{{ $subscriber->email }}</td>
                      <td>{{ date('d-m-Y', strtotime($subscriber->created_at)) }}</td>
                  </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $subscribers->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

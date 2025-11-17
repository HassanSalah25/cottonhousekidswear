@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All contacts') }}</h1>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('contacts') }}</h5>
            <div class="pull-right clearfix">
                <form class="" id="sort_contacts" action="" method="GET">
                    <div class="form-inline pad-rgt pull-left">
                        <div class="" style="min-width: 200px;">
                            <input type="text" class="form-control" id="search" name="search"
                                   @isset($sort_search) value="{{ $sort_search }}" @endisset
                                   placeholder="{{ translate('Type email or name & Enter') }}">
                        </div>
                        <div class="">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search_column" name="search_column"
                                       @isset($search_column) value="{{ $search_column }}" @endisset
                                       placeholder="{{ translate('Type 1 (Subject, Status, Message) & hit Enter') }}">
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
        </div>
        <div class="card-body">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th data-breakpoints="lg">{{ translate('First Name') }}</th>
                    <th data-breakpoints="lg">{{ translate('Last Name') }}</th>
                    <th data-breakpoints="lg">{{ translate('Email Address') }}</th>
                    <th data-breakpoints="lg">{{ translate('Subject') }}</th>
                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th data-breakpoints="lg">{{ translate('Message') }}</th>
                    <th class="text-right" data-breakpoints="lg">{{ translate('Options') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($contacts as $key => $user)
                    <tr>
                        <td>{{ $key + 1 + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->subject }}</td>
                        <td>{{ Str::limit($user->message,700) }}</td>
                        <td>
                            @if($user->status == 0)
                                <div>
                                    <span class="bg-primary rounded p-1 font-weight-bold text-light">
                                        {{ translate('Waiting') }}
                                    </span>
                                </div>
                            @else
                                <div>
                                    <span class="bg-success rounded p-1 font-weight-bold text-light">
                                        {{ translate('Contacted') }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="text-right">
                            @can('view_contacts')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="{{ route('contacts.show', $user->id) }}" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endcan
                          {{--  @can('delete_contacts')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                   data-href="{{ route('contacts.destroy', $user->id) }}"
                                   title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $contacts->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_contacts(el) {
            $('#sort_contacts').submit();
        }

        function confirm_ban(url) {
            $('#confirm-ban').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('confirmation').setAttribute('href', url);
        }

        function confirm_unban(url) {
            $('#confirm-unban').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('confirmationunban').setAttribute('href', url);
        }
    </script>
@endsection

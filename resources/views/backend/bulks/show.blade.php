@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Bulk Purchase Information') }}</h5>
            </div>
            <div class="card-body">
                    <form class="container-fluid no-gutters d-flex flex-nowrap justify-content-between flex-column"
                          method="POST" action="{{ route('bulks.update',$bulkPurchase) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="product_id" value="{{$bulkPurchase->product_id}}">
                        <div class="my-2">
                            <label class="label">{{translate('Email Address')}}</label>
                            <input type="email" class="form-control"
                                   name="email"
                                   value="{{$bulkPurchase->email}}"
                                   placeholder="{{translate('Enter Your Email Address')}}">

                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Phone')}}</label>
                            <input type="number" class="form-control"
                                   name="phone"
                                   value="{{ $bulkPurchase->phone }}"
                                   placeholder="{{translate('Enter Your Phone')}}">
                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Quantity')}}</label>
                            <input type="number" class="form-control" value="{{ $bulkPurchase->quantity }}" name="quantity"
                                   placeholder="{{translate('Enter Your Quantity')}}">
                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Notes')}}</label>
                            <textarea type="number" class="form-control" name="notes"
                                      placeholder="{{translate('Enter Your Notes')}}">{{$bulkPurchase->notes}}</textarea>
                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Status')}}</label>
                            <select class="select2 form-control aiz-selectpicker" name="status" data-toggle="select2"
                                    data-placeholder="Choose ...">
                                <option value="0">{{ translate('Choose Status') }}</option>
                                    <option value="1" {{1 == $bulkPurchase->status ? 'selected' : ''}}>{{ translate('Contacted') }}
                                    </option>
                                    <option value="0" {{0 == $bulkPurchase->status ? 'selected' : ''}}>{{ translate('Waiting') }}
                                    </option>
                            </select>
                        </div>
                        <div class="my-2">
                            <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        @foreach($errors->getMessageBag()->all() as $key => $message)
        AIZ.plugins.notify('danger', '{{ $message }}');
        @endforeach
    </script>
@endsection

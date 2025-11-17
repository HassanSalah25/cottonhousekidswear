@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="separator text-left"><span
                            class="bg-white pr-3">{{ translate('Person Information') }}</span></h6>
                </div>
                <div class="card-body text-center">
                    <h1 class="h5 mb-1">{{ $contact->name }}</h1>
                    <div class="text-left ">
                        <p class="text-muted">
                            <strong>{{ translate('First Name') }} :</strong>
                            <span class="ml-2">{{ $contact->first_name }}</span>
                        </p>
                        <p class="text-muted">
                            <strong>{{ translate('Last Name') }} :</strong>
                            <span class="ml-2">{{ $contact->last_name }}</span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Email') }} :</strong>
                            <span class="ml-2">
                                {{ $contact->email }}
                            </span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Date') }} :</strong>
                            <span class="ml-2">
                                {{ $contact->created_at }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            @if($contact->status == 0)
                <div>
                    <a href="{{ route('contacts.change_status',$contact) }}" class="btn btn-primary">
                        {{ translate('Waiting') }}
                    </a>
                </div>
            @else
                <div>
                    <a href="{{ route('contacts.change_status',$contact) }}" class="btn btn-success">
                        {{ translate('Contacted') }}
                    </a>
                </div>
            @endif
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    {{ translate('Message') }}
                </div>
                <div class="card-body">
                    <textarea class="form-control" rows="10" readonly>{{ $contact->message }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection

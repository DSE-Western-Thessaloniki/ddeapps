@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Mailmerge') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('Mail merge!') }}
                    <ul>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('apps.mailmerge.doclogo.index') }}">{{ __('Logos') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('apps.mailmerge.docaddress.index') }}">{{ __('Addresses') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('apps.mailmerge.create') }}">{{ __('New mail merge') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Exact copies') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                    </div><br />
                    @endif

                    <div class="row">
                        <div class="col-3">{{ __('Title') }}</div>
                        <div class="col-9 align-self-center">
                            {{ $exactcopy->title }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">{{ __('Text') }}</div>
                        <div class="col-9 align-self-center">
                            <pre>{{ $exactcopy->text }}</pre>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">{{ __('Active') }}</div>
                        <div class="col-9 align-self-center">
                            @if ($exactcopy->active)
                                {{ __('True') }}
                            @else
                                {{ __('False') }}
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.exactcopy.index') }}">{{ __('Back') }}</a>
                        </div>
                        <div class="col-10 d-flex justify-content-end">
                            <a class="btn btn-primary" href="{{ route('apps.mailmerge.exactcopy.edit', $exactcopy->id)}}">{{ __('Edit') }}</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

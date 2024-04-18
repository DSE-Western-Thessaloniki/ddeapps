@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Logos') }}</div>

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

                    <form method="post" action="{{ route('apps.mailmerge.doclogo.store') }}">

                        <doclogoform imagespath="{{ env('APP_URL').'/images/' }}"></doclogoform>
                        <div class="form-group row">
                            <div class="col-2">
                                <a class="btn btn-danger" href="{{ route('apps.mailmerge.doclogo.index') }}">{{ __('Cancel')}}</a>
                            </div>
                            <div class="col-10 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('Save')}}</button>
                            </div>
                        </div>

                        @csrf
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

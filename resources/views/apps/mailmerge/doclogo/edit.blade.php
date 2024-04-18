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

                    <form method="post" action="{{ route('apps.mailmerge.doclogo.update', $doclogo->id) }}">

                        <doclogoform
                            title="{{ $doclogo->title }}"
                            logofile="{{ $doclogo->image}}"
                            text="{{ $doclogo->text}}"
                            :active="{{ json_encode($doclogo->active) }}"
                            imagespath="{{ env('APP_URL').(str_ends_with(env('APP_URL'), '/') ? 'images/' : '/images/') }}"
                        >
                        </doclogoform>

                        <div class="form-group row mb-3">
                            <div class="col-2">
                                <a class="btn btn-danger" href="{{ route('apps.mailmerge.doclogo.index') }}">{{ __('Cancel') }}</a>
                            </div>
                            <div class="col-10 d-flex justify-content-end">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
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

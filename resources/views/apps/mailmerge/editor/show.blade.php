@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Editor') }}</div>

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
                            {{ $editor->title }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">{{ __('Address') }}</div>
                        <div class="col-9 align-self-center">
                            {{ $editor->address }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">{{ __('Name') }}</div>
                        <div class="col-9 align-self-center">
                            {{ $editor->name }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">{{ __('Telephone') }}</div>
                        <div class="col-9 align-self-center">
                            {{ $editor->telephone }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">{{ __('Email') }}</div>
                        <div class="col-9 align-self-center">
                            {{ $editor->email }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.editor.index') }}">{{ __('Back') }}</a>
                        </div>
                        <div class="col-10 d-flex justify-content-end">
                            @can('update', $editor)
                            <a class="btn btn-primary" href="{{ route('apps.mailmerge.editor.edit', $editor->id)}}">{{ __('Edit') }}</a>
                            @endcan
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

                    {!! Form::open(['action' => ['DocLogoController@update', $doclogo->id],
                    'method' => 'POST']) !!}

                    <doclogoform
                        title="{{ $doclogo->title }}"
                        logofile="{{ $doclogo->image}}"
                        text="{{ $doclogo->text}}"
                        active="{{ $doclogo->active}}"
                    >
                    </doclogoform>

                    <div class="form-group row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.doclogo.index') }}">{{ __('Cancel') }}</a>
                        </div>
                        <div class="col-10 d-flex justify-content-end">
                            {{Form::hidden('_method', 'PUT')}}
                            {{Form::submit(__('Save'), ['class' => 'btn btn-primary'])}}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

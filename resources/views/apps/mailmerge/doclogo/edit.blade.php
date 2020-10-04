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

                    <div class="form-group row">
                        <label for="doclogotitle" class="col-3 col-form-label">{{ __('Title') }}</label>
                        <div class="col-9 align-self-center">
                            <input type="text" class="form-control" name="doclogotitle" id="doclogotitle" value="{{ $doclogo->title }}" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="image" class="col-3 col-form-label">{{ __('Image') }}</label>
                        <div class="col-9 align-self-center">
                        <input type="text" class="form-control" name="image" id="image" value="{{ $doclogo->image }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text" class="col-3 col-form-label">{{ __('Text') }}</label>
                        <div class="col-9 align-self-center">
                        <textarea class="form-control text-center" name="doclogotext" id="text" rows="10">{{ $doclogo->text }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="active" class="col-3 col-form-label">{{ __('Active') }}</label>
                        <div class="col-9">
                            <div class="form-check">
                                @if ($doclogo->active)
                                <input type="checkbox" class="form-check-input" name="active" id="active" value="1" checked="checked">
                                @else
                                <input type="checkbox" class="form-check-input" name="active" id="active" value="1">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.doclogo.index') }}">{{ __('Cancel') }}</a>
                        </div>
                        <div class="col-md-10 d-flex justify-content-end">
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

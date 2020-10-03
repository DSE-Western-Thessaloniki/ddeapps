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

                    {!! Form::open(['action' => 'DocLogoController@store',
                    'method' => 'POST']) !!}

                    <div class="form-group row">
                        <label for="title" class="col-3 col-form-label">{{ __('Title') }}</label>
                        <div class="col-9 align-self-center">
                            <input type="text" class="form-control" name="doclogotitle">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="title" class="col-3 col-form-label">{{ __('Image') }}</label>
                        <div class="col-9 align-self-center">
                            <input type="text" class="form-control" name="image">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="title" class="col-3 col-form-label">{{ __('Text') }}</label>
                        <div class="col-9 align-self-center">
                            <textarea class="form-control text-center" name="doclogotext" rows="10"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3">{{ __('Active') }}</div>
                        <div class="col-9">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="active" value="1" checked="checked">
                                <label class="form-check-label" for="active">{{ __('Active logo') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10 d-flex justify-content-end">
                        {{Form::submit(__('Save'), ['class' => 'btn btn-primary'])}}
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

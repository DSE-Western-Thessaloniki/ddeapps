@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Recipients') }}</div>

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

                    {!! Form::open(['action' => 'MailMerge\RecipientController@store',
                    'method' => 'POST']) !!}

                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="code">{{ __('Code') }}</label>
                        <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}" required>
                    </div>
                    <div class="form-group row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.recipient.index') }}">{{ __('Cancel')}}</a>
                        </div>
                        <div class="col-10 d-flex justify-content-end">
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

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
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

                    {!! Form::open(['action' => ['MailMerge\RecipientController@update', $recipient->id],
                    'method' => 'POST']) !!}

                    <div class="form-group mb-3">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{$recipient->name}}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="code">{{ __('Text') }}</label>
                        <input type="text" id="code" name="code" class="form-control" value="{{$recipient->code}}" required>
                    </div>

                    <recipientlinks
                        links="{{ $recipient->linksJson() }}"
                    >
                    </recipientlinks>

                    <div class="form-group row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.recipient.index') }}">{{ __('Cancel') }}</a>
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

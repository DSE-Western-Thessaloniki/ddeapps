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

                    {!! Form::open(['action' => ['ExactCopyController@update', $exactcopy->id],
                    'method' => 'POST']) !!}

                    <div class="form-group">
                        <label for="title">{{ __('Title') }}</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{$exactcopy->title}}" required>
                    </div>
                    <div class="form-group">
                        <label for="text">{{ __('Text') }}</label>
                        <textarea id="text" name="text" rows="10" class="form-control text-center">{{$exactcopy->text}}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            @if ($exactcopy->active)
                                <input type="checkbox" class="form-check-input" name="active" id="active" value="1" checked="checked">
                            @else
                                <input type="checkbox" class="form-check-input" name="active" id="active" value="1">
                            @endif
                            <label for="active" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="{{ route('apps.mailmerge.exactcopy.index') }}">{{ __('Cancel') }}</a>
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

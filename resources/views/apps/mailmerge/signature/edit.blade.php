@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Signatures') }}</div>

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

                    <form method="post" action="{{ route('apps.mailmerge.signature.update', $signature->id) }}">

                        <div class="form-group mb-3">
                            <label for="title">{{ __('Title') }}</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{$signature->title}}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="text">{{ __('Text') }}</label>
                            <textarea id="text" name="text" rows="10" class="form-control text-center">{{$signature->text}}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                @if ($signature->active)
                                    <input type="checkbox" class="form-check-input" name="active" id="active" value="1" checked="checked">
                                @else
                                    <input type="checkbox" class="form-check-input" name="active" id="active" value="1">
                                @endif
                                <label for="active" class="form-check-label">{{ __('Active') }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-2">
                                <a class="btn btn-danger" href="{{ route('apps.mailmerge.signature.index') }}">{{ __('Cancel') }}</a>
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

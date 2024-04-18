@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
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

                    <form method="post" action="{{ route('apps.mailmerge.editor.store') }}">

                        <div class="form-group mb-3">
                            <label for="title">{{ __('Title') }}</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">{{ __('Address') }}</label>
                            <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="telephone">{{ __('Telephone') }}</label>
                            <input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <a class="btn btn-danger" href="{{ route('apps.mailmerge.editor.index') }}">{{ __('Cancel')}}</a>
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

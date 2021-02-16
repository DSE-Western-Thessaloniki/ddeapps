@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Admin') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a class="btn btn-light admin-button" href="{{ route('admin.user.index') }}">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4">
                                    <h1 class="display-3"><i class="fa fa-users"></i></h1>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Users</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a class="btn btn-light admin-button">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4">
                                    <h1 class="display-3"><i class="fa fa-cogs"></i></h1>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Options</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

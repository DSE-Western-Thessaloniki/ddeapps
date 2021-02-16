@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Users') }}</div>

                @if(Auth::user()->isAdministrator())
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

                    <table class="table table-striped">
                        <tr>
                            <td>{{ __('Username') }}</td>
                            <td class="text-center">
                                {{ $user->username }}
                            </td>
                        </tr>

                        <tr>
                            <td>{{ __('Name') }}</td>
                            <td class="text-center">
                                {{ $user->name }}
                            </td>
                        </tr>

                        <tr>
                            <td>{{ __('E-mail') }}</td>
                            <td class="text-center">
                                <pre>{{ $user->email }}</pre>
                            </td>
                        </tr>

                        <tr>
                            <td>{{ __('Active') }}</td>
                            <td class="text-center">
                                @if ($user->active)
                                    {{ __('True') }}
                                @else
                                    {{ __('False') }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="col-2">
                                <a class="btn btn-danger" href="{{ route('admin.user.index') }}">{{ __('Back') }}</a>
                            </td>
                            <td class="col-10 d-flex justify-content-end">
                                <a class="btn btn-primary" href="{{ route('admin.user.edit', $user->id)}}">{{ __('Edit') }}</a>
                            </td>
                        </tr>
                    </table>
                </div>
                @else
                    {{ __('Access denied') }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

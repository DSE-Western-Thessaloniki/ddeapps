@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Addresses') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="btn-toolbar pb-2" role="toolbar">
                        <div class="btn-group mr-2" role="group">
                        <a class="btn btn-dark" href="{{ route('apps.mailmerge.index')}}">
                            @icon('arrow-circle-left') Back
                        </a>
                        </div>
                        <div class="btn-group" role="group">
                        <a class="btn btn-primary mr-2" href="{{ route('apps.mailmerge.docaddress.create')}}">
                        @icon('plus-circle') {{ __('New Address') }}
                        </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Telephone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($docaddresses as $docaddress)
                                <tr>
                                    <td>{{$docaddress->id}}</td>
                                    <td><a href="{{ route('apps.mailmerge.docaddress.show', $docaddress->id) }}">{{$docaddress->title}}</a></td>
                                    <td>{{$docaddress->address}}</td>
                                    <td>{{$docaddress->name}}</td>
                                    <td>{{$docaddress->telephone}}</td>
                                    <td>{{$docaddress->email}}</td>
                                    <td>
                                        <a href="{{ route('apps.mailmerge.docaddress.edit',$docaddress->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('apps.mailmerge.docaddress.destroy', $docaddress->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No address available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
                        <div class="btn-group" role="group">
                            @can('create', DocAddress::class)
                            <a class="btn btn-primary mr-2" href="{{ route('apps.mailmerge.docaddress.create')}}">
                                @icon('plus-circle') {{ __('New Address') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Telephone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Created by') }}</th>
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
                                    <td>{{$docaddress->creator->name}}</td>
                                    <td>
                                        @can('update', $docaddress)
                                        <a href="{{ route('apps.mailmerge.docaddress.edit',$docaddress->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('delete', $docaddress)
                                        <form action="{{ route('apps.mailmerge.docaddress.destroy', $docaddress->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                        @endcan
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

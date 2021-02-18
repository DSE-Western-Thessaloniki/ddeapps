@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Signatures') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="btn-toolbar pb-2" role="toolbar">
                        <div class="btn-group" role="group">
                            @can('create', Signature::class)
                            <a class="btn btn-primary mr-2" href="{{ route('apps.mailmerge.signature.create')}}">
                            @icon('plus-circle') {{ __('New Signature') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Text') }}</th>
                                    <th>{{ __('Created by') }}</th>
                                    <th>{{ __('Active') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($signatures as $signature)
                                <tr>
                                    <td>{{$signature->id}}</td>
                                    <td><a href="{{ route('apps.mailmerge.signature.show', $signature->id) }}">{{$signature->title}}</a></td>
                                    <td><pre class="text-center">{{$signature->text}}</pre></td>
                                    <td>{{$signature->creator->name}}</td>
                                    @if($signature->active)
                                        <td class="text-center text-success">
                                            @icon('check')
                                        </td>
                                    @else
                                        <td class="text-center text-danger">
                                            @icon('times')
                                        </td>
                                    @endif

                                    <td>
                                        @can('update', $signature)
                                        <a href="{{ route('apps.mailmerge.signature.edit',$signature->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('delete', $signature)
                                        <form action="{{ route('apps.mailmerge.signature.destroy', $signature->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No signatures available</td>
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

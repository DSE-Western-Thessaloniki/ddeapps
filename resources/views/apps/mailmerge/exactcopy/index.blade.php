@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Exact copies') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="btn-toolbar pb-2" role="toolbar">
                        <div class="btn-group mr-2" role="group">
                        <a class="btn btn-dark" href="{{ route('apps.mailmerge.index')}}">
                            @icon('arrow-circle-left') {{ __('Back') }}
                        </a>
                        </div>
                        <div class="btn-group" role="group">
                        <a class="btn btn-primary mr-2" href="{{ route('apps.mailmerge.exactcopy.create')}}">
                        @icon('plus-circle') {{ __('New Exact Copy') }}
                        </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Text') }}</th>
                                    <th>{{ __('Active') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exactcopies as $exactcopy)
                                <tr>
                                    <td>{{$exactcopy->id}}</td>
                                    <td><a href="{{ route('apps.mailmerge.exactcopy.show', $exactcopy->id) }}">{{$exactcopy->title}}</a></td>
                                    <td><pre class="text-center">{{$exactcopy->text}}</pre></td>
                                    @if($exactcopy->active)
                                        <td class="text-center text-success">
                                            @icon('check')
                                        </td>
                                    @else
                                        <td class="text-center text-danger">
                                            @icon('times')
                                        </td>
                                    @endif

                                    <td>
                                        <a href="{{ route('apps.mailmerge.exactcopy.edit',$exactcopy->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('apps.mailmerge.exactcopy.destroy', $exactcopy->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No exact copies available</td>
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

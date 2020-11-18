@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Logos') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="btn-toolbar pb-2" role="toolbar">
                        <div class="btn-group mr-2">
                            <a class="btn btn-primary" href="{{ route('apps.mailmerge.doclogo.create')}}">
                            @icon('plus-circle') {{ __('New Logo') }}
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Text') }}</th>
                                    <th>{{ __('Active') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doclogos as $doclogo)
                                <tr>
                                    <td>{{$doclogo->id}}</td>
                                    <td><a href="{{ route('apps.mailmerge.doclogo.show', $doclogo->id) }}">{{$doclogo->title}}</a></td>
                                    <td>{{$doclogo->image}}</td>
                                    <td><pre class="text-center">{{$doclogo->text}}</pre></td>
                                    @if($doclogo->active)
                                        <td class="text-center text-success">
                                            @icon('check')
                                        </td>
                                    @else
                                        <td class="text-center text-danger">
                                            @icon('times')
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{ route('apps.mailmerge.doclogo.edit',$doclogo->id)}}" class="btn btn-primary">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('apps.mailmerge.doclogo.destroy', $doclogo->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No logo available</td>
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

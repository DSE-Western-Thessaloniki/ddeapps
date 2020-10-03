@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Logos') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                        <a class="btn btn-primary" href="{{ route('apps.mailmerge.doclogo.create')}}">
                            @icon('plus-circle') New Logo
                        </a>
                        </li>
                        <li class="list-group-item">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Text</th>
                                    <th>Active</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doclogos as $doclogo)
                                <tr>
                                    <td>{{$doclogo->id}}</td>
                                    <td>{{$doclogo->title}}</td>
                                    <td>{{$doclogo->image}}</td>
                                    <td>{{$doclogo->text}}</td>
                                    <td>{{$doclogo->active}}</td>
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
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

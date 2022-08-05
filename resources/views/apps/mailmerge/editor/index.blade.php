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
                            @can('create', \App\Models\MailMerge\Editor::class)
                            <a class="btn btn-primary mr-2" href="{{ route('apps.mailmerge.editor.create')}}">
                                @icon('plus-circle') {{ __('New Editor') }}
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
                                @forelse($editors as $editor)
                                <tr>
                                    <td>{{$editor->id}}</td>
                                    <td><a href="{{ route('apps.mailmerge.editor.show', $editor->id) }}">{{$editor->title}}</a></td>
                                    <td>{{$editor->address}}</td>
                                    <td>{{$editor->name}}</td>
                                    <td>{{$editor->telephone}}</td>
                                    <td>{{$editor->email}}</td>
                                    <td>{{$editor->creator->name}}</td>
                                    <td>
                                        @can('update', $editor)
                                        <a href="{{ route('apps.mailmerge.editor.edit',$editor->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('delete', $editor)
                                        <form action="{{ route('apps.mailmerge.editor.destroy', $editor->id)}}" method="post">
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

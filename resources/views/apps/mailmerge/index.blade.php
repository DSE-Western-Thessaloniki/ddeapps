@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Mail merge') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="btn-toolbar pb-2" role="toolbar">
                        <div class="btn-group" role="group">
                            @can('create', MailMerge::class)
                            <a class="btn btn-primary" href="{{ route('apps.mailmerge.create') }}">
                                @icon('plus-circle') {{ __('New Mail Merge') }}
                            </a>
                            @endcan
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Protocol number') }}</th>
                                    <th>{{ __('Subject') }}</th>
                                    <th>{{ __('Created by') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mailmerges as $mailmerge)
                                <tr>
                                    <td>{{$mailmerge->id}}</td>
                                    <td><a href="{{ route('apps.mailmerge.show', $mailmerge->id) }}">{{$mailmerge->protocol_num}}/{{$mailmerge->date}}</a></td>
                                    <td>{{$mailmerge->subject}}</td>
                                    <td>{{$mailmerge->creator->name}}</td>

                                    <td>
                                        @can('update', $mailmerge)
                                        <a href="{{ route('apps.mailmerge.edit',$mailmerge->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('delete', $mailmerge)
                                        <form action="{{ route('apps.mailmerge.destroy', $mailmerge->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">{{ __('No mail merge available') }}</td>
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

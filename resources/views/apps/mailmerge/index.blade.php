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

                    <div class="btn-toolbar pb-2 justify-content-between" role="toolbar">
                        <div class="btn-group" role="group">
                            @can('create', \App\Models\MailMerge\MailMerge::class)
                            <a class="btn btn-primary" href="{{ route('apps.mailmerge.create') }}">
                                @icon('plus-circle') {{ __('New Mail Merge') }}
                            </a>
                            @endcan
                        </div>
                        <form class="form-horizontal" id="search" method="GET" action="{{ route('apps.mailmerge.index') }}">
                            <div class="input-group" role="group">
                                <input type="text" class="form-control" placeholder="Κριτήρια αναζήτησης..."name="filter" value="{{ $filter }}">
                                <button type="submit" class="btn btn-primary ml-2" form="search">Αναζήτηση</button>
                            </div>
                        </form>
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
                                        <a href="{{ route('apps.mailmerge.edit',$mailmerge->id)}}" class="btn btn-primary m-1">@icon('pencil-alt') {{ __('Edit') }}</a><br/>
                                        @endcan
                                        @can('create', \App\Models\MailMerge\MailMerge::class)
                                        <a href="{{ route('apps.mailmerge.copy',$mailmerge->id)}}" class="btn btn-primary m-1">@icon('copy') {{ __('Copy') }}</a><br/>
                                        @endcan
                                        <a href="{{ route('apps.mailmerge.print',$mailmerge->id)}}" target="_blank" class="btn btn-success m-1">@icon('print') {{ __('Print') }}</a>
                                    </td>
                                    <td>
                                        @can('delete', $mailmerge)
                                        <a href="{{ route('apps.mailmerge.confirm_delete', $mailmerge->id)}}" class="btn btn-danger">@icon('trash-alt') {{ __('Delete') }}</a>
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
                    {{ $mailmerges->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Recipients') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="btn-toolbar pb-2" role="toolbar">
                        <div class="btn-group" role="group">
                            @can('create', \App\Models\MailMerge\Recipient::class)
                            <a class="btn btn-primary mr-2" href="{{ route('apps.mailmerge.recipient.create')}}">
                                @icon('plus-circle') {{ __('New Recipient') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Aliases') }}</th>
                                    <th>{{ __('Created by') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recipients as $recipient)
                                <tr>
                                    <td>{{$recipient->id}}</td>
                                    <td>{{$recipient->name}}</td>
                                    <td>{{$recipient->code}}</td>
                                    <td>{{$recipient->links()}}</td>
                                    <td>{{$recipient->creator->name}}</td>
                                    <td>
                                        @can('update', $recipient)
                                        <a href="{{ route('apps.mailmerge.recipient.edit',$recipient->id)}}" class="btn btn-primary">{{ __('Edit') }}</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('delete', $recipient)
                                        <form action="{{ route('apps.mailmerge.recipient.destroy', $recipient->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No recipients available</td>
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

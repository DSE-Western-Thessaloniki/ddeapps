@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Mailmerge') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('Create Mail merge!') }}
                    {!! Form::open(['action' => 'MailMergeController@store',
                                    'method' => 'POST']) !!}
                        <mailmerge-component></mailmerge-component>

                        <br/>
                        <div class="col-md-10 d-flex justify-content-end">
                            {{Form::submit(__('Save'), ['class' => 'btn btn-primary'])}}
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

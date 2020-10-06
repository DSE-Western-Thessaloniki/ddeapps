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

                        <!--<mailmerge-component></mailmerge-component>-->

                        <div class="form-group">
                            <label for="logoselect">Logo:</label>
                            <select class="form-control" id="logoselect" name="logoselect">
                                @foreach($logos as $logo)
                                    <option value="{{ $logo->id }}">{{ $logo->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="docsubj">Subject:</label>
                            <textarea id="docsubj" name="docsubj" class="form-control">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="doctext">Text:</label>
                            <textarea id="doctext" name="doctext" class="form-control" rows="10">
                            </textarea>
                        </div>

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

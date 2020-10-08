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
                    @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                    </div><br />
                    @endif

                    {!! Form::open(['action' => 'MailMergeController@store',
                                    'method' => 'POST']) !!}

                        <!--<mailmerge-component></mailmerge-component>-->

                        @empty($doc_logos->toArray())
                        <div class="alert alert-danger">
                            <ul>
                            <li>{{ __('Cannot continue without creating a logo') }}</li>
                            </ul>
                        </div><br />
                        <a class="btn btn-dark" href="{{ route('apps.mailmerge.index')}}">@icon('arrow-circle-left') {{ __('Back') }}</a>
                        <a class="btn btn-primary" href="{{ route('apps.mailmerge.doclogo.create')}}">@icon('plus-circle') {{ __('Create Logo') }}</a>
                        @else
                        <div class="form-group">
                            <label for="logoselect">Logo:</label>
                            <select class="form-control" id="logoselect" name="logoselect">
                                @foreach($doc_logos as $logo)
                                    <option value="{{ $logo->id }}">{{ $logo->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="protocol">{{ __('Protocol number:') }}</label>
                                    <input type="text" id="protocol" name="protocol" class="form-control">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="date">{{ __('Date:') }}</label>
                                    <input type="date" id="date" name="date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <textarea id="subject" name="subject" class="form-control">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="text">Text:</label>
                            <textarea id="text" name="text" class="form-control" rows="10">
                            </textarea>
                        </div>

                        <br/>
                        <div class="form-group row">
                            <div class="col-2">
                                <a class="btn btn-danger" href="{{ route('apps.mailmerge.index')}}">{{ __('Cancel') }}</a>
                            </div>
                            <div class="col-10 d-flex justify-content-end">
                                {{Form::submit(__('Save'), ['class' => 'btn btn-primary'])}}
                            </div>
                        </div>
                        @endempty
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

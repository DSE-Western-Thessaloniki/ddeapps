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
                        <a class="btn btn-primary" href="{{ route('apps.mailmerge.doclogo.create')}}">@icon('plus-circle') {{ __('Create Logo') }}</a>
                        @else
                            @empty($doc_addresses->toArray())
                                <div class="alert alert-danger">
                                    <ul>
                                    <li>{{ __('Cannot continue without creating an address.') }}</li>
                                    </ul>
                                </div><br />
                                <a class="btn btn-primary" href="{{ route('apps.mailmerge.docaddress.create')}}">@icon('plus-circle') {{ __('Create Address') }}</a>
                            @else
                                @empty($signatures->toArray())
                                    <div class="alert alert-danger">
                                        <ul>
                                        <li>{{ __('Cannot continue without creating a signature.') }}</li>
                                        </ul>
                                    </div><br />
                                    <a class="btn btn-primary" href="{{ route('apps.mailmerge.signature.create')}}">@icon('plus-circle') {{ __('Create Signature') }}</a>
                                @else
                                    @empty($exact_copies->toArray())
                                        <div class="alert alert-danger">
                                            <ul>
                                            <li>{{ __('Cannot continue without creating an exact copy.') }}</li>
                                            </ul>
                                        </div><br />
                                        <a class="btn btn-primary" href="{{ route('apps.mailmerge.exactcopy.create')}}">@icon('plus-circle') {{ __('Create Exact Copy') }}</a>
                                    @else
                                        <div class="form-group">
                                            <label for="logoselect">{{ __('Logo:') }}</label>
                                            <select class="form-control" id="logoselect" name="logoselect">
                                                @foreach($doc_logos as $logo)
                                                    <option value="{{ $logo->id }}">{{ $logo->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="addressselect">{{ __('Address:') }}</label>
                                            <select class="form-control" id="addressselect" name="addressselect">
                                                @foreach($doc_addresses as $doc_address)
                                                    <option value="{{ $doc_address->id }}">{{ $doc_address->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="protocol">{{ __('Protocol number:') }}</label>
                                                    <input type="text" id="protocol" name="protocol" class="form-control" required>
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
                                            <label for="subject">{{ __('Subject:') }}</label>
                                            <textarea id="subject" name="subject" class="form-control">
                                            </textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="text">{{ __('Text:') }}</label>
                                            <textarea id="text" name="text" class="form-control" rows="10">
                                            </textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="exactcopyselect">{{ __('Exact Copy:') }}</label>
                                            <select class="form-control" id="exactcopyselect" name="exactcopyselect">
                                                @foreach($exact_copies as $exact_copy)
                                                    <option value="{{ $exact_copy->id }}">{{ $exact_copy->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="signatureselect">{{ __('Signature:') }}</label>
                                            <select class="form-control" id="signatureselect" name="signatureselect">
                                                @foreach($signatures as $signature)
                                                    <option value="{{ $signature->id }}">{{ $signature->title }}</option>
                                                @endforeach
                                            </select>
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
                                @endempty
                            @endempty
                        @endempty
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

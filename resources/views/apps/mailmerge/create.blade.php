@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Mail merge') }}</div>

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

                    {!! Form::open(['action' => 'MailMerge\MailMergeController@store',
                                    'method' => 'POST']) !!}

                        <mailmerge-component
                            doc_logos_str="{{ $doc_logos->toJson() }}"
                            editors_str="{{ $editors->toJson() }}"
                            signatures_str="{{ $signatures->toJson() }}"
                            exact_copies_str="{{ $exact_copies->toJson() }}"
                            route_doc_logo_create="{{ route('apps.mailmerge.doclogo.create') }}"
                            route_editor_create="{{ route('apps.mailmerge.editor.create') }}"
                            route_signature_create="{{ route('apps.mailmerge.signature.create') }}"
                            route_exact_copy_create="{{ route('apps.mailmerge.exactcopy.create') }}"
                            route_index="{{ route('apps.mailmerge.index') }}"
                            func="create"
                        >
                        </mailmerge-component>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

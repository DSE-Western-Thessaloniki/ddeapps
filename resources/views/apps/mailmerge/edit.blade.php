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

                    {!! Form::open(['action' => ['MailMerge\MailMergeController@update', $mailmerge->id],
                                    'method' => 'POST']) !!}

                        <mailmerge-component
                            doc_logos_str="{{ $doc_logos->toJson() }}"
                            doc_logos_selected="{{ $mailmerge->logo_id }}"
                            editors_str="{{ $editors->toJson() }}"
                            editors_selected="{{ $mailmerge->editor_id }}"
                            signatures_str="{{ $signatures->toJson() }}"
                            signatures_selected="{{ $mailmerge->signature_id }}"
                            exact_copies_str="{{ $exact_copies->toJson() }}"
                            exact_copies_selected="{{ $mailmerge->exact_copy_id }}"
                            protocol_num="{{ $mailmerge->protocol_num }}"
                            doc_date="{{ $mailmerge->date }}"
                            doc_subject="{{ $mailmerge->subject }}"
                            doc_text="{{ $mailmerge->text }}"
                            doc_data="{{ $mailmerge->xlsxdata }}"
                            doc_data_header="{{ $mailmerge->xlsxdata_header }}"
                            doc_mfields="{{ $mailmerge->mergefields }}"
                            doc_ada="{{ $mailmerge->ada }}"
                            route_doc_logo_create="{{ route('apps.mailmerge.doclogo.create') }}"
                            route_editor_create="{{ route('apps.mailmerge.editor.create') }}"
                            route_signature_create="{{ route('apps.mailmerge.signature.create') }}"
                            route_exact_copy_create="{{ route('apps.mailmerge.exactcopy.create') }}"
                            route_index="{{ route('apps.mailmerge.index') }}"
                            func="edit"
                        >
                        </mailmerge-component>
                    {{Form::hidden('_method', 'PUT')}}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

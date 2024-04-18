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

                        <form method="POST" action="{{ route('apps.mailmerge.store') }}">
                            <mailmerge-component doc_logos_str="{{ $doc_logos->toJson() }}"
                                doc_logos_selected="{{ old('logo_id') }}" editors_str="{{ $editors->toJson() }}"
                                editors_selected="{{ old('editor_id') }}" signatures_str="{{ $signatures->toJson() }}"
                                signatures_selected="{{ old('signature_id') }}" exact_copies_str="{{ $exact_copies->toJson() }}"
                                exact_copies_selected="{{ old('exact_copy_id') }}" protocol_num="{{ old('protocol_num') }}"
                                doc_date="{{ old('date') }}" doc_subject="{{ old('subject') }}"
                                doc_text="{{ old('text') }}" doc_data="{{ old('xlsxdata') }}"
                                doc_data_header="{{ old('xlsxdata_header') }}" doc_mfields="{{ old('mergefields') }}"
                                doc_ada="{{ old('ada') }}"
                                :files_for_teachers="{{ json_encode(old('files_for_teachers') === '1') }}"
                                route_doc_logo_create="{{ route('apps.mailmerge.doclogo.create') }}"
                                route_editor_create="{{ route('apps.mailmerge.editor.create') }}"
                                route_signature_create="{{ route('apps.mailmerge.signature.create') }}"
                                route_exact_copy_create="{{ route('apps.mailmerge.exactcopy.create') }}"
                                route_index="{{ route('apps.mailmerge.index') }}" func="create">
                            </mailmerge-component>

                            @csrf
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

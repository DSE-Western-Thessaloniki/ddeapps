@extends('layouts.app')

<?php
$date = new DateTime($mailmerge->date);
?>

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

                    <pagepreview
                        doc_address_address="{{ $doc_address->address }}"
                        doc_address_name="{{ $doc_address->name }}"
                        doc_address_telephone="{{ $doc_address->telephone }}"
                        doc_address_email="{{ $doc_address->email }}"
                        doc_logo_image="{{ $doc_logo->image }}"
                        doc_logo_text="{{ $doc_logo->text }}"
                        exact_copy_text="{{ $exact_copy->text }}"
                        signature_text="{{ $signature->text }}"
                        protocol_num="{{ $mailmerge->protocol_num }}"
                        doc_date="{{ $date->format('d/m/Y') }}"
                        doc_subject="{{ $mailmerge->subject }}"
                        doc_text="{{ $mailmerge->text }}"
                        doc_recipient_fields="{{ $mailmerge->mergefields }}"
                        xls_data="{{ $mailmerge->xlsxdata }}"
                        print_url="{{ route('apps.mailmerge.print', ['id' => $mailmerge->id]) }}"
                        save_mail_merge_url="{{ route('apps.mailmerge.save', ['id' => $mailmerge->id]) }}"
                    >

                    </pagepreview>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

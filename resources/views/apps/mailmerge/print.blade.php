@extends('layouts.print')

@section('content')

<?php
// Prepare printing
$doc_logo_text_html = preg_replace('/\n/','<br/>', $doc_logo->text);
$exact_copy_text_html = preg_replace('/\n/','<br/>', $exact_copy->text);
$signature_text_html = preg_replace('/\n/','<br/>', $signature->text);
$date = new DateTime($mailmerge->date);
?>

<div class="container">
    <div class="page">
        <table class="table table-borderless">
            <tr>
                <td>
                    <p class="text-center"><img :src="logo_img"></p>
                    <p class="text-center">{!! $doc_logo_text_html !!}</p>
                    <table class="table table-borderless doc-address-col">
                        <tr>
                            <td class="no-wrap pr-1">Ταχ. Διεύθυνση:</td>
                            <td>{{ $doc_address->address }}</td>
                        </tr>
                        <tr>
                            <td>Πληροφορίες:</td>
                            <td>{{ $doc_address->name }}</td>
                        </tr>
                        <tr>
                            <td>Τηλέφωνο:</td>
                            <td>{{ $doc_address->telephone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><a href="mailto:{{ $doc_address->email }}">{{ $doc_address->email }}</a></td>
                        </tr>
                    </table>
                </td>

                <td>
                    <table class="table table-borderless doc-recipient-col">
                        <tr>
                            <td>
                                <p class="text-right">Θεσσαλονίκη, {{ $date->format('d/m/Y') }}<br/>
                                                    Αρ. Πρωτ.: {{ $mailmerge->protocol_num }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-bottom" id="recipients">
                                <p class="font-weight-bold mb-0">ΠΡΟΣ</p>
                                <p>[[ΟΝΟΜΑ]] [[ΕΠΩΝΥΜΟ]]<br/>
                                ΚΛΑΔΟΥ: [[ΚΛΑΔΟΣ]]<br/>
                                Α.Μ.: [[ΑΜ]]<br/>
                                </p>
                                <p class="font-weight-bold mb-0">ΚΟΙΝ</p>
                                1. ΑΦ [[ΑΦ]]<br/>
                                <span id="recipient-list"></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <p class="font-weight-bold">Θέμα: «{{ $mailmerge->subject }}»</p>
        <p id="doc_text">{!! $mailmerge->text !!}</p>

        <table class="table table-borderless signature-table">
            <tr>
                <td class="text-center">
                    {!! $exact_copy_text_html !!}
                </td>
                <td class="text-center">
                    {!! $signature_text_html !!}
                </td>
            </tr>
        </table>
    </div>
</div>

@endsection

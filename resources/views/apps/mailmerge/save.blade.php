@extends('layouts.print')

@section('content')

@php
// Prepare printing
$doc_logo_text_html = preg_replace('/\n/','<br/>', $doc_logo->text);
$exact_copy_text_html = preg_replace('/\n/','<br/>', $exact_copy->text);
$signature_text_html = preg_replace('/\n/','<br/>', $signature->text);
$date = new DateTime($mailmerge->date);
$xlsxdata = json_decode($mailmerge->xlsxdata, true);
$recipient_fields = json_decode($mailmerge->mergefields, true);
$base64_logo = base64_encode(file_get_contents(url(env('APP_URL')."/images/".$doc_logo->image)));
@endphp

<div class="container">
    @php
        // Προετοιμασία του κυρίως κειμένου του εγγράφου
        // Πρέπει να αντικαταστήσουμε τις [[ετικέτες]]
        $text = $mailmerge->text;
        preg_match_all("/\[\[(.+?)\]\]/", $mailmerge->text, $matches);
        foreach ($matches[1] as $match) {
            $text = str_replace("[[".$match."]]", $record[$match], $text);
        }

        // Προετοιμασία της λίστας των παραληπτών
        $recipients = array();
        foreach ($recipient_fields as $field) {
            if ($record[$field] != "") {
                array_push($recipients, $record[$field]);
            }
        }
        $recipients = array_unique($recipients);
        $recipients_text = "";
        $i = 2;
        foreach ($recipients as $recipient) {
            $recipients_text .= $i.". ".$recipient."<br/>";
            $i++;
        }
    @endphp
    <div class="page" size="A4">
        <table class="table table-borderless">
            <tr>
                <td class="w-50">
                    <p class="text-center"><img src="data:image/png;base64,{{ $base64_logo }}" width="50"></p>
                    <p class="text-center">{!! $doc_logo_text_html !!}</p>
                    <table class="table table-borderless doc-address-col">
                        <tr>
                            <td class="no-wrap pr-1">Ταχ. Διεύθυνση:</td>
                            <td>{{ $editor->address }}</td>
                        </tr>
                        <tr>
                            <td>Πληροφορίες:</td>
                            <td>{{ $editor->name }}</td>
                        </tr>
                        <tr>
                            <td>Τηλέφωνο:</td>
                            <td>{{ $editor->telephone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><a href="mailto:{{ $editor->email }}">{{ $editor->email }}</a></td>
                        </tr>
                    </table>
                </td>

                <td class="w-50">
                    <table class="table table-borderless doc-recipient-col">
                        <tr>
                            <td>
                                @if($mailmerge->ada)
                                <p class="text-right"><b>ΑΔΑ: {{ $mailmerge->ada }}</b></p>
                                @endif
                                <p class="text-right">Θεσσαλονίκη, {{ $date->format('d/m/Y') }}<br/>
                                                    Αρ. Πρωτ.: {{ $mailmerge->protocol_num }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-bottom" id="recipients">
                                <p class="font-weight-bold mb-0">ΠΡΟΣ</p>
                                <p>{{ $record['ΕΠΩΝΥΜΟ'] }} {{ $record['ΟΝΟΜΑ'] }}<br/>
                                ΚΛΑΔΟΥ: {{ $record['ΚΛΑΔΟΣ'] }}<br/>
                                @if(intval($record['ΑΜ']) < 1000000)
                                Α.Μ.: {{ $record['ΑΜ'] }}<br/>
                                @else
                                Α.Φ.Μ.: {{ $record['ΑΜ'] }}<br/>
                                @endif
                                (δια της σχολικής μονάδας)
                                </p>
                                <p class="font-weight-bold mb-0">ΚΟΙΝ</p>
                                1. ΑΦ @if (isset($record['ΑΦ'])) {{ $record['ΑΦ'] }} @endif<br/>
                                <span id="recipient-list">{!! $recipients_text !!}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <p class="font-weight-bold">Θέμα: «{{ $mailmerge->subject }}»</p>
        <p id="doc_text">{!! $text !!}</p>

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

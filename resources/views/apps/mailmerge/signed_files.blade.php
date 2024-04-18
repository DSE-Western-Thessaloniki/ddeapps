@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Λήψη υπογεγραμμένων αρχείων εγγράφου {{ $mailmerge->protocol_num }}/{{ $mailmerge->date }}</div>

                <div class="card-body">
                    <a
                    type="button"
                    class="btn btn-primary mb-4"
                    href="{{ route('apps.mailmerge.show', $mailmerge->id) }}"
                    >
                        Επιστροφή στην προεπισκόπηση
                    </a>
                    <p>Παρακαλώ επιλέξτε τα αρχεία για λήψη:</p>
                    @can('view', $mailmerge)
                    <a
                        type="button"
                        class="btn btn-success mb-4"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Λήψη αρχείου zip με όλα τα παρακάτω αρχεία"
                        href="{{ route('apps.mailmerge.zip', $mailmerge->id) }}"
                    >
                        Λήψη όλων
                    </a>
                    <table class="table table-striped">
                        @foreach ($files as $index => $file)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td>
                                <a href="{{ route('apps.mailmerge.signed_file', [$mailmerge->id, basename($file)])}}" target="_blank">{{ basename($file) }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

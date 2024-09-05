@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Ανέβασμα υπογεγραμμένων αρχείων εγγράφου
                        {{ $mailmerge->protocol_num }}/{{ $mailmerge->date }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Παρακαλώ επιλέξτε τα αρχεία για ανέβασμα:</p>
                        @can('update', $mailmerge)
                            <form action="{{ route('apps.mailmerge.upload_files', $mailmerge->id) }}" method="post"
                                enctype="multipart/form-data">
                                <input type="file" name="signed[]" class="form-control mb-4" accept=".pdf" multiple
                                    required />

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('apps.mailmerge.show', $mailmerge->id) }}"
                                        class="btn btn-primary">Επιστροφή</a>

                                    <button class="btn btn-success" type="submit">@icon('save')
                                        {{ __('Save') }}</button>
                                    @csrf
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

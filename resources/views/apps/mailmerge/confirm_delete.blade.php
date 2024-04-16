@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Επιβεβαίωση διαγραφής κοινοποίησης</div>

                <div class="card-body">
                    <p class="text-danger text-center display-5">Είστε σίγουροι ότι θέλετε να διαγράψετε την κοινοποίηση;</p>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('apps.mailmerge.create')}}" class="btn btn-primary">Επιστροφή</a>

                        @can('delete', $mailmerge)
                        <form action="{{ route('apps.mailmerge.destroy', $mailmerge->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">@icon('trash-alt') {{ __('Delete') }}</button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

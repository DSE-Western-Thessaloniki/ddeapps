@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Logos') }}</div>

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

                    <table class="table table-striped">
                        <tr>
                            <td>{{ __('Title') }}</td>
                            <td class="text-center">
                                {{ $doclogo->title }}
                            </td>
                        </tr>

                        <tr>
                            <td>{{ __('Image') }}</td>
                            <td class="text-center">
                                {{ $doclogo->image }}
                                <img src="/images/{{$doclogo->image}}">
                            </td>
                        </tr>

                        <tr>
                            <td>{{ __('Text') }}</td>
                            <td class="text-center">
                                <pre>{{ $doclogo->text }}</pre>
                            </td>
                        </tr>

                        <tr>
                            <td>{{ __('Active') }}</td>
                            <td class="text-center">
                                @if ($doclogo->active)
                                    {{ __('True') }}
                                @else
                                    {{ __('False') }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="col-2">
                                <a class="btn btn-danger" href="{{ route('apps.mailmerge.doclogo.index') }}">{{ __('Back') }}</a>
                            </td>
                            <td class="col-10 d-flex justify-content-end">
                                @can('update', $doclogo)
                                <a class="btn btn-primary" href="{{ route('apps.mailmerge.doclogo.edit', $doclogo->id)}}">{{ __('Edit') }}</a>
                                @endcan
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

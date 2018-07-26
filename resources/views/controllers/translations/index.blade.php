@extends('arbory::layout.main')

@section('content.header')
    {!! $breadcrumbs !!}
@stop

@section('content')


    <div class="card">
        <div class="card-header bg-light">
            @lang('arbory::translations.all_translations')
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    {!! $searchField !!}
                </div>
            </div>

            <table class="table table-responsive-sm">
                <thead>
                <tr>
                    <th>Group</th>
                    <th>Key</th>
                    @foreach($languages as $language)
                        <th>Text {{$language->locale}}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($translations as $translation)
                    <tr>
                        <td>{!! $highlight($translation->namespace) !!}::{!! $highlight($translation->group) !!}</td>
                        <td>{!! $highlight($translation->item) !!}</td>
                        @foreach($languages as $language)
                            <td>{!! $highlight($translation->{$language->locale . '_text'}) !!}</td>
                        @endforeach
                        <td><a class="btn btn-sm btn-success" href="{{$translation->edit_url}}"
                            ><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

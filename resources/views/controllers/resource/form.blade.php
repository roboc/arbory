@extends('arbory::layout.main', [ 'body_class' => $bodyClass ])

@section('content.header')
    {!! $breadcrumb !!}
@endsection

@section('content')
    {!! $content !!}
@stop

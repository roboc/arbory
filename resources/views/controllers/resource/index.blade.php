@extends('arbory::layout.main', [ 'body_class' => $bodyClass ])

@section('content.header')
    {!! $breadcrumb !!}
@endsection

@section('content')
    {!! $content !!}

    <div class="modal fade" id="arbory-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"></div>
        </div>
    </div>

@stop

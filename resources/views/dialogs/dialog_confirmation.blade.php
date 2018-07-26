@extends('arbory::dialogs.base',['class' => 'modal-secondary'])

@section('dialog')
    <form class="edit_resource" id="edit_resource" action="{{ $form_target }}" accept-charset="UTF-8" method="post">
        <input type="hidden" name="_method" value="{{ $form_action }}">
        {{csrf_field()}}
        <div class="modal-header">
            <h5 class="modal-title">@yield('dialog.title')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @yield('dialog.body')
        </div>
        <div class="modal-footer">
            @yield('dialog.tools.secondary')
            @yield('dialog.tools.primary')
        </div>
    </form>
@stop

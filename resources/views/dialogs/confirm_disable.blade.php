@extends('arbory::dialogs.dialog_confirmation', [
    'form_target' => $form_target,
    'form_action' => 'post'
])

@section('dialog.title')
    @lang('Confirm disable')
@stop

@section('dialog.body')
    <div class="confirmation">
        <i class="fa fa-trash-o"></i>
        <div class="question text-center h5">@lang('Do you want to disable the following object?')</div>
        <div class="description text-center text-primary">{{$object_name}}</div>
    </div>
@stop

@section('dialog.tools.primary')
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> @lang('No')</button>
    <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> @lang('Yes')</button>
@stop

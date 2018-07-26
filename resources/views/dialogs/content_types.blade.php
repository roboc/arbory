@extends('arbory::dialogs.base',['class' => 'modal-secondary content-type'])

@section('dialog')
    <div class="modal-header">
        <h5 class="modal-title">@lang('arbory::dialog.nodes_content_type.add_new_node')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="description h5">@lang('arbory::dialog.nodes_content_type.select_content_type')</div>
        @if($types)
        <div class="content-types">
            <div class="list-group">
                @foreach($types as $type)
                    <a class="list-group-item  list-group-item-action" href="{{$type['url']}}">{{$type['title']}}</a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@stop


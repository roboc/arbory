jQuery(function () {
    var body = jQuery('body');
    var modal = body.find('#arbory-modal');

    modal.on('show.bs.modal', function (e) {
        var button = jQuery(e.relatedTarget);
        var modal = jQuery(this);

        modal.find('.modal-dialog').load(button.data('remote'));
    });

    modal.on('hidden.bs.modal', function () {
        var modal = jQuery(this);

        modal.find('.modal-content').empty();
    });
});

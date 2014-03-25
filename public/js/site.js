/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {

    $("[name='switch']").bootstrapSwitch();
    $("[name='switch']").on('switchChange.bootstrapSwitch', function() {
        var arr = {
            'type': $(this).data('type'),
            'id': $(this).data('id'),
            'action': $(this).data('action')
        }
        $.ajax({
            url: '/json/state',
            type: 'POST',
            data: JSON.stringify(arr),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: true,
            success: function(msg) {
                //alert(msg.status);
            }
        });
    });
    $('.setstate').on('click', function() {
        var arr = {
            'type': $(this).data('type'),
            'id': $(this).data('id'),
            'action': $(this).data('action')
        }
        $.ajax({
            url: '/json/state',
            type: 'POST',
            data: JSON.stringify(arr),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: true,
            success: function(msg) {
                //alert(msg.status);
            }
        });
    })

    $('a[data-toggle="modal"]').on('click', function(e) {

        // From the clicked element, get the data-target arrtibute
        // which BS3 uses to determine the target modal
        var target_modal = $(e.currentTarget).data('target');
        // also get the remote content's URL
        var remote_content = e.currentTarget.href;

        // Find the target modal in the DOM
        var modal = $(target_modal);
        // Find the modal's <div class="modal-body"> so we can populate it
        var modalBody = $(target_modal + ' .modal-body');

        // Capture BS3's show.bs.modal which is fires
        // immediately when, you guessed it, the show instance method
        // for the modal is called
        modal.on('show.bs.modal', function() {
            // use your remote content URL to load the modal body
            modalBody.load(remote_content, function() {
                $('#groupconfig').multiSelect({
                    selectableHeader: "<div class='custom-header'>inaktiv</div>",
                    selectionHeader: "<div class='custom-header'>aktiv</div>"
                });
                initDynamic();
            });
        }).modal();
        // and show the modal

        // Now return a false (negating the link action) to prevent Bootstrap's JS 3.1.1
        // from throwing a 'preventDefault' error due to us overriding the anchor usage.
        return false;
    });
    $('button[data-action="save"]').on('click', function(e) {
        var modal = $(this).parent().parent().find('.modal-body');
        var target = modal.find('[data-target]').data('target');
        $.ajax({
            url: target,
            type: 'POST',
            data: JSON.stringify($('#groupconfig').val()),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: true,
            success: function(msg) {
                alert(msg);
            }
        });
    })
    $('.pick-a-color').pickAColor();
    $(".pick-a-color").on("change", function () {
        console.log(tinycolor($(this).val()).toRgb());
        var arr = {
            'type': 'light',
            'id': $(this).data('id'),
            'action': $(this).data('action'),
            'color': tinycolor($(this).val()).toRgb()
        }
        $.ajax({
            url: '/json/state',
            type: 'POST',
            data: JSON.stringify(arr),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: true,
            success: function(msg) {
                //alert(msg.status);
            }
        });
    });
});

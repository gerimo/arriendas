$(document).on('ready', function() {
    $('#example').dataTable({
        "bDeferRender": true,
        "bScrollInfinite": true,
        "bScrollCollapse": true,
        "sScrollY": "400px"
    });
});
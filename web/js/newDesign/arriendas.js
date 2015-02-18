function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

$(document).on("click", ".notbar .remove", function(){

    $(this).parent().hide(1000);

    /*$.post("<?php echo ?>");*/
});
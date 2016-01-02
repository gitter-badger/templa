$(function() {
    $("#savedMes").hide();
    $('#saveForm').submit(function(event) {
        event.preventDefault();
        var $form = $(this);
        var $submitBtn = $("#saveSubmit");

        var _data = {
            "from": $("#url").val(),
            "return": $("#return").val(),
            "key": $("#key").val(),
            "secret": $("#secret").val()
        };
        $("#savedMes").hide();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: _data,
            timeout: 10000,
            beforeSend: function(xhr, settings) {
                $submitBtn.attr('disabled', true);
            },
            complete: function(xhr, textStatus) {
                $submitBtn.attr('disabled', false);
            },
            success: function(result, textStatus, xhr) {
                console.log("saved");
                $("#savedMes").show();                
            },
            error: function(xhr, textStatus, error) {
                console.log("error");
                console.log(result);
            }
        });
    });
});

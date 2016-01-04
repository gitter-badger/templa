$(function() {
    $("#savedMes").hide();

    var $form = $('#saveForm');

    $form.submit(function(event) {
        event.preventDefault();
        var $submitBtn = $("#saveSubmit");

        var _data = {
            "from": $("#url").val(),
            "return": $("#return").val(),
            "key": $("#key").val(),
            "secret": $("#secret").val()
        };
        $("#savedMes").hide();
        console.log(_data);

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
                console.log(result);
                $("#savedMes").show();
            },
            error: function(xhr, textStatus, error) {
                console.log("error");
                console.log(xhr);
            }
        });
    });

    (function dataLoad(){
        $.ajax({
            url: $form.attr('action'),
            type: "GET",
            timeout: 10000,
            success: function(result, textStatus, xhr) {
                console.log("loaded");
                console.log(result);
                $("#url").val(result.fromurl);
                $("#return").val(result.returnurl);
                $("#key").val(result.tw_consumer_key);
                $("#secret").val(result.tw_consumer_key_secret);
            },
            error: function(xhr, textStatus, error) {
                console.log("error");
                console.log(xhr);
            }
        });
    })()
});

$(document).ready(function()
{
    changePrivateSettings();
});

function changePrivateSettings()
{
    $('.configOptionSelect').change(function()
    {
        var name = $(this).attr('name');
        var value = $(this).val();
        var ajaxIcon = $('.ajaxWaitSmall');
        $.ajax
        ({
            url: './../options_private_sync_config_change',
            dataType: 'json',
            type: 'POST',
            data:
            {
                name: name,
                value: value
            },
            onLoading: ajaxIcon.show(),
            success: function(response)
            {
                ajaxIcon.fadeOut();
                if(response.success)
                {

                }
                else
                {
                    console.log(response.error);
                }

            }
        })
    });
}

$(document).ready(function()
{
    tooltip();
});

function tooltip()
{
    $('.tooltip').each(function()
    {
        var tooltip = $(this);
        var tooltipContainer = tooltip.parent();

        tooltipContainer.mouseover(function()
        {
            tooltip.show();
        })

        tooltipContainer.mouseout(function()
        {
           tooltip.fadeOut(400);
        });
    })
}
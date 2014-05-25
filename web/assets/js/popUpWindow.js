function createPopUpWindow(divWidth, divId, event)
{
    var popUpDiv = '#' + divId;
    var height = $(popUpDiv).height();

    $(popUpDiv)
     .width(divWidth)
     .css('top', -height - 5)
     .fadeIn(500);


    $(popUpDiv).animate
    ({
        top: '115px'
    });

    var popUpBgndDiv = $('.popUpWindowBackground');
    var popUpCloseDiv = $('#' + divId).find('#popUpClose');
    var popUpToolTip = $('#' + divId).find('#popUpCloseTooltip');

    popUpBgndDiv.css("opacity", "0.7"); // css opacity, supports IE7, IE8
    popUpBgndDiv.fadeIn(0001);

    // Show / Hide ToolTip
    popUpCloseDiv.hover(function()
    {
        popUpToolTip.toggle();
    });

    // Close Current PopUp Window
    popUpCloseDiv.click(function()
    {
        $(popUpDiv).fadeOut(0500);
        popUpBgndDiv.fadeOut('normal');
    });

    event.preventDefault();
}

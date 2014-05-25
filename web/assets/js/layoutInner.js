$(document).ready(function()
{
    map();
});

function map()
{
    openMap();
}

function openMap()
{
    $('#map').click(function(event)
    {
        createPopUpWindow('80%', 'mapPopUp', event);
        var map = L.mapbox.map('mapBox', 'examples.map-i86nkdio')
            .setView([40, -74.50], 9);
    });
}
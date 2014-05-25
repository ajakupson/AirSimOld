var offset = 0;

$(document).ready(function()
{
    searchContacts();
    writeMessage();
});

function searchContacts()
{
    $('#searchContactButton').click(function()
    {
        offset = 0;
        $('.usersBlocksWrap').html('');
        appendContacts();
    });
    $(window).scroll(function()
    {
        if($(window).scrollTop() + $(window).height() == $(document).height())
            appendContacts();
    });
}

function appendContacts()
{
    var searchText = $('#searchContactText').val();
    var gender = $('input[name="gender"]').filter(':checked').val();
    var phoneNumber = $('#phoneNumber').val();
    var country = $('#country').val();
    var city = $('#city').val();
    var ageFrom = $('#ageFrom').val();
    var ageTo = $('#ageTo').val();

    $.ajax
    ({
        url: './../../contacts_search_contact',
        type: 'POST',
        dataType: 'json',
        data:
        {
            nameFamily: searchText,
            gender: gender,
            phoneNumber: phoneNumber,
            country: country,
            city: city,
            ageFrom: ageFrom,
            ageTo: ageTo,
            offset: offset
        },
        onLoading: $('#waitContactsToLoad').show(),
        success: function(response)
        {
            if(response.success)
            {
                $('#waitContactsToLoad').hide();
                offset += 10;
                $.each(response.data.contacts, function(key, value)
                {
                    var friendBlock = '<div class = "friend">';
                    friendBlock += '<table><tbody>';
                    friendBlock +=  '<tr>';
                    friendBlock +=  '<td class = "center">';
                    friendBlock +=  '<div class = "name">' + value.firstName + '</div>' + value.lastName;
                    friendBlock +=  '</td>';
                    friendBlock +=  '<td>';
                    if(value.webProfilePic != null )
                        friendBlock +=  '<img src = "/AirSim/web/public_files/' + value.username + '/albums/profile_pics/' + value.webProfilePic + '"/>';
                    else friendBlock += '<img src = "/AirSim/web/public_files/default/noAvatarMale.png"/>';
                    friendBlock +=  '</td>';
                    friendBlock +=  '<td>';
                    friendBlock +=  'Add contact<br/>Write message';
                    friendBlock +=  '</td>';
                    friendBlock += '</tr>';
                    friendBlock += '</tbody></table>';
                    friendBlock += '</div>';

                    $('.usersBlocksWrap').append(friendBlock);
                    $('.usersBlocksWrap').append('<div class = "clear"></div>');
                });
            }
            else
            {
                console.log('returned error');
            }
        },
        error: function()
        {
            console.log('error');
        }
    });
}

function writeMessage()
{
    $('.test').click(function(event)
    {
       createPopUpWindow('50%', 'writeMessagePopUp', event);
    });
}
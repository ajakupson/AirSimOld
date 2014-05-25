$(document).ready(function()
{
    $('#searchFriendText').on('input', function()
    {
        var searchText = $(this).val();
        $.ajax
        ({
            url: './../../contacts_search_friend',
            type: 'POST',
            dataType: 'json',
            data:
            {
                searchText: searchText
            },
            success: function(response)
            {
                if(response.success)
                {
                    $('.friendBlocksWrap').html('');
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
                            friendBlock +=  'Write message<br/>Note<br/>History of changes';
                            friendBlock +=  '</td>';
                            friendBlock +=  '<td>';
                            friendBlock +=  '<input type = "checkbox" class = "friendCheck"/>';
                            friendBlock +=  '</td>';
                            friendBlock += '</tr>';
                            friendBlock += '</tbody></table>';
                            friendBlock += '</div>';

                        $('.friendBlocksWrap').append(friendBlock);
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

    });
});
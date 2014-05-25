var conn = new ab.Session
(
    'ws://80.66.252.45:8080', // The host (our Ratchet WebSocket server) to connect to
    function()
    {
        // Once the connection has been established
        console.log('WebSocket connection is opened');

        var userId = $('#userId').val();
        conn.subscribe('user' + userId, function(topic, response)
        {
            //console.log(response.data.text);
            var event = response.event;
            switch(event)
            {
                case 'addWallRecord':
                {
                    addWallRecordSocket(response);
                }break;
                case 'likeWallRecord':
                {
                    likeWallRecordSocket(response);
                }break;
                case 'dislikeWallRecord':
                {
                    dislikeWallRecordSocket(response);
                }break;
                case 'deleteWallRecord':
                {
                    deleteWallRecordSocket(response);
                }break;
                default:break;
            }

        });
    },
    function()
    {
        // When the connection is closed
        console.warn('WebSocket connection closed');
    },
    {
        // Additional parameters, we're ignoring the WAMP sub-protocol for older browsers
        'skipSubprotocolCheck': true
    }
);

$(document).ready(function()
{
    addWallRecord();
    likeWallRecord();
    dislikeWallRecord();
    deleteWallRecord();
})

function addWallRecord()
{
    $('#addWallRecord').click(function()
    {
        var wallRecordForm = $('#wallRecordAddForm');
        var userId = $('#userId').val();
        var wallRecordText = $('.wallTextarea').val();

        if(wallRecordText.length > 0)
        {
            wallRecordForm.ajaxForm
            ({
                url: './../user_add_wall_record',
                dataType: 'json',
                type: 'POST',
                data:
                {
                    userId: userId,
                    page: 'user' + userId,
                    event: 'addWallRecord',
                    text: wallRecordText
                },
                success: function(response)
                {
                    if(response.success)
                    {
                        $('.wallTextarea').html('');
                        $('.attachedPhotos > ul').html('');
                        $('.attachedPhotos').hide();
                        $('.attachedDocuments > ul').html('');
                        $('.attachedDocuments').hide();
                    }
                    else
                    {
                        console.log(response.error);
                    }

                }
            }).submit();
        }
    });
}
function likeWallRecord()
{
    $(document).on('click', '.likeWallRecord.active', function()
    {
        var userId = $('#userId').val();
        var wallRecordBlock = $(this).closest('.wallRecord');
        var wallRecordId = wallRecordBlock.find('.wallRecordId').val();
        var likeButton = $(this);
        var dislikeButton = wallRecordBlock.find('.dislikeWallRecord');


        $.ajax
        ({
            url: './../user_like_wall_record',
            dataType: 'json',
            type: 'POST',
            data:
            {
                page: 'user' + userId,
                event: 'likeWallRecord',
                wallRecordId: wallRecordId
            },
            success: function(response)
            {
                if(response.success)
                {
                    likeButton.removeClass('active');
                    likeButton.addClass('inactive');

                    if(dislikeButton.hasClass('inactive'))
                    {
                        dislikeButton.removeClass('inactive');
                        dislikeButton.addClass('active');
                    }
                }
                else
                {
                    console.log(response.error);
                }

            }
        })
    });
}
function dislikeWallRecord()
{
    $(document).on('click', '.dislikeWallRecord.active', function()
    {
        var userId = $('#userId').val();
        var wallRecordBlock = $(this).closest('.wallRecord');
        var wallRecordId = wallRecordBlock.find('.wallRecordId').val();
        var dislikeButton = $(this);
        var likeButton = wallRecordBlock.find('.likeWallRecord');

        $.ajax
        ({
            url: './../user_dislike_wall_record',
            dataType: 'json',
            type: 'POST',
            data:
            {
                page: 'user' + userId,
                event: 'dislikeWallRecord',
                wallRecordId: wallRecordId
            },
            success: function(response)
            {
                if(response.success)
                {
                    dislikeButton.removeClass('active');
                    dislikeButton.addClass('inactive');

                    if(likeButton.hasClass('inactive'))
                    {
                        likeButton.removeClass('inactive');
                        likeButton.addClass('active');
                    }
                }
                else
                {
                    console.log(response.error);
                }

            }
        })
    });
}
function deleteWallRecord()
{
    var confirmDialog;
    var dialogOperation;
    var wallRecordBlock;
    var wallRecordId;

    $(document).on('click','.wallRecordDeleteButton', function()
    {
        wallRecordBlock = $(this).closest('.wallRecord');
        wallRecordId = wallRecordBlock.find('.wallRecordId').val();

        createPopUpWindow('19%', 'dialogPopUp', event);

        confirmDialog = $('#dialogPopUp');
        $('#dialogOperation').val('deleteWallRecord');
    });

    $('#confirmYes').click(function()
    {
        dialogOperation = $('#dialogOperation').val();

        if(dialogOperation == 'deleteWallRecord')
        {
            var userId = $('#userId').val();
            $.ajax
            ({
                url: './../user_delete_wall_record',
                dataType: 'json',
                type: 'POST',
                data:
                {
                    page: 'user' + userId,
                    event: 'deleteWallRecord',
                    wallRecordId: wallRecordId
                },
                success: function(response)
                {
                    if(response.success)
                    {
                        //console.log(response.value);
                    }
                    else
                    {
                        console.log(response.error);
                    }

                }
            })
        }
    });

    $('#confirmNo').click(function()
    {
        dialogOperation = $('#dialogOperation').val();

        if(dialogOperation == 'deleteWallRecord')
        {
            confirmDialog.find('#popUpClose').trigger('click');
        }
    });
}

/* ********** WebSocket ********** */
function addWallRecordSocket(data)
{
    var wallRecordsBlock = $('.wallRecords');
    var wallRecord = '<article class = "wallRecord">';
        wallRecord += '<input type = "hidden" class = "wallRecordId" value = "' + data.data.newWallRecordId + '"/>';
        wallRecord += '<img src = "' + data.data.authorPic + '" class = "wallRecordAuthorPic"/>';
        wallRecord += '<div class = "wallRecordHeader">';
        wallRecord += '<span class = "wallRecordAuthor">' + data.data.authorName + ' ' + data.data.authorFamily + '</span>';
        wallRecord += '<input type = "button" class = "wallRecordDeleteButton"/>';
        wallRecord += '<span class = "wallRecordDate">' + data.data.recordDate + '</span>';
        wallRecord += '<div class = "wallRecordText">' + data.data.text+ '</div>';
        wallRecord += '<div class = "wallRecordPics">';
        wallRecord += '<ul>';
        $.each(data.data.addedPics, function(key, value)
        {
            wallRecord += '<li>';
            wallRecord += '<img src = "../../public_files/' + data.data.toUsername + '/albums/wall_pics/' + value.fileName + '" class = "photo"/>';
            wallRecord += '<input type = "hidden" class = "photoId" value = "' + value.fileId + '" />';
            wallRecord += '</li>';
        });
        wallRecord += '</ul><div class = "clear"></div>';
        wallRecord += '</div>';
        wallRecord += '<div class = "wallRecordButtons">';
        wallRecord += '<input type = "button" class = "likeButton likeWallRecord" />';
        wallRecord += '<span class = "totalLikes">0</span>';
        wallRecord += '<input type = "button" class = "dislikeButton dislikeWallRecord" />';
        wallRecord += '<span class = "totalDislikes">0</span>';
        wallRecord += '<input type = "button" class = "submitButton wallReplyButton" value = "Reply" />';
        wallRecord += '</div>';
        wallRecord += '<div class = "clear"></div>'
        wallRecord += '</article>';

    wallRecordsBlock.prepend(wallRecord);

    /* Notification */
    /*if(data.data.toId)
    {
        if(data.data.toId == $('#userId').val())
        {
            $('.notifiationBox').fadeIn(500);
            $('#notificationSound').get(0).play();
        }
    }*/
}
function likeWallRecordSocket(data)
{
    $('.wallRecord').each(function()
    {
        var wallRecordId = $(this).find('.wallRecordId');
        var wallRecordIdVal = wallRecordId.val();
        if(wallRecordIdVal == data.data.wallRecordId)
        {
            var likesContainer = $(this).find('.totalLikes');
            var likes = likesContainer.html();
            likes++;
            $(this).find('.totalLikes').html('+' + likes);

            if(data.data.flag == 'change')
            {
                var dislikesContainer = $(this).find('.totalDislikes');
                var dislikes = dislikesContainer.html();
                dislikes++;
                $(this).find('.totalDislikes').html(dislikes);
            }
        }
    });
}
function dislikeWallRecordSocket(data)
{
    $('.wallRecord').each(function()
    {
        var wallRecordId = $(this).find('.wallRecordId');
        var wallRecordIdVal = wallRecordId.val();
        if(wallRecordIdVal == data.data.wallRecordId)
        {
            var dislikesContainer = $(this).find('.totalDislikes');
            var dislikes = dislikesContainer.html();
            dislikes--;
            $(this).find('.totalDislikes').html(dislikes);

            if(data.data.flag == 'change')
            {
                var likesContainer = $(this).find('.totalLikes');
                var likes = likesContainer.html();
                likes--;
                $(this).find('.totalLikes').html(likes);
            }
        }
    });
}
function deleteWallRecordSocket(data)
{
    $('.wallRecord').each(function()
    {
        var wallRecordId = $(this).find('.wallRecordId');
        var wallRecordIdVal = wallRecordId.val();
        if(wallRecordIdVal == data.data.wallRecordId)
        {
            $('#dialogPopUp').find('#popUpClose').trigger('click');
            $(this).remove();
        }
    });
}
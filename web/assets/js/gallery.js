$(document).ready(function()
{
    showPhoto();
    listPhoto();
    showHideDescription();
});

function showPhoto()
{
    $('.photo').click(function(event)
    {
        var photoId = $(this).parent().find('.photoId').val();
        getPhotoData(photoId);
        createPopUpWindow('80%', 'photoPopUp', event);
    });
}

function getPhotoData(photoId)
{

    $.ajax
    ({
        url: './../user_get_photo_data',
        data:
        {
            photoId: photoId
        },
        dataType: 'json',
        type: 'POST',
        success: function(response)
        {
            if(response.success)
            {
                var photoContainer = $('.photoContainer');
                var img = photoContainer.find('.photoShow');
                var imagePath = './../../public_files/admin/albums/' + response.data.photoData.albumName + '/' +
                    response.data.photoData.photoName;
                var prevPhotoId = response.data.photoData.previousPhotoId;
                var nextPhotoId = response.data.photoData.nextPhotoId;

                if(prevPhotoId.length == 0)
                    $('.previousPhoto').hide();
                else
                {
                    $('#previousPhotoId').val(prevPhotoId);
                    $('.previousPhoto').show();
                }

                if(nextPhotoId.length == 0)
                    $('.nextPhoto').hide();
                else
                {
                    $('#nextPhotoId').val(nextPhotoId);
                    $('.nextPhoto').show();
                }

                img.attr('src', imagePath);

                /* Info */
                var albumTitle = response.data.photoData.albumTitle;
                var albumId = response.data.photoData.albumId;
                var photoTitle = response.data.photoData.photoTitle;
                var photoDescription = response.data.photoData.photoDescription;
                var dateAdded = response.data.photoData.photoDateAdded;
                var photoLocation = response.data.photoData.photoAddress;
                var undefined = '..........';

                $('.spanAlbum').html((albumTitle != null) ? albumTitle : undefined);
                $('.spanPhoto').html(photoTitle != null ? photoTitle : undefined);
                $('.photoDescription').html(photoDescription);
                $('.spanDate').html(dateAdded != null ? dateAdded : undefined);
                $('.spanLocation').html(photoLocation != null ? photoLocation : undefined);
            }
            else
            {
                console.log(response.error);
            }

        }
    });
}

function listPhoto()
{

    var photoId;
    $('.previousPhoto').click(function()
    {
        photoId = $('#previousPhotoId').val();
        getPhotoData(photoId);
    });

    $('.nextPhoto').click(function()
    {
        photoId = $('#nextPhotoId').val();
        getPhotoData(photoId);
    });
}

function showHideDescription()
{
    $('.descriptionButton').mouseover(function()
    {
        $('.photoDescription').fadeIn('fast');
    })

    $('.descriptionButton').mouseout(function()
    {
        $('.photoDescription').fadeOut('fast');
    })
}
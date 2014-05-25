$(document).ready(function()
{
    $('#mainInfoHeader').click(function()
    {
       $('#mainInfoBlock').slideToggle('slow');
    });
    $('#additionalInfoHeader').click(function()
    {
        $('#additionalInfoBlock').slideToggle('slow');
    });
    $('#hobbiesInfoHeader').click(function()
    {
        $('#hobbiesInfoBlock').slideToggle('slow');
    });

    ratyInit();
    attachFiles();
});

function ratyInit()
{
    $('.photoRatingStars').raty({
        starOff: '../../assets/images/styles/raty/starOffBig.png',
        starOn: '../../assets/images/styles/raty/starOnBig.png',
        number: 10,
        score: 8,
        /*score: function()
        {
            return $(this).attr('data-score');
        },*/
        readOnly: false,
        size: 18
    });
}

function attachFiles()
{
    previewAttachedImages();
    previewAttachedDocuments();
}

function previewAttachedImages()
{
    $('#wallAttachedPictures').change(function()
    {
        var images = $(this).get(0).files;
        var totalImages = images.length;
        var imageWidth;

        /*if(totalImages >= 4)
            imageWidth = 24.5;
        else imageWidth = 100 / totalImages - 0.5;*/

        imageWidth = 19.5;
        var previewBlock = $('.attachedPhotos > ul');
        previewBlock.html('');
        for(var i = 0; i < totalImages; i++)
        {
            //console.log(images[i].type);
            var reader = new FileReader();
            reader.onload = function(e)
            {
                var imgBlock = $('<li />').css({'width': imageWidth + '%', 'background': ('url('+ e.target.result+') no-repeat  '),
                    'background-size': 'cover'});
                imgBlock.appendTo(previewBlock);
            }
            reader.readAsDataURL(this.files[i]);
        }
        $('.wallInputWrapper').addClass('focus');
        $('.attachedPhotos').fadeIn(1500);
    });
}

function previewAttachedDocuments()
{
    $('#wallAttachedDocuments').change(function()
    {
        var validExtensions = new Array("doc", "docx", "xlsx", "xls", "ppt", "pptx", "csv", "txt", "pdf", "zip", "rar");
        var documents = $(this).get(0).files;
        var totalDocuments = documents.length;
        var previewBlock = $('.attachedDocuments > ul');
        previewBlock.html('');

        for(var i = 0; i < totalDocuments; i++)
        {
            var documentBlock = $('<li />');
            var documentName = documents[i].name;
            var documentExtension = documentName.substr((documentName.lastIndexOf('.') + 1));
            if (validExtensions.indexOf(documentExtension) >= 0)
            {
                var name = '<span class = "name">' + documentName + '</span>';
                switch(documentExtension)
                {
                    case 'pdf':
                        documentBlock.html('<div class = "pdfIcon"></div>' + name);
                        break;
                    case 'doc':
                    case 'docx':
                        documentBlock.html('<div class = "docxIcon"></div>' + name);
                        break;
                    case 'xls':
                    case 'xlsx':
                        documentBlock.html('<div class = "xlsxIcon"></div>' + name);
                        break;
                    case 'ppt':
                    case 'pptx':
                        documentBlock.html('<div class = "pptxIcon"></div>' + name);
                        break;
                    case 'csv':
                        documentBlock.html('<div class = "csvIcon"></div>' + name);
                        break;
                    case 'txt':
                        documentBlock.html('<div class = "txtIcon"></div>' + name);
                        break;
                    case 'rar':
                        documentBlock.html('<div class = "rarIcon"></div>' + name);
                        break;
                    case 'zip':
                        documentBlock.html('<div class = "zipIcon"></div>' + name);
                        break;
                    default:
                        documentBlock.html('<div class = "documentIcon"></div>' + name);
                        break;

                }
                documentBlock.appendTo(previewBlock);
            }
            else
            {
                // Create array, which stores indexes of not supported files,
                // and show error to user
            }
        }
        $('.wallInputWrapper').addClass('focus');
        $('.attachedDocuments').fadeIn(1500);
    });
}
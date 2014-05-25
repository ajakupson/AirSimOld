var currentSlide = 1;
var totalSlides = 0;

$(document).ready(function()
{
    slider();
    showPopUpWindows();
    logIn();
});

function slider()
{
    totalSlides = $('#slider > li').length;

    if(totalSlides == 1)
    {
        $('.arrowLeft').hide();
        $('.arrowRight').hide();
    }

    nextSlide();
    previousSlide();
}
function nextSlide()
{

    $('.arrowRight').click(function()
    {
        currentSlide++;
        if(currentSlide == totalSlides){ $('.arrowRight').hide(); }
        else{ if($('.arrowLeft').is(":hidden")) { $('.arrowLeft').show(); } }

        $('#slider').animate({marginLeft: "-" + 900 * (currentSlide - 1)}, 500);

    });
}
function previousSlide()
{
    $('.arrowLeft').click(function()
    {
        currentSlide--;
        if(currentSlide == 1){ $('.arrowLeft').hide(); }
        else{ if($('.arrowRight').is(":hidden")) { $('.arrowRight').show(); } }

        $('#slider').animate({marginLeft: "-" + 900 * (currentSlide - 1)}, 500);

    });
}

function showPopUpWindows()
{
    showAboutUs();
    showContact();
}

function showAboutUs()
{
    $('#about').click(function(event)
    {
        createPopUpWindow('60%', 'aboutPopUp', event);
    });
}

function showContact()
{
    $('#contact').click(function(event)
    {
        createPopUpWindow('60%', 'contactPopUp', event);
    });
}

function logIn()
{
    $('.loginButton').click(function()
    {
        var username = $('input[name = "username"]').val();
        var password = $('input[name = "password"]').val();
        var errorCounter = 0;

        if(username.length == 0)
        {
            errorCounter++;
            showElement('#usernameIsRequired');
        }
        else hideElement('#usernameIsRequired');
        if(username.length < 5)
        {
            errorCounter++;
            showElement('#usernameMinLength');
        }
        else hideElement('#usernameMinLenght');

        if(password.length == 0)
        {
            errorCounter++;
            showElement('#passwordIsRequired');
        }
        else hideElement('#passwordIsRequired');
        if(password.length < 5)
        {
            errorCounter++;
            showElement('#passwordMinLength');
        }
        else hideElement('#passwordMinLength');

        if(errorCounter > 0)
            return false;
        else
        {
            hideElement('#wrongUsernameOrPass');

            $.ajax
            ({
                data:
                {
                    username: username,
                    password: password
                },
                url: './../accounts_login',
                dataType: 'json',
                type: 'POST',
                success: function(response)
                {
                    if(response.success)
                    {
                        window.location = response.data.userURL;
                    }
                    else
                    {
                        if(response.error == 'noInDatabase')
                            showElement('#wrongUsernameOrPass');
                    }
                }
            })
        }
    });
}
function showElement(id)
{
    $(id).show(250);
}
function hideElement(id)
{
    $(id).hide(250);
}
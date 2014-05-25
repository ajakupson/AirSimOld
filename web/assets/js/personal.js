$(document).ready(function()
{
    var dates = 0;
    $('.inputDate').each(function()
    {
        dates++;
        $(this).attr('id', 'date' + dates);
        $('#date' + dates).datepicker
        ({
            showOn: 'button',
            buttonImage: './../../assets/images/styles/datepickerIcon.png',
            buttonImageOnly: true,
            dateFormat: 'yy.mm.dd'

        })
    });

    updateMainInfo();
    updateAdditionalInfo();

    cloneHighEducationForm();
    saveHighEducation();
    deleteHighEducation();

    cloneWorkplaceForm();
    saveWorkplace();
    deleteWorkplace();
});

function updateMainInfo()
{
    $('#updateMainInfo').click(function()
    {
        var form = $(this).closest('form');
        var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
        var successBlock = $(this).closest('.optionsConfiguration').find('.success');
        var errorsBlock = form.find('.errors');

        var name = form.find('input[name="userName"]').val();
        var family = form.find('input[name="userFamily"]').val();
        var phoneOperator = form.find('input[name="phoneOperator"]');

        if(name.length == 0 || family.length == 0 || phoneOperator.length == 0)
        {
            errorsBlock.show();
            return false;
        }

        form.ajaxSubmit
        ({
            url: './../options_update_main_info',
            dataType: 'json',
            type: 'POST',
            beforeSubmit: function()
            {
                ajaxIcon.show();
            },
            success: function(response)
            {
                if(response.success)
                {
                    ajaxIcon.hide();
                    errorsBlock.hide();
                    successBlock.show(250);
                    successBlock.delay(3000).fadeOut();
                }
                else
                {
                    console.log(response.error);
                }
            }
        });
    });
}

function updateAdditionalInfo()
{
    $('#updateAdditionalInfo').click(function()
    {
        var form = $(this).closest('form');
        var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
        var successBlock = $(this).closest('.optionsConfiguration').find('.success');

        form.ajaxSubmit
        ({
            url: './../options_update_additional_info',
            dataType: 'json',
            type: 'POST',
            beforeSubmit: function()
            {
                ajaxIcon.show();
            },
            success: function(response)
            {
                if(response.success)
                {
                    ajaxIcon.hide();
                    successBlock.show(250);
                    successBlock.delay(3000).fadeOut();
                }
                else
                {
                    console.log(response.error);
                }
            }
        });
    });
}

function cloneHighEducationForm()
{
    $('.addHighEducation').click(function()
    {
        var cnt = 0;
        $('.highEducation').each(function()
        {
            if($(this).find('.highEducationRecordId').val() == 0)
                cnt++;
        });
        if(cnt == 1)
        {
            var newForm = $('.highEducation:last').clone(true);

            /* For DatePicker Cloning */
            newForm.find('.inputDate').siblings('.ui-datepicker-trigger, .ui-datepicker-apply').remove();
            newForm.find('.inputDate')
                .removeClass('hasDatepicker')
                .removeData('datepicker')
                .unbind()
                .datepicker({
                    showOn: 'button',
                    buttonImage: './../../assets/images/styles/datepickerIcon.png',
                    buttonImageOnly: true,
                    dateFormat: 'yy.mm.dd'
                });
            /* ***** */

            newForm.prependTo('#highEducationWrapper');
            newForm.fadeIn();
        }
    });
}

function saveHighEducation()
{
    $('.saveHighEducation').click(function()
    {
        var recId = $(this).closest('form[name="highEducationForm"]').find('.highEducationRecordId').val();
        var form = $(this).closest('form');
        if(recId == 0)
            addHighEducation(form);
        else
            editHighEducation(form);
    })
}
function addHighEducation(form)
{
    var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
    var successBlock = form.find('.success');
    var errorsBlock = form.find('.errors');

    var university = form.find('input[name="university"]').val();
    var speciality = form.find('input[name="speciality"]').val();
    var degree = form.find('input[name="degree"]').val();
    var startYear = form.find('input[name="startYear"]').val();

    if(university.length == 0 || speciality.length == 0 || degree.length == 0 ||
        startYear.length == 0)
    {
        errorsBlock.show();
        return false;
    }

    errorsBlock.hide();

    var recordIdInput = form.find('.highEducationRecordId');
    form.ajaxSubmit
    ({
        url:'./../options_add_high_education',
        dataType:  'json',
        type: 'POST',
        beforeSubmit:function()
        {
            ajaxIcon.show();
        },
        success: function(response)
        {
            if(response.success)
            {
                ajaxIcon.hide();
                successBlock.show(250);
                recordIdInput.val(response.data.highEducationId);
            }
            else
            {
                // Error Process
                console.log(response.error);
            }
        }

    });
}
function editHighEducation(form)
{
    var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
    var successBlock = form.find('.success');
    var errorsBlock = form.find('.errors');

    var university = form.find('input[name="university"]').val();
    var speciality = form.find('input[name="speciality"]').val();
    var degree = form.find('input[name="degree"]').val();
    var startYear = form.find('input[name="startYear"]').val();

    if(university.length == 0 || speciality.length == 0 || degree.length == 0 ||
        startYear.length == 0)
    {
        errorsBlock.show();
        return false;
    }

    errorsBlock.hide();

    form.ajaxSubmit
    ({
        url:'./../options_edit_high_education',
        dataType:  'json',
        type: 'POST',
        beforeSubmit:function()
        {
            ajaxIcon.show();
        },
        success: function(response)
        {
            if(response.success)
            {
                ajaxIcon.hide();
                successBlock.show(250);
            }
            else
            {
                // Error Process
                console.log(response.error);
            }
        }

    });
}
function deleteHighEducation()
{
    var form;
    var confirmDialog;
    var dialogOperation;

    $('.deleteHighEducation').click(function()
    {
        createPopUpWindow('19%', 'dialogPopUp', event);

        form = $(this).closest('form');
        confirmDialog = $('#dialogPopUp');
        $('#dialogOperation').val('deleteHighEducation');
    });

    $('#confirmYes').click(function()
    {
        dialogOperation = $('#dialogOperation').val();

        if(dialogOperation == 'deleteHighEducation')
        {
            confirmDialog.find('#popUpClose').trigger('click');
            var highEducationRecordId = form.find('.highEducationRecordId').val();
            var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
            var formContainer = form.closest('.highEducation');

            if(highEducationRecordId != 0)
            {
                $.ajax
                ({
                    url: './../options_delete_high_education',
                    dataType: 'json',
                    data:
                    {
                        highEducationRecordId: highEducationRecordId
                    },
                    type: 'POST',
                    beforeSubmit:function()
                    {
                        ajaxIcon.show();
                    },
                    success: function(response)
                    {
                        if(response.success)
                        {
                            formContainer.remove();
                            ajaxIcon.hide();
                        }
                        else
                        {

                        }
                    }
                });
            }
        }
    });

    $('#confirmNo').click(function()
    {
        dialogOperation = $('#dialogOperation').val();

        if(dialogOperation == 'deleteHighEducation')
        {
            confirmDialog.find('#popUpClose').trigger('click');
        }
    });
}

function cloneWorkplaceForm()
{
    $('.addWorkplace').click(function()
    {
        var cnt = 0;
        $('.workplaces').each(function()
        {
            if($(this).find('.workplaceRecordId').val() == 0)
                cnt++;
        });
        if(cnt == 1)
        {
            var newForm = $('.workplaces:last').clone(true);

            /* For DatePicker Cloning */
            newForm.find('.inputDate').siblings('.ui-datepicker-trigger, .ui-datepicker-apply').remove();
            newForm.find('.inputDate')
                .removeClass('hasDatepicker')
                .removeData('datepicker')
                .unbind()
                .datepicker({
                    showOn: 'button',
                    buttonImage: './../../assets/images/styles/datepickerIcon.png',
                    buttonImageOnly: true,
                    dateFormat: 'yy.mm.dd'
                });
            /* ***** */

            newForm.prependTo('#workplacesWrapper');
            newForm.fadeIn();
        }
    });
}
function saveWorkplace()
{
    $('.saveWorkplace').click(function()
    {
        var recId = $(this).closest('form[name="workplacesForm"]').find('.workplaceRecordId').val();
        var form = $(this).closest('form');
        if(recId == 0)
            addWorkplace(form);
        else
            editWorkplace(form);
    })
}
function addWorkplace(form)
{
    var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
    var successBlock = form.find('.success');
    var errorsBlock = form.find('.errors');

    var workplace = form.find('input[name="workplace"]').val();
    var position = form.find('input[name="position"]').val();
    var startYear = form.find('input[name="startYear"]').val();

    if(workplace.length == 0 || position.length == 0 || startYear.length == 0)
    {
        errorsBlock.show();
        return false;
    }

    errorsBlock.hide();

    var recordIdInput = form.find('.workplaceRecordId');
    form.ajaxSubmit
    ({
        url:'./../options_add_workplace',
        dataType:  'json',
        type: 'POST',
        beforeSubmit:function()
        {
            ajaxIcon.show();
        },
        success: function(response)
        {
            if(response.success)
            {
                ajaxIcon.hide();
                successBlock.show(250);
                recordIdInput.val(response.data.workplaceId);
            }
            else
            {
                // Error Process
                console.log(response.error);
            }
        }

    });
}
function editWorkplace(form)
{
    var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
    var successBlock = form.find('.success');
    var errorsBlock = form.find('.errors');

    var workplace = form.find('input[name="workplace"]').val();
    var position = form.find('input[name="position"]').val();
    var startYear = form.find('input[name="startYear"]').val();

    if(workplace.length == 0 || position.length == 0 || startYear.length == 0)
    {
        errorsBlock.show();
        return false;
    }

    errorsBlock.hide();

    var recordIdInput = form.find('.workplaceRecordId');
    form.ajaxSubmit
    ({
        url:'./../options_edit_workplace',
        dataType:  'json',
        type: 'POST',
        beforeSubmit:function()
        {
            ajaxIcon.show();
        },
        success: function(response)
        {
            if(response.success)
            {
                ajaxIcon.hide();
                successBlock.show(250);
            }
            else
            {
                // Error Process
                console.log(response.error);
            }
        }

    });
}
function deleteWorkplace()
{
    var form;
    var confirmDialog;
    var dialogOperation;

    $('.deleteWorkplace').click(function()
    {
        createPopUpWindow('19%', 'dialogPopUp', event);

        form = $(this).closest('form');
        confirmDialog = $('#dialogPopUp');
        $('#dialogOperation').val('deleteWorkplace');

    });

    $('#confirmYes').click(function()
    {
        dialogOperation = $('#dialogOperation').val();

        if(dialogOperation == 'deleteWorkplace')
        {
            confirmDialog.find('#popUpClose').trigger('click');
            var workplaceRecordId = form.find('.workplaceRecordId').val();
            var ajaxIcon = form.closest('.optionsConfiguration').find('.ajaxWaitSmall');
            var formContainer = form.closest('.workplaces');

            if(workplaceRecordId != 0)
            {
                $.ajax
                ({
                    url: './../options_delete_workplace',
                    dataType: 'json',
                    data:
                    {
                        workplaceRecordId: workplaceRecordId
                    },
                    type: 'POST',
                    beforeSubmit:function()
                    {
                        ajaxIcon.show();
                    },
                    success: function(response)
                    {
                        if(response.success)
                        {
                            formContainer.remove();
                            ajaxIcon.hide();
                        }
                        else
                        {

                        }
                    }
                });
            }
        }
    });

    $('#confirmNo').click(function()
    {
        dialogOperation = $('#dialogOperation').val();

        if(dialogOperation == 'deleteWorkplace')
        {
            confirmDialog.find('#popUpClose').trigger('click');
        }
    });
}
jQuery(function()
{

    //If loading a saved form from your database, put the ID here. Example id is "1".
    var formID = 0;
    function getParam(param)
    {
                  var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                  for (var i=0;i<url.length;i++)
                  {
                         var params = url[i].split("=");
                         if(params[0] == param)
                          return params[1];
                  }
                  return false;
    }
    //Adds new field with animation
    jQuery("#add-field a").click(function()
    {
        event.preventDefault();
        jQuery(addField(jQuery(this).data('type'))).appendTo('#form-fields').hide().slideDown('fast');
        jQuery('#form-fields').sortable();
    });
    //Removes fields and choices with animation
    jQuery("#lupe").on("click", ".delete", function()
    {
        if (confirm('Вы уверены ?'))
        {
            var $this = jQuery(this);
            $this.parent().slideUp( "slow", function()
            {
                $this.parent().remove()
            });
        }
    });

    //Makes fields required
    jQuery("#lupe").on("click", ".toggle-required", function()
    {
        requiredField(jQuery(this));
    });

    //Makes choices selected
    jQuery("#lupe").on("click", ".toggle-selected", function()
    {
        selectedChoice(jQuery(this));
    });

    //Adds new choice to field with animation
    jQuery("#lupe").on("click", ".add-choice", function()
    {
        jQuery(addChoice()).appendTo(jQuery(this).prev()).hide().slideDown('fast');
        jQuery('.choices ul').sortable();
    });

    //Saving form
    jQuery("#lupe").submit(function(event)
    {
        event.preventDefault();

        //Loop through fields and save field data to array
        var fields = [];
        var $this = jQuery(this);
        var formName = $this.find('.lp-form-name').val();
        var formSubject = $this.find('.lp-form-mail-subject').val();
        var formTo = $this.find('.lp-form-mail-to').val();
        var formFrom = $this.find('.lp-form-mail-from').val();

        jQuery('.field').each(function()
        {
            var $this = jQuery(this);
            //field type
            var fieldType = $this.data('type');
            //field label
            var fieldLabel = $this.find('.field-label').val();
            //field name
            var uniqueID = Math.floor(Math.random()*999999)+1;
            var fieldName = fieldLabel.toLowerCase().replace(/\s/g, "_")+"_"+ uniqueID;
            //field required
            var fieldReq = $this.hasClass('required') ? 1 : 0;
            //check if this field has choices
            if($this.find('.choices li').length >= 1)
            {
                var choices = [];
                $this.find('.choices li').each(function()
                {
                    var $thisChoice = jQuery(this);
                    //choice label
                    var choiceLabel = $thisChoice.find('.choice-label').val();
                    //choice selected
                    var choiceSel = $thisChoice.hasClass('selected') ? 1 : 0;
                    choices.push({
                        label: choiceLabel,
                        sel: choiceSel
                    });
                });
            }

            fields.push({
                type: fieldType,
                label: fieldLabel,
                name: fieldName,
                req: fieldReq,
                choices: choices
            });

        });

        var frontEndFormHTML = '';
        var formID = 0;
        var formID = getParam("form_id");

        var $form = jQuery(this);
        // check if the input is valid
        if(! $form.valid()) return false;
        //Save form to database

        jQuery('#lupe-loader').show();
        var formdata = JSON.stringify(fields);

        jQuery.ajax({
            type: "POST",
            dataType: 'json',
            url: ajax_form_object.ajaxurl,
            data: {
                'action' : 'save_lupe_form', //calls wp_ajax_nopriv_ajaxlogin
                'security': ajax_form_object.ajax_nonce,
                'formId' : formID,
                'formFields' : formdata,
                'formName': formName,
                'formSubject': formSubject,
                'formTo' : formTo,
                'formFrom' : formFrom },
            success: function (msg)
            {
                jQuery('#lupe-loader').hide();
                //console.log(formID);
                //alert(formdata);
                jQuery('.alert').removeClass('hide');
                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                //Demo only
                jQuery('.alert p').val(msg);
                //window.location.href = "../wp-admin/admin.php?page=softech-form-builder-list";
            }
        });

    });

    //load saved form
    var formID = getParam("form_id");
    loadForm(formID);

});

//Add field to builder
function addField(fieldType)
{

    var hasRequired, hasChoices;
    var includeRequiredHTML = '';
    var includeChoicesHTML = '';

    switch (fieldType)
    {
        case 'text':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'email':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'tel':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'date':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'time':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'password':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'textarea':
            hasRequired = true;
            hasChoices = false;
            break;
        case 'select':
            hasRequired = true;
            hasChoices = true;
            break;
        case 'radio':
            hasRequired = true;
            hasChoices = true;
            break;
        case 'checkbox':
            hasRequired = false;
            hasChoices = true;
            break;
        case 'agree':
            //required "agree to terms" checkbox
            hasRequired = false;
            hasChoices = false;
            break;
    }

    if (hasRequired)
    {
        includeRequiredHTML = '' +
            '<label>Обязательное поле ' +
            '<input class="toggle-required" type="checkbox">' +
            '</label>'
    }

    if (hasChoices)
    {
        includeChoicesHTML = '' +
            '<div class="choices">' +
            '<ul></ul>' +
            '<button type="button" class="add-choice"><span>+</span>Добавить элемент списка</button>' +
            '</div>'
    }

    return '' +
        '<div class="field" data-type="' + fieldType + '">' +
        '<button type="button" style="color: red;font-family: inherit;font-weight: 500;" class="delete">X</button>' +
        '<h3>' + fieldType + '</h3>' +
        '<label>Название поля:' +
        '<input type="text" class="field-label" required>' +
        '</label>' +
        includeRequiredHTML +
        includeChoicesHTML +
        '</div>'
}

//Make builder field required
function requiredField($this)
{
    if (!$this.parents('.field').hasClass('required'))
    {
        //Field required
        $this.parents('.field').addClass('required');
        $this.attr('checked','checked');
    }
    else
    {
        //Field not required
        $this.parents('.field').removeClass('required');
        $this.removeAttr('checked');
    }
}

function selectedChoice($this)
{
    if (! $this.parents('li').hasClass('selected'))
    {
        //Only checkboxes can have more than one item selected at a time
        //If this is not a checkbox group, unselect the choices before selecting
        if ($this.parents('.field').data('type') != 'checkbox')
        {
            $this.parents('.choices').find('li').removeClass('selected');
            $this.parents('.choices').find('.toggle-selected').not($this).removeAttr('checked');
        }

        //Make selected
        $this.parents('li').addClass('selected');
        $this.attr('checked','checked');

    }
    else
    {
        //Unselect
        $this.parents('li').removeClass('selected');
        $this.removeAttr('checked');

    }
}

//Builder HTML for select, radio, and checkbox choices
function addChoice()
{
    return '' +
        '<li>' +
        '<label>Элемент: ' +
        '<input type="text" class="choice-label" placeholder="Enter Your Option Name" required>' +
        '</label>' +
        '<label>Значение по умолчанию ' +
        '<input class="toggle-selected" type="checkbox">' +
        '</label>' +
        '<button type="button" class="delete"></button>' +
        '</li>'
}

//Loads a saved form from your database into the builder
function loadForm(formID)
{
    jQuery.ajax({

            type: "GET",
            dataType: 'json',
            url: ajax_form_object.ajaxurl,
            data: {

                'action' : 'load_lupe_form', //calls wp_ajax_nopriv_ajaxlogin
                'formID' : formID
            },
            success: function (data)
            {
        if (data)
        {
            //go through each saved field object and render the builder
            jQuery.each( data, function( ko, viv )
            {
                //Add the field
                jQuery(addField(viv['type'])).appendTo('#form-fields').hide().slideDown('fast');
                var $currentField = jQuery('#form-fields .field').last();

                //Add the label
                $currentField.find('.field-label').val(viv['label']);

                //Is it required?
                if (viv['req'])
                {
                    requiredField($currentField.find('.toggle-required'));
                }

                //Any choices?
                if (viv['choices'])
                {
                    jQuery.each( viv['choices'], function( ko, viv )
                    {
                        //add the choices
                        $currentField.find('.choices ul').append(addChoice());

                        //Add the label
                        $currentField.find('.choice-label').last().val(viv['label']);

                        //Is it selected?
                        if (viv['sel'])
                        {
                            selectedChoice($currentField.find('.toggle-selected').last());
                        }
                    });
                }

            });

            jQuery('#form-fields').sortable();
            jQuery('.choices ul').sortable();
        }
    }
    });
}

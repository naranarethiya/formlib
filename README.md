# Formlib
The Formlib file contains functions that assist in working with forms and filling data in form input by passing array as database result.


## What is Form Model Binding?

Normally, when we create an edit form, you would pull in the data of the item you want to edit. Then you would use that to populate your form’s input fields.

With Form Model Binding, your edit forms are automatically populated based on the item you passed to form model array.

## Inspired from Laravel

First i see this feature in laravel  [here](https://laravel.com/docs/4.2/html#form-model-binding), Its awesome feature you just need pass the database result object and Form Library automatically populate the form input based on field name and result object. 

Mostly for quick development purpose i prefer codeigniter and there is no concept in codeigniter for model binding, so here is stand alone solution for formModel binding, you can use this with codeigniter as well as with core PHP or any other framework.

## Inherited from Codeigniter [formHelper](https://www.codeigniter.com/userguide3/helpers/form_helper.html)
I Fill more comfortable with codeigniter, its has [formHelper](https://www.codeigniter.com/userguide3/helpers/form_helper.html), which full fill all form generation needs, so i just copy the form helper and modify it according to needs. 

Formlib inherited from codeigniter form_helper so no need for detailed documentation you can view detailed documentation on [form_helper here](https://www.codeigniter.com/userguide3/helpers/form_helper.html). I given more focus to input name so every function's first argument contain input name here is example. 
	
	required_once('Form.php');
	$form=new Form();`

	$data = array(
        'id'            => 'username',
        'class'         => 'form-control',
        'maxlength'     => '100'
	);

	echo $form->form_input('username',$data);

Will proceed
	`<input type="text" name="username" id="username" class="form-control" maxlength="100" />`

## Form model Example
To use form's model binding functionality, Use function form_model function for form open tag, form_model accept model array as first argument. Check below function argument of form_model
		
	form_model($model_array=array(), $action_url = '', $extra_attributes = array(), $hidden = array() )

form_model will return `<form>` tag with specified attribute.

Make sure that $model_array is contain element with same name as given to fields, formLib is fill the fields based on array element name and field name. check below example.

	<?php 
		required_once('Form.php');
		$form=new Form();

		$model_array=array(
			'full_name'=>'Naran Arethiya',
			'email_address'=>'email.naran@gmail.com'
		);
		/* Form Opening tag  */
		echo $form->form_model($model_array, 'submit.php');
	
        $attribute=array(
            'class'=>'form-control',
            'placeholder'=>"First name"
        );
        echo $form_obj->form_input('first_name',$attribute);

        $attribute=array(
            'ype'=>'email',
            'class'=>'form-control',
            'placeholder'=>"Email"
        );
        echo $form_obj->form_input('email_address',$attribute);

        /* Form Closing tag  */
    	echo $form->form_close(); 
    ?>

In above example fields first_name and email_address will get filled with 'Naran Arethiya' and 'email.naran@gmail.com' respectively.

For more detail usage checkout the examples in example folder

## Function Reference

	form_model($model_array=array(), $action_url = '', $extra_attributes = array(), $hidden = array() )
	form_open_multipart($action = '', $attributes = array(), $hidden = array())
	form_hidden($name, $value = '', $recursing = FALSE)
	form_input($name, $data = '', $value = '', $extra = '')
	form_password($name, $data = '', $value = '', $extra = '')
	form_upload($name, $data = '', $value = '', $extra = '')
	form_textarea($name, $data = '', $value = '', $extra = '')
	form_multiselect($name, $options = array(), $selected = array(), $data='',  $extra = '')
	form_dropdown($name, $options = array(), $selected = array(), $data = '',  $extra = '', $multiple=false)
	form_country_dropdown($name, $selected = array(), $data = '',  $extra = '', $multiple=false)
	form_dropdown_fromdatabase($name,$array,$key,$value,$selected=false,$other=false,$defaultoption="SELECT", $datavalue=false)
	form_checkbox($name, $value = '', $data = '', $checked = FALSE, $extra = '')
	form_radio($name, $value = '', $data = '',  $checked = FALSE, $extra = '')
	form_submit($data = '', $value = '', $extra = '')
	form_close($extra = '')


Its highly Recommended to read codeigniter documentation for form_helper from [HERE](https://www.codeigniter.com/userguide3/helpers/form_helper.html)

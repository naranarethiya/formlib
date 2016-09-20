# Formlib
PHP Form control generation library with database result array model binding.

## What is Form Model Binding?

Normally, when we create an edit form, you would pull in the data of the item you want to edit. Then you would use that to populate your form’s input fields.

With Form Model Binding, your edit forms are automatically populated based on the item you passed to form model array.

## Inspired from Laravel

First i see this feature in laravel  [here](https://laravel.com/docs/4.2/html#form-model-binding), Its awesome feature you just need pass the database result object and formLibrary automatically fill the form input. Mostly for quick development purpose i preper codeigniter so here is stand alone solution for formModel binding.

## Inherited from Codeigniter
I Fill more comfitable with codeigniter, codeigniter has [formHelper library](https://www.codeigniter.com/userguide3/helpers/form_helper.html) which full fill all form generation needs, so i just copy the form library and modify it according to needs. this library inherited from codeiniter form_helper so no need for detailed documentation you can view detailed documentation form [form_helper here](https://www.codeigniter.com/userguide3/helpers/form_helper.html). I given more focus to input name so every function's first argument contain input name here is example. 
	`required_once('Form.php');
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
To use form's model binding functionality, Use function form_model function for form open tag, form_model accept model array as first argument.

Mostly prefer to use form_model function instad of using form_open or form_open_multipart, If you are not using binding just pass blank array.

For more detail usage checkout the examples in example folder

## Custom Function Reference

function | Description | Example 
---------|-------------|---------
form_model([$model_array=array() [, $action = ''[, $attributes = array()[, $hidden = array()]]]])| An HTML multipart form opening tag with model binding initializing | $form->form_model($model_array,'submit.php');
form_country_dropdown($name, $selected = array(), $option = '',  $extra = '', $multiple=false)|Generate Country dropdown/ Select list| $form('country','',array('class'=>'form-control'))
form_dropdown_fromdatabase($name,$option_list,$key,$value,$selected=false,$other=false,$defaultoption="SELECT") | Generate Select box directly from database array result| $form->form_dropdown_fromdatabase('users_list',$database_array,'user_id','username','', array('class'=>'form-control'))

Its highly Recommened to read codeiniter documentation for form_helper from [HERE](https://www.codeigniter.com/userguide3/helpers/form_helper.html)

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form {

	public $model_array=false;
	/**
	 * Form Declaration
	 *
	 * Creates the opening portion of the form.
	 *
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	function form_open($action = '', $attributes = array(), $hidden = array())
	{
		$CI =& get_instance();

		// If no action is provided then set to the current url
		if ( ! $action)
		{
			$action = $CI->config->site_url($CI->uri->uri_string());
		}
		// If an action is not a full URL then turn it into one
		elseif (strpos($action, '://') === FALSE)
		{
			$action = $CI->config->site_url($action);
		}

		$attributes = $this->_attributes_to_string($attributes);

		if (stripos($attributes, 'method=') === FALSE)
		{
			$attributes .= ' method="post"';
		}

		if (stripos($attributes, 'accept-charset=') === FALSE)
		{
			$attributes .= ' accept-charset="'.strtolower(config_item('charset')).'"';
		}

		$form = '<form action="'.$action.'"'.$attributes.">\n";

		// Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
		if ($CI->config->item('csrf_protection') === TRUE && strpos($action, $CI->config->base_url()) !== FALSE && ! stripos($form, 'method="get"'))
		{
			$hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
		}

		if (is_array($hidden))
		{
			foreach ($hidden as $name => $value)
			{
				$form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value).'" style="display:none;" />'."\n";
			}
		}

		return $form;
	}


	/**
	 * Form Declaration with model variable
	 *
	 * Creates the opening portion of the form.
	 *
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	function form_model($model, $action = '', $attributes = array(), $hidden = array()){
		$this->model_array=$model;
		return $this->form_open_multipart($action, $attributes, $hidden);
	}

// ------------------------------------------------------------------------


	/**
	 * Form Declaration - Multipart type
	 *
	 * Creates the opening portion of the form, but with "multipart/form-data".
	 *
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	function form_open_multipart($action = '', $attributes = array(), $hidden = array())
	{
		if (is_string($attributes))
		{
			$attributes .= ' enctype="multipart/form-data"';
		}
		else
		{
			$attributes['enctype'] = 'multipart/form-data';
		}

		return $this->form_open($action, $attributes, $hidden);
	}

// ------------------------------------------------------------------------


	/**
	 * Hidden Input Field
	 *
	 * Generates hidden fields. You can pass a simple key/value string or
	 * an associative array with multiple values.
	 *
	 * @param	mixed	$name		Field name
	 * @param	string	$value		Field value
	 * @param	bool	$recursing
	 * @return	string
	 */
	function form_hidden($name, $value = '', $recursing = FALSE)
	{
		static $form;

		if ($recursing === FALSE)
		{
			$form = "\n";
		}

		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				/* Set model value  */
				$model_value=$this->set_model_value($name);
				if(!empty($model_value) && empty($val)) {
					$val=$model_value;
				}
				$this->form_hidden($key, $val, TRUE);
			}

			return $form;
		}

		if ( ! is_array($value))
		{
			/* Set model value  */
			$model_value=$this->set_model_value($name);
			if(!empty($model_value) && empty($value)) {
				$value=$model_value;
			}
					
			$form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value)."\" />\n";
		}
		else
		{
			foreach ($value as $k => $v)
			{
				$k = is_int($k) ? '' : $k;
				/* Set model value  */
				$model_value=$this->set_model_value($name.'['.$k.']');
				if(!empty($model_value) && empty($v)) {
					$v=$model_value;
				}

				$this->form_hidden($name.'['.$k.']', $v, TRUE);
			}
		}

		return $form;
	}

// ------------------------------------------------------------------------


	/**
	 * Text Input Field
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_input($name, $data = '', $value = '', $extra = '')
	{
		$defaults = array(
			'type' => 'text',
			'value' => $value
		);

		/*set name*/
		if(is_array($data)) {
			unset($data['name']);
			$data=array("name"=>$name)+$data;
		}
		else {
			$data['name']=$name;
		}
		
		/* Set model value  */
		$model_value=$this->set_model_value($name);
		if(!empty($model_value) && empty($value)) {
			$data['value']=$model_value;
		}

		return '<input '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra)." />\n";
	}

// ------------------------------------------------------------------------


	/**
	 * Password Field
	 *
	 * Identical to the input function but adds the "password" type
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_password($name, $data = '', $value = '', $extra = '')
	{
		is_array($data) OR $data = array('name' => $data);
		$data['type'] = 'password';
		return $this->form_input($name, $data, $value, $extra);
	}

// ------------------------------------------------------------------------


	/**
	 * Upload Field
	 *
	 * Identical to the input function but adds the "file" type
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_upload($name, $data = '', $value = '', $extra = '')
	{
		$defaults = array('type' => 'file');
		is_array($data) OR $data = array('name' => $name);

		if(is_array($data)) {
			/* Overwrite name in data */
			unset($data['name']);
			$data=array("name"=>$name)+$data;
		}

		return '<input '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra)." />\n";
	}

// ------------------------------------------------------------------------


	/**
	 * Textarea field
	 *
	 * @param	mixed	$data
	 * @param	string	$value
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_textarea($name, $data = '', $value = '', $extra = '')
	{
		$defaults = array();

		/* Overwrite name from data */
		if(is_array($data)) {
			unset($data['name']);
			$data=array('name'=>$name)+$data;
		}
		else {
			$data['name']=$name;
		}

		if ( ! is_array($data) OR ! isset($data['value']))
		{
			$val = $value;
		}
		else
		{
			$val = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
		}

		/* Set model value  */
		$model_value=$this->set_model_value($name);
		if(!empty($model_value) && empty($val)) {
			$val=$model_value;
		}

		return '<textarea '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra).'>'
			.html_escape($val)
			."</textarea>\n";
	}

// ------------------------------------------------------------------------


	/**
	 * Multi-select menu
	 *
	 * @param	string
	 * @param	array
	 * @param	mixed
	 * @param	mixed
	 * @return	string
	 */
	function form_multiselect($name, $options = array(), $selected = array(), $data='',  $extra = '')
	{
		$extra = $this->_attributes_to_string($extra);
		if (stripos($extra, 'multiple') === FALSE)
		{
			$extra .= ' multiple="multiple"';
		}

		return $this->form_dropdown($name, $data, $options, $selected, $extra,'multiselect');
	}


// --------------------------------------------------------------------


	/**
	 * Drop-down Menu
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_dropdown($name, $options = array(), $selected = array(), $data = '',  $extra = '', $multiple=false)
	{
		$defaults = array();

		/* Set name */
		if(is_array($data)) {
			unset($data['name']);
			$data=array('name'=>$name)+$data;
		}
		else {
			$data['name']=$name;
		}

		/* Set selected */
		if (empty($selected) && $this->model_array) {
			$selected=$this->set_model_value($name, $multiple);
		}

		if (is_array($data))
		{
			if (isset($data['selected']))
			{
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options']))
			{
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
		}
		else
		{
			$defaults = array('name' => $data);
		}

		is_array($selected) OR $selected = array($selected);
		is_array($options) OR $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected))
		{
			if (is_array($data))
			{
				if (isset($data['name'], $_POST[$data['name']]))
				{
					$selected = array($_POST[$data['name']]);
				}
			}
			elseif (isset($_POST[$data]))
			{
				$selected = array($_POST[$data]);
			}
		}

		$extra = $this->_attributes_to_string($extra);

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select '.rtrim($this->_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val))
			{
				if (empty($val))
				{
					continue;
				}

				$form .= '<optgroup label="'.$key."\">\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
					$form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.'>'
						.(string) $optgroup_val."</option>\n";
				}

				$form .= "</optgroup>\n";
			}
			else
			{
				$form .= '<option value="'.html_escape($key).'"'
					.(in_array($key, $selected) ? ' selected="selected"' : '').'>'
					.(string) $val."</option>\n";
			}
		}

		return $form."</select>\n";
	}
//----------------------------------------------------------------------

	function form_country_dropdown($name, $selected = array(), $data = '',  $extra = '', $multiple=false) {
		$country_list=array(
			""=>"Select Country",
			'Afghanistan'=>'Afghanistan',
			'Aland Islands'=>'Aland Islands',
			'Albania'=>'Albania',
			'American Samoa'=>'American Samoa',
			'Andorra'=>'Andorra',
			'Angola'=>'Angola',
			'Anguilla'=>'Anguilla',
			'Antarctica'=>'Antarctica',
			'Antigua and Barbuda'=>'Antigua and Barbuda',
			'Argentina'=>'Argentina',
			'Armenia'=>'Armenia',
			'Aruba'=>'Aruba',
			'Australia'=>'Australia',
			'Austria'=>'Austria',
			'Azerbaijan'=>'Azerbaijan',
			'Bahamas'=>'Bahamas',
			'Bahrain'=>'Bahrain',
			'Bangladesh'=>'Bangladesh',
			'Barbados'=>'Barbados',
			'Belarus'=>'Belarus',
			'Belgium'=>'Belgium',
			'Belize'=>'Belize',
			'Benin'=>'Benin',
			'Bermuda'=>'Bermuda',
			'Bhutan'=>'Bhutan',
			'Bolivia'=>'Bolivia',
			'Bosnia and Herzegovina'=>'Bosnia and Herzegovina',
			'Botswana'=>'Botswana',
			'Bouvet Island'=>'Bouvet Island',
			'Brazil'=>'Brazil',
			'British Indian Ocean Territory'=>'British Indian Ocean Territory',
			'British Virgin Islands'=>'British Virgin Islands',
			'Brunei'=>'Brunei',
			'Bulgaria'=>'Bulgaria',
			'Burkina Faso'=>'Burkina Faso',
			'Burundi'=>'Burundi',
			'Cambodia'=>'Cambodia',
			'Cameroon'=>'Cameroon',
			'Canada'=>'Canada',
			'Cape Verde'=>'Cape Verde',
			'Caribbean Netherlands'=>'Caribbean Netherlands',
			'Cayman Islands'=>'Cayman Islands',
			'Central African Republic'=>'Central African Republic',
			'Chad'=>'Chad',
			'Chile'=>'Chile',
			'China'=>'China',
			'Christmas Island'=>'Christmas Island',
			'Cocos (Keeling) Islands'=>'Cocos (Keeling) Islands',
			'Colombia'=>'Colombia',
			'Comoros'=>'Comoros',
			'Congo (Brazzaville)'=>'Congo (Brazzaville)',
			'Congo (Kinshasa)'=>'Congo (Kinshasa)',
			'Cook Islands'=>'Cook Islands',
			'Costa Rica'=>'Costa Rica',
			'Croatia'=>'Croatia',
			'Cuba'=>'Cuba',
			'Curaçao'=>'Curaçao',
			'Cyprus'=>'Cyprus',
			'Czech Republic'=>'Czech Republic',
			'Denmark'=>'Denmark',
			'Djibouti'=>'Djibouti',
			'Dominica'=>'Dominica',
			'Dominican Republic'=>'Dominican Republic',
			'Ecuador'=>'Ecuador',
			'Egypt'=>'Egypt',
			'El Salvador'=>'El Salvador',
			'Equatorial Guinea'=>'Equatorial Guinea',
			'Eritrea'=>'Eritrea',
			'Estonia'=>'Estonia',
			'Ethiopia'=>'Ethiopia',
			'Falkland Islands'=>'Falkland Islands',
			'Faroe Islands'=>'Faroe Islands',
			'Fiji'=>'Fiji',
			'Finland'=>'Finland',
			'France'=>'France',
			'French Guiana'=>'French Guiana',
			'French Polynesia'=>'French Polynesia',
			'French Southern Territories'=>'French Southern Territories',
			'Gabon'=>'Gabon',
			'Gambia'=>'Gambia',
			'Georgia'=>'Georgia',
			'Germany'=>'Germany',
			'Ghana'=>'Ghana',
			'Gibraltar'=>'Gibraltar',
			'Greece'=>'Greece',
			'Greenland'=>'Greenland',
			'Grenada'=>'Grenada',
			'Guadeloupe'=>'Guadeloupe',
			'Guam'=>'Guam',
			'Guatemala'=>'Guatemala',
			'Guernsey'=>'Guernsey',
			'Guinea'=>'Guinea',
			'Guinea-Bissau'=>'Guinea-Bissau',
			'Guyana'=>'Guyana',
			'Haiti'=>'Haiti',
			'Heard Island and McDonald Islands'=>'Heard Island and McDonald Islands',
			'Honduras'=>'Honduras',
			'Hong Kong S.A.R., China'=>'Hong Kong S.A.R., China',
			'Hungary'=>'Hungary',
			'Iceland'=>'Iceland',
			'India'=>'India',
			'Indonesia'=>'Indonesia',
			'Iran'=>'Iran',
			'Iraq'=>'Iraq',
			'Ireland'=>'Ireland',
			'Isle of Man'=>'Isle of Man',
			'Israel'=>'Israel',
			'Italy'=>'Italy',
			'Ivory Coast'=>'Ivory Coast',
			'Jamaica'=>'Jamaica',
			'Japan'=>'Japan',
			'Jersey'=>'Jersey',
			'Jordan'=>'Jordan',
			'Kazakhstan'=>'Kazakhstan',
			'Kenya'=>'Kenya',
			'Kiribati'=>'Kiribati',
			'Kuwait'=>'Kuwait',
			'Kyrgyzstan'=>'Kyrgyzstan',
			'Laos'=>'Laos',
			'Latvia'=>'Latvia',
			'Lebanon'=>'Lebanon',
			'Lesotho'=>'Lesotho',
			'Liberia'=>'Liberia',
			'Libya'=>'Libya',
			'Liechtenstein'=>'Liechtenstein',
			'Lithuania'=>'Lithuania',
			'Luxembourg'=>'Luxembourg',
			'Macao S.A.R., China'=>'Macao S.A.R., China',
			'Macedonia'=>'Macedonia',
			'Madagascar'=>'Madagascar',
			'Malawi'=>'Malawi',
			'Malaysia'=>'Malaysia',
			'Maldives'=>'Maldives',
			'Mali'=>'Mali',
			'Malta'=>'Malta',
			'Marshall Islands'=>'Marshall Islands',
			'Martinique'=>'Martinique',
			'Mauritania'=>'Mauritania',
			'Mauritius'=>'Mauritius',
			'Mayotte'=>'Mayotte',
			'Mexico'=>'Mexico',
			'Micronesia'=>'Micronesia',
			'Moldova'=>'Moldova',
			'Monaco'=>'Monaco',
			'Mongolia'=>'Mongolia',
			'Montenegro'=>'Montenegro',
			'Montserrat'=>'Montserrat',
			'Morocco'=>'Morocco',
			'Mozambique'=>'Mozambique',
			'Myanmar'=>'Myanmar',
			'Namibia'=>'Namibia',
			'Nauru'=>'Nauru',
			'Nepal'=>'Nepal',
			'Netherlands'=>'Netherlands',
			'Netherlands Antilles'=>'Netherlands Antilles',
			'New Caledonia'=>'New Caledonia',
			'New Zealand'=>'New Zealand',
			'Nicaragua'=>'Nicaragua',
			'Niger'=>'Niger',
			'Nigeria'=>'Nigeria',
			'Niue'=>'Niue',
			'Norfolk Island'=>'Norfolk Island',
			'Northern Mariana Islands'=>'Northern Mariana Islands',
			'North Korea'=>'North Korea',
			'Norway'=>'Norway',
			'Oman'=>'Oman',
			'Pakistan'=>'Pakistan',
			'Palau'=>'Palau',
			'Palestinian Territory'=>'Palestinian Territory',
			'Panama'=>'Panama',
			'Papua New Guinea'=>'Papua New Guinea',
			'Paraguay'=>'Paraguay',
			'Peru'=>'Peru',
			'Philippines'=>'Philippines',
			'Pitcairn'=>'Pitcairn',
			'Poland'=>'Poland',
			'Portugal'=>'Portugal',
			'Puerto Rico'=>'Puerto Rico',
			'Qatar'=>'Qatar',
			'Reunion'=>'Reunion',
			'Romania'=>'Romania',
			'Russia'=>'Russia',
			'Rwanda'=>'Rwanda',
			'Saint Barthélemy'=>'Saint Barthélemy',
			'Saint Helena'=>'Saint Helena',
			'Saint Kitts and Nevis'=>'Saint Kitts and Nevis',
			'Saint Lucia'=>'Saint Lucia',
			'Saint Martin (French part)'=>'Saint Martin (French part)',
			'Saint Pierre and Miquelon'=>'Saint Pierre and Miquelon',
			'Saint Vincent and the Grenadines'=>'Saint Vincent and the Grenadines',
			'Samoa'=>'Samoa',
			'San Marino'=>'San Marino',
			'Sao Tome and Principe'=>'Sao Tome and Principe',
			'Saudi Arabia'=>'Saudi Arabia',
			'Senegal'=>'Senegal',
			'Serbia'=>'Serbia',
			'Seychelles'=>'Seychelles',
			'Sierra Leone'=>'Sierra Leone',
			'Singapore'=>'Singapore',
			'Sint Maarten'=>'Sint Maarten',
			'Slovakia'=>'Slovakia',
			'Slovenia'=>'Slovenia',
			'Solomon Islands'=>'Solomon Islands',
			'Somalia'=>'Somalia',
			'South Africa'=>'South Africa',
			'South Georgia and the South Sandwich Islands'=>'South Georgia and the South ,Sandwich ',
			'South Korea'=>'South Korea',
			'South Sudan'=>'South Sudan',
			'Spain'=>'Spain',
			'Sri Lanka'=>'Sri Lanka',
			'Sudan'=>'Sudan',
			'Suriname'=>'Suriname',
			'Svalbard and Jan Mayen'=>'Svalbard and Jan Mayen',
			'Swaziland'=>'Swaziland',
			'Sweden'=>'Sweden',
			'Switzerland'=>'Switzerland',
			'Syria'=>'Syria',
			'Taiwan'=>'Taiwan',
			'Tajikistan'=>'Tajikistan',
			'Tanzania'=>'Tanzania',
			'Thailand'=>'Thailand',
			'Timor-Leste'=>'Timor-Leste',
			'Togo'=>'Togo',
			'Tokelau'=>'Tokelau',
			'Tonga'=>'Tonga',
			'Trinidad and Tobago'=>'Trinidad and Tobago',
			'Tunisia'=>'Tunisia',
			'Turkey'=>'Turkey',
			'Turkmenistan'=>'Turkmenistan',
			'Turks and Caicos Islands'=>'Turks and Caicos Islands',
			'Tuvalu'=>'Tuvalu',
			'U.S. Virgin Islands'=>'U.S. Virgin Islands',
			'Uganda'=>'Uganda',
			'Ukraine'=>'Ukraine',
			'United Arab Emirates'=>'United Arab Emirates',
			'United Kingdom'=>'United Kingdom',
			'United States'=>'United States',
			'United States Minor Outlying Islands'=>'United States Minor Outlying Islands',
			'Uruguay'=>'Uruguay',
			'Uzbekistan'=>'Uzbekistan',
			'Vanuatu'=>'Vanuatu',
			'Vatican'=>'Vatican',
			'Venezuela'=>'Venezuela',
			'Vietnam'=>'Vietnam',
			'Wallis and Futuna'=>'Wallis and Futuna',
			'Western Sahara'=>'Western Sahara',
			'Yemen'=>'Yemen',
			'Zambia'=>'Zambia',
			'Zimbabwe'=>'Zimbabwe',

		);
		
		/* If country list is not exist in model data than add it into it */
		if(isset($this->model_array[$name])) {
			if(array_search($this->model_array[$name], $country_list)===false) {
				$country_list[$this->model_array[$name]]=$this->model_array[$name];
			}
		}

		return $this->form_dropdown($name, $country_list, $selected,$data, $extra, $multiple);
	}


// --------------------------------------------------------------------

	/* 
		Create combobox from database result array 
		accept 7 parameter.
		1 - name of select box or combobox.
		2 - array of values. ex. array( array('key'=>'key1', 'val'=>'value1'), array('key'=>'key2', 'val'=>'value2')  )
		3 - option value column name of select box or combobox in above example key will be right argumant.
		4 - text which will display in select box or combobox in above example val is right argument.
		5 - bydefault selected value in select box or combobox can be array also.
		6 - other HTML or css attributes.
		7 - 'SELECT' will be first option or not.
	*/
	function form_dropdown_fromdatabase($name,$array,$key,$value,$selected=false,$other=false,$defaultoption="SELECT") {

		/* Set selected */
		if (empty($selected) && $this->model_array) {

			if(isset($other['multiple'])) {
				$selected=$this->set_model_value($name, true);
			}
			else {
				$selected=$this->set_model_value($name);
			}
		}

		$other=$this->_attributes_to_string($other);

		if(empty($array)) {
			$output = "<select name=\"{$name}\" ".$other.">";
			if($defaultoption) {
				$output .= "<option value=\"\">".$defaultoption."</option>";    
			}
			$output .= "</select>";
		}
		else{  
			$output = "<select name=\"{$name}\" ".$other.">";
			if($defaultoption) {
				$output .= "<option value=\"\">".$defaultoption."</option>";    
			}
			$keys=array_column($array,$key);
			if(is_array($value)) {
				$args=array();
				$args[]="$this->combine";
				foreach($value as $val) {
					$args[]=array_column($array,$val);
				}
				$vals=call_user_func_array('array_map',$args);
			}
			else {
				$vals=array_column($array,$value);
			}
			$new_array=array_combine($keys,$vals);

			foreach ($new_array as $key => $value) {
				if(is_array($selected)) {
					if (in_array($key,$selected)) {
						$output .= "<option value=\"{$key}\" selected>{$value}</option>";
					} 
					else {
						$output .= "<option value=\"{$key}\">{$value}</option>";
					}
				}
				else {
					if ($selected !== false && $selected == $key) {
						$output .= "<option value=\"{$key}\" selected>{$value}</option>";
					} 
					else {
						$output .= "<option value=\"{$key}\">{$value}</option>";
					}
				}
			}

			$output .= "</select>";
		}
		return $output;
	}

// ------------------------------------------------------------------------


	/**
	 * Checkbox Field
	 *
	 * @param	mixed
	 * @param	string
	 * @param	bool
	 * @param	mixed
	 * @return	string
	 */
	function form_checkbox($name, $value = '', $data = '', $checked = FALSE, $extra = '')
	{
		$defaults = array('type' => 'checkbox','name'=>$name,'value' => $value);
		/* Set name */
		if(is_array($data)) {
			unset($data['name']);
			$data=array('name'=>$name)+$data;
		}
		else {
			$data['name']=$name;
		}

		if (is_array($data) && array_key_exists('checked', $data))
		{
			$checked = $data['checked'];

			if ($checked == FALSE)
			{
				unset($data['checked']);
			}
			else
			{
				$data['checked'] = 'checked';
			}
		}

		if ($checked == TRUE)
		{
			$defaults['checked'] = 'checked';
		}
		else
		{
			unset($defaults['checked']);
		}

		/* Set value from model */
		$checked=$this->set_model_value($name, true);
		if(!empty($value)) {
			if(is_array($checked)) {
				if(array_search($value,$checked)!==false) {
					$data['checked'] = 'checked';
				}
			}
			elseif(is_string($checked) && $checked == $value) {
				$data['checked'] = 'checked';
			}
		}


		return '<input '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra)." />\n";
	}

// ------------------------------------------+------------------------------


	/**
	 * Radio Button
	 *
	 * @param	mixed
	 * @param	string
	 * @param	bool
	 * @param	mixed
	 * @return	string
	 */
	function form_radio($name, $value = '', $data = '',  $checked = FALSE, $extra = '')
	{
		is_array($data) OR $data = array('name' => $data);
		$data['type'] = 'radio';

		return $this->form_checkbox($name, $value, $data, $checked, $extra);
	}

// ------------------------------------------------------------------------


	/**
	 * Submit Button
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_submit($data = '', $value = '', $extra = '')
	{
		$defaults = array(
			'type' => 'submit',
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		return '<input '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra)." />\n";
	}

// ------------------------------------------------------------------------


	/**
	 * Reset Button
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_reset($data = '', $value = '', $extra = '')
	{
		$defaults = array(
			'type' => 'reset',
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		return '<input '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra)." />\n";
	}

// ------------------------------------------------------------------------


	/**
	 * Form Button
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_button($data = '', $content = '', $extra = '')
	{
		$defaults = array(
			'name' => is_array($data) ? '' : $data,
			'type' => 'button'
		);

		if (is_array($data) && isset($data['content']))
		{
			$content = $data['content'];
			unset($data['content']); // content is not an attribute
		}

		return '<button '.$this->_parse_form_attributes($data, $defaults).$this->_attributes_to_string($extra).'>'
			.$content
			."</button>\n";
	}

// ------------------------------------------------------------------------


	/**
	 * Form Label Tag
	 *
	 * @param	string	The text to appear onscreen
	 * @param	string	The id the label applies to
	 * @param	array	Additional attributes
	 * @return	string
	 */
	function form_label($label_text = '', $id = '', $attributes = array())
	{

		$label = '<label';

		if ($id !== '')
		{
			$label .= ' for="'.$id.'"';
		}

		if (is_array($attributes) && count($attributes) > 0)
		{
			foreach ($attributes as $key => $val)
			{
				$label .= ' '.$key.'="'.$val.'"';
			}
		}

		return $label.'>'.$label_text.'</label>';
	}

// ------------------------------------------------------------------------


	/**
	 * Fieldset Tag
	 *
	 * Used to produce <fieldset><legend>text</legend>.  To close fieldset
	 * use form_fieldset_close()
	 *
	 * @param	string	The legend text
	 * @param	array	Additional attributes
	 * @return	string
	 */
	function form_fieldset($legend_text = '', $attributes = array())
	{
		$fieldset = '<fieldset'.$this->_attributes_to_string($attributes).">\n";
		if ($legend_text !== '')
		{
			return $fieldset.'<legend>'.$legend_text."</legend>\n";
		}

		return $fieldset;
	}

// ------------------------------------------------------------------------


	/**
	 * Fieldset Close Tag
	 *
	 * @param	string
	 * @return	string
	 */
	function form_fieldset_close($extra = '')
	{
		return '</fieldset>'.$extra;
	}

// ------------------------------------------------------------------------


	/**
	 * Form Close Tag
	 *
	 * @param	string
	 * @return	string
	 */
	function form_close($extra = '') {
		$this->clear_model();
		return '</form>'.$extra;
	}

// ------------------------------------------------------------------------


	/**
	 * Form Prep
	 *
	 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
	 *
	 * @deprecated	3.0.0	An alias for html_escape()
	 * @param	string|string[]	$str		Value to escape
	 * @return	string|string[]	Escaped values
	 */
	function form_prep($str)
	{
		return html_escape($str, TRUE);
	}

// ------------------------------------------------------------------------


	/**
	 * Parse the form attributes
	 *
	 * Helper function used by some of the form helpers
	 *
	 * @param	array	$attributes	List of attributes
	 * @param	array	$default	Default values
	 * @return	string
	 */
	function _parse_form_attributes($attributes, $default)
	{
		if (is_array($attributes))
		{
			foreach ($default as $key => $val)
			{
				if (isset($attributes[$key]))
				{
					$default[$key] = $attributes[$key];
					unset($attributes[$key]);
				}
			}

			if (count($attributes) > 0)
			{
				$default = array_merge($default, $attributes);
			}
		}

		$att = '';

		foreach ($default as $key => $val)
		{
			if ($key === 'value')
			{
				$val = html_escape($val);
			}
			elseif ($key === 'name' && ! strlen($default['name']))
			{
				continue;
			}

			$att .= $key.'="'.$val.'" ';
		}

		return $att;
	}

// ------------------------------------------------------------------------


	/**
	 * Attributes To String
	 *
	 * Helper function used by some of the form helpers
	 *
	 * @param	mixed
	 * @return	string
	 */
	function _attributes_to_string($attributes)
	{
		if (empty($attributes))
		{
			return '';
		}

		if (is_object($attributes))
		{
			$attributes = (array) $attributes;
		}

		if (is_array($attributes))
		{
			$atts = '';

			foreach ($attributes as $key => $val)
			{
				$atts .= ' '.$key.'="'.$val.'"';
			}

			return $atts;
		}

		if (is_string($attributes))
		{
			return ' '.$attributes;
		}

		return FALSE;
	}

	/**
	* Return value of field from $model_array for input type text, passoword, textarea
	* @param field name
	* @return string 
	*/
	function set_model_value($name, $multiple=false) {
		$data=array(
			'value'=>''
		);
		if($this->model_array) {
			if(isset($this->model_array[$name])) {
				return $this->model_array[$name];
			}
			/* If name contain contain array sign ex. name[] or name[key] */
			elseif((preg_match_all('/\[(.*?)\]/', $name, $matches))) {
				/* If  name[] is exist*/
				if(empty($matches[1][0])) {
					/* Remove [] bracket from $name to extract name from $model_array */
					$ele_name=substr($name,0, -2);
					/* If is array than assign child of that array */
					if(isset($this->model_array[$ele_name]) && is_array($this->model_array[$ele_name])) {

						/* check if not multi dimensional array than return it */
						if($multiple && count($this->model_array[$ele_name]) == count($this->model_array[$ele_name], COUNT_RECURSIVE)) {
							$data['value']=$this->model_array[$ele_name];
						}
						else {
							/* Reset array to get first element */
							reset($this->model_array[$ele_name]);
							$first_key = key($this->model_array[$ele_name]);
							/* Check if array has element*/
							if(!is_null($first_key)) {
								$data['value']=$this->model_array[$ele_name][$first_key];
								/*Remove assigned key*/
								unset($this->model_array[$ele_name][$first_key]);
							}
						}
					}
				}
				/* if name[key] or name[key1][key2] exist */
				else {
					$matches_str=implode('', $matches[0]);
					if(isset($this->model_array[$matches_str])) {
						$data['value']=$this->model_array[$matches_str];
					}
					else {
						/* remove last [key] from name for actual string name */
						$ele_name=str_replace($matches_str,'',$name);
						if(isset($this->model_array[$ele_name])) {
							$element=$this->model_array[$ele_name];
							/* Loop through each matched string to get child value */
							foreach ($matches[1] as $matches_val) {
								if(isset($element[$matches_val])) {
									$element=$element[$matches_val];
								}
							}
							$data['value']=$element;
						}
					}
				}
			}
		}

		return $data['value'];
	}

	function combine() {
		$args=func_get_args();
		$return='';
		foreach($args as $arg) {
			$return.=$arg.',';
		}
		$return=rtrim($return,',');
		 return $return;
	}

	function clear_model() {
		$this->model_array=false;
	}

}
<div class="row">
	<?php 
		$model_data=array();

		/* for set form model while editing recoed */
		if(!empty($client)) {
			$model_data=$client;
		}

		echo $this->form->form_model($model_data,'client/save_client', array('name' => 'client_addform')); 
	?>
	    <div class="col-md-12">
	    	<?php 
	    		if(isset($client['client_id'])) {
	    			echo $this->form->form_hidden('client_id',$client['client_id']);	
	    		}
	    	?>
	    	 <div class="box box-primary">
		    	<div class="box-header with-border">
	                <div class="box-title">Client</div>
	        	</div>
        		<div class="box-body"><br/>
        			<div id="error_div"></div>
        			<div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Company name <span class="text-danger">*</span></label>
		                  <?php 
		                  		$other_option=array(
		                  			'class'=>'form-control',
		                  			'placeholder'=>'Company Name',
		                  			'required'=>'required'
		                  		);
		                  		echo $this->form->form_input('client_name',$other_option); 
		                  ?>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Email <span class="text-danger">*</span></label>
		                  <?php 
		                  		/* You can create any html5 form control by overwriting type attribute in option argument as below example */
		                  		$other_option=array(
		                  			'type'=>'email',
		                  			'class'=>'form-control',
		                  			'placeholder'=>'Official Email',
		                  			'required'=>'required'
		                  		);
		                  		echo $this->form->form_input('email',$other_option); 
		                  ?>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Zip</label>
		                  <?php 
		                  		$other_option=array(
		                  			'type'=>'number',
		                  			'class'=>'form-control',
		                  			'placeholder'=>'zip',
		                  			
		                  		);
		                  		echo $this->form->form_input('client_zip',$other_option); 
		                  ?>
		                </div>
	                </div>

	                 <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Country</label>
		                  <?php 
		                  		$other_option=array(
		                  			'class'=>'form-control select2',
		                  			'placeholder'=>'Country',			                  			
		                  		);
		                  		echo $this->form->form_country_dropdown('client_country','',$other_option); 
		                  ?>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Company Type</label>
		                  <br/>
		                  <label>
		                  	<?php
		                  		echo $this->form->form_radio('company_type','Private');
		                  		echo " Private &nbsp;&nbsp;";
		                  	?>
		                  	</label>
		                  	<label>
		                  	<?php	
		                  		echo $this->form->form_radio('company_type','Public');
		                  		echo " Public ";
		                  	?>
		                  </label>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Client type</label>
		                  <?php 
		                  		$other_option=array(
		                  			'class'=>'form-control',
		                  		);
		                  		echo $this->form->form_dropdown_fromdatabase('client_type_id', $client_type, 'client_type_id','client_type_title','',$other_option); 
		                  ?>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Discount Eligible </label><br/>
		                  <label>
		                  	<?php
		                  		echo $this->form->form_checkbox('client_discount','Yes');
		                  		echo "Yes";
		                  	?>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Address</label>
		                  <?php 
		                  		$other_option=array(
		                  			'class'=>'form-control',
		                  			'placeholder'=>'Full Address',
		                  		);
		                  		echo $this->form->form_textarea('client_address', $other_option); 
		                  ?>
		                </div>
	                </div>

	                <div class="col-md-4 col-sm-6">
	        			<div class="form-group">
		                  <label>Note</label>
		                  <?php 
		                  		$other_option=array(
		                  			'class'=>'form-control',
		                  			'placeholder'=>'Client Note',
		                  		);
		                  		echo $this->form->form_textarea('client_note', $other_option); 
		                  ?>
		                </div>
	                </div>
        		</div>

        		<div class="box-footer with-border">
        			<div class="box-tools pull-right">
	                	<input type="reset" class="btn btn-danger" value="Reset">
	                	<input type="submit" class="btn btn-primary" value="Submit">
	        		</div>
	        	</div>
		        </div>
		    </div>
	    </div>
	<?php echo $this->form->form_close(); ?>
</div>
<script type="text/javascript" src="<?php echo base_url()."public/js/validate.min.js"; ?>"></script>


<script type="text/javascript">
var validator = new FormValidator('client_addform', [{
    name: 'client_name',
    display: 'company name',
    rules: 'required'
},
{
    name: 'client_zip',
    display: 'Zip code',
    rules: 'numeric|min_length[6]|max_length[8]'
}], 
function(errors, event) {
    show_validation_error(errors);
});

/* Show validation error message */
function show_validation_error(errors, error_div) {
	var err_html='<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" class="close" type="button" data-dismiss="alert">x</button><ul id="error_list">%s</ul></div>';
    if (errors.length > 0) {
        var errorString = '';
        errorLength = errors.length;
        for (var i = 0; i < errorLength; i++) {
            errorString += '<li>' + errors[i].message + '</li>';
        }
        errorString=err_html.replace('%s',errorString);
        if(!error_div) {
        	error_div='#error_div';
        }
        $(error_div).html(errorString);
        /* Scroll to error div */
        //if(!$(error_div).is(":visible")){
        	$('html, body').animate({
		        scrollTop: $(error_div).offset().top
		    }, 1000);
        //}
        
    }
}
</script>
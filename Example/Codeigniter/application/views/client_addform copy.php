<div class="row">
	<?php 
		$model_data=array();

		/* for set form model while editing recoed */
		if(!empty($client)) {
			$model_data=$client;
			$model_data['people_name']=array();
			$model_data['people_designation']=array();
			$model_data['people_email']=array();
			$model_data['people_mobile']=array();

			/* Modify data so one logic can be used for old_data and edit client */
			foreach ($people as $key => $client_row) {
				if(!empty($client_row['client_people'])) {
					$id=$client_row['client_people'];
					$model_data['people_name'][$id] = $client_row['people_name'];
					$model_data['people_designation'][$id] = $client_row['people_designation'];
					$model_data['people_email'][$id] = $client_row['people_email'];
					$model_data['people_mobile'][$id] = $client_row['people_mobile'];
				}
			}

		}
		/* Over write old data */
		$old_data=$this->session->flashdata('old_data');
		if($old_data) {
			$model_data=$old_data;
		}

		echo $this->form->form_model($model_data,'client/save_client', array('name' => 'client_addform')); 
	?>
	    <div class="col-md-12">
	    	<?php 
	    		if(isset($client['client_id'])) {
	    			echo $this->form->form_hidden('client_id',$client['client_id']);	
	    		}
	    		echo $this->form->form_hidden('deleted');
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
	                <div class="clearfix"></div>
	                <h4>Contact Person</h4>
	                <a href="javascript:none();" id="add_more_people" class="btn btn-sm btn-primary pull-right" title="Add more"><i class="fa fa-plus"></i> Add more</a>
	                <table class="table table-bordered" id="people">
	                	<thead>
	                		<tr>
	                			<th>Full Name</th>
	                			<th>Designation</th>
	                			<th>Email</th>
	                			<th>Tel</th>
	                			<th>#</th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<?php
	                		/* Loop through each person */
	                		if(!empty($client) && isset($model_data['people_name'])  && is_array($model_data['people_name'])) {
	                				foreach ($model_data['people_name'] as $key => $client_row) { 
               				?>
	                					<tr>
											<td>
												<?php
								              		$other_option=array(
								              			'class'=>'form-control',
								              			'placeholder'=>'Contact Person',
								              			'required'=>'required'
								              		);
								              		echo $this->form->form_input('people_name['.$key.']',$other_option); 
								              ?>
											</td>
											<td>
												<?php 
								              		$other_option=array(
								              			'class'=>'form-control',
								              			'placeholder'=>'Designation',
								              		);
								              		echo $this->form->form_input('people_designation['.$key.']',$other_option); 
								              ?>
											</td>
											<td>
												<?php 
								              		$other_option=array(
								              			'class'=>'form-control',
								              			'placeholder'=>'Email Address',
								              			'required'=>'required'
								              		);
								              		echo $this->form->form_input('people_email['.$key.']',$other_option); 
								                  ?>
											</td>
											<td>
												<?php 
								              		$other_option=array(
								              			'class'=>'form-control',
								              			'placeholder'=>'Telephone Number',
								              			
								              		);
								              		echo $this->form->form_input('people_mobile['.$key.']',$other_option); 
								              	?>
											</td>
											<td><a href="javascript:void()" data-id="<?php echo $key; ?>" class="remove_person"><i class="fa fa-trash-o"></i></a></td>
										</tr>
	                		<?php } } ?>
	                	</tbody>
	                </table>
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
	<!-- Add more tr layout -->
	<div id="person_table_row" style="display:none">
		<table>
			<tbody>
				<tr>
					<td>
						<?php
		              		$other_option=array(
		              			'class'=>'form-control',
		              			'placeholder'=>'Contact Person',
		              			'required'=>'required'
		              		);
		              		echo $this->form->form_input('people_name[]',$other_option); 
		              ?>
					</td>
					<td>
						<?php 
		              		$other_option=array(
		              			'class'=>'form-control',
		              			'placeholder'=>'Designation',
		              		);
		              		echo $this->form->form_input('people_designation[]',$other_option); 
		              ?>
					</td>
					<td>
						<?php 
		              		$other_option=array(
		              			'class'=>'form-control',
		              			'placeholder'=>'Email Address',
		              			'required'=>'required'
		              		);
		              		echo $this->form->form_input('people_email[]',$other_option); 
		                  ?>
					</td>
					<td>
						<?php 
		              		$other_option=array(
		              			'class'=>'form-control',
		              			'placeholder'=>'Telephone Number',
		              			
		              		);
		              		echo $this->form->form_input('people_mobile[]',$other_option); 
		              	?>
					</td>
					<td><a href="javascript:void()" class="remove_person"><i class="fa fa-trash-o"></i></a></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url()."public/js/validate.min.js"; ?>"></script>


<script type="text/javascript">
$(document).ready(function() {	
	var tr_count=$('#people >tbody >tr').length;
	if(tr_count < 1) {
		add_people();
	}

	$('#add_more_people').click(function() {
		add_people();
	});

	$(document).on('click','.remove_person', function() {
		var id=$(this).attr('data-id');
		if(id) {
			var hidden_field='<input type="hidden" name="deleted_person[]" value="'+id+'">';
			$('form[name="client_addform"]').append(hidden_field);
		}
		$(this).parent().parent().remove();
	});
});

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

function add_people() {
	var tr=$('#person_table_row table>tbody').html();
	$('#people tbody').append(tr);
}

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
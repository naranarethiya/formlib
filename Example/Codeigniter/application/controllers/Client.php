<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller {

	function __construct($value='') {
		parent::__construct();
		$this->load->model('client_model');
		$this->load->library('form');
		$this->load->helper('global_helper');
	}

	public function index() {
		$data['title']="Clients";
		$data['client']=$this->client_model->get_client(array("GROUP_BY"=>'client.client_id'));
		
		/* Get view data to render in master view */
		$data['main_content']=$this->load->view('client_list.php',$data,true);

		/* Render master view with */
		$this->load->view('master',$data);
	}

	/*
		Accept $id as argument if edit the client
	*/
	public function client_addform($id=false) {
		$data['title']="Client Add";
		$data['client']=false;
		if($id) {
			/* Get client */
			$client=$this->client_model->get_client(array('client.client_id'=>$id));
			
			/* Check if valid client id */
			if(!isset($client[0])) {
				set_message("invalid client selected");
				redirect(base_url()."client");
			}

			$data['client']=$client[0];
			$data['people']=$client;
		}

		/* Get client type for select box value */
		$data['client_type']=$this->client_model->get_client_type();

		/* Get view data to render in master view */
		$data['main_content']=$this->load->view('client_addform.php',$data,true);

		/* Render master view with */
		$this->load->view('master',$data);
	}

	public function save_client() {
		$this->load->library('form_validation');
		$client_id=$this->input->post('client_id');
		/* Form Validation */
		$this->form_validation->set_rules('client_name', 'Company Name', 'required');
		
		$this->form_validation->set_rules('client_zip', 'zip code', 'numeric|min_length[6]|max_length[8]');
		$this->form_validation->set_rules('people_name[]', 'Contact person', 'required');
		$this->form_validation->set_rules('people_email[]', 'email', 'valid_email');

		if ($this->form_validation->run() == FALSE) {
			/* Global Helper function to set messages */
			set_message(validation_errors());

			/* Set Old data to set form fields */
			$this->session->set_flashdata('old_data',$this->input->post());
			
			/* Global helper function to redirect user on back page */
			redirect_back();
			return 0;
		}
		
		/* Create insert array */
		$insert_client['client_name']=$this->input->post('client_name');
		$insert_client['client_zip']=$this->input->post('client_zip');
		$insert_client['client_country']=$this->input->post('client_country');
		$insert_client['client_address']=$this->input->post('client_address');
		$insert_client['client_note']=$this->input->post('client_note');
		$insert_client['client_type_id']=$this->input->post('client_type_id');
		$insert_client['company_type']=$this->input->post('company_type');
		$insert_client['email']=$this->input->post('email');
		$insert_client['client_discount']=$this->input->post('client_discount');
		
		//dsm($insert_client);
		//dsm($_POST);die;
		/* People variable */
		$people_name=$this->input->post('people_name');
		$people_designation=$this->input->post('people_designation');
		$people_email=$this->input->post('people_email');
		$people_mobile=$this->input->post('people_mobile');

		/* Start transction  */
		$this->db->trans_begin();

		/* Update client */
		if(!empty($client_id)) {
			/* Update client table */
			$this->client_model->update_client($insert_client, $client_id);

			/* Delete people which are deleted */
			$deleted=$this->input->post('deleted_person');
			if(!empty($deleted)) {
				/* Set Condition for delete */
				$delete_filter['WHERE_IN']=array('client_people'=>$deleted);
				$delete_filter['client_id']=$client_id;
				$this->client_model->delete_client_people($delete_filter);
			}

			/* Get current client from db to compare */
			$client_result=$this->client_model->get_client(array("client.client_id"=>$client_id));

			/* Extract primary key to comapre */
			$people_ids=array_column($client_result,'client_people');

			/* Get existing people's primary key for compare */
			foreach ($people_name as $key => $value) {
				
				/* create Array  */
				$people_insert=array(
					'client_id'=>$client_id,
					'people_name'=>$people_name[$key],
					'people_designation'=>$people_designation[$key],
					'people_email'=>$people_email[$key],
					'people_mobile'=>$people_mobile[$key],
				);

				/* Check if new row then add */
				if(!in_array($key, $people_ids)) {	
					/* Insert New client people */
					$this->client_model->add_client_people($people_insert);
				}
				else {
					/* Update client people */
					$this->client_model->update_client_people($people_insert, $key);
				}
			}
			
		}
		/* Insert New client */
		else {
			$client_id=$this->client_model->add_client($insert_client);

			/* Add people */
			foreach ($people_name as $key => $value) {
				$people_insert=array(
					'client_id'=>$client_id,
					'people_name'=>$people_name[$key],
					'people_designation'=>$people_designation[$key],
					'people_email'=>$people_email[$key],
					'people_mobile'=>$people_mobile[$key],
				);
				$this->client_model->add_client_people($people_insert);
			}
		}

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    $this->session->set_flashdata('old_data',$this->input->post());
		    set_message("Something went wrong, Please try again");
		   	redirect_back();
		}
		else {
		    $this->db->trans_commit();
		    set_message("Client data saved","success");
		    redirect(base_url()."client");
		}

	}

	public function client_delete() {
		$id=$this->input->post('id');
		if($id) {
			$this->client_model->delete_client(array('WHERE_IN'=>array('client.client_id'=>$id)));
			$return=array("status"=>1, "message"=>"client deleted successfully");
			echo json_encode($return);
		}
		else {
			$return=array("status"=>0, "message"=>"Please select valid clients");
			echo json_encode($return);	
		}
		return 0;
	}
}
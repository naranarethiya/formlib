<?php 
class Client_model extends CI_Model {

	public function add_client($data) {
		$this->db->insert('client',$data);
		return $this->db->insert_id();
	}

	public function add_client_people($data) {
		$this->db->insert('client_people',$data);
		return $this->db->insert_id();
	}	

	public function update_client($data,$id) {
		$this->db->where('client_id',$id);
		return $this->db->update('client',$data);
	}

	public function update_client_people($data,$id) {
		$this->db->where('client_people',$id);
		return $this->db->update('client_people',$data);
	}

	public function get_client($filter=false) {
		if($filter) {
			apply_filter($filter);
		}
		$this->db->select('client.*, client_people.*, client.client_id as client_id');
		$this->db->join('client_people','client_people.client_id=client.client_id','left');
		$this->db->order_by('client.client_id','desc');
		$client_rs=$this->db->get('client');
		return $client_rs->result_array();
	}

	public function get_client_type($filter=false) {
		if($filter) {
			apply_filter($filter);
		}
		$client_rs=$this->db->get('client_type');
		return $client_rs->result_array();
	}

	public function get_client_count($filter=false) {
		if($filter) {
			apply_filter($filter);
		}
		$this->db->select("count(*) as count");
		$client_rs=$this->db->get('client');
		return $client_rs->result_array();
	}

	function delete_client($filter=false) {
		if($filter) {
			apply_filter($filter);
		}
		$this->db->delete('client');
	}

	public function delete_vessel($filter) {
		apply_filter($filter);
		$this->db->delete('client_vessel');
	}

	function delete_client_people($filter=false) {
		if($filter) {
			apply_filter($filter);
		}
		$this->db->delete('client_people');
	}

	public function check_exist($name) {
		$id=$this->input->post('client_id');
		if($id) {
			$filter['WHERE']="client.client_id !='".$id."'";
		}
		$filter['client_name']=$name;
		$result=$this->get_client($filter);
		if(isset($result[0])) {
			return false;
		}
		else {
			return true;
		}
	}
}
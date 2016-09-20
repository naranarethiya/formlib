<?php 
	/* 
		Message display 
		accept 2 parameter.
		1 - message text.
		2 - (optional) type of message success, error, warning. Bydefault error is selected.
	*/
	function set_message($message,$type="error") {
		$CI =& get_instance();
		if($type=="error") {
			$add=$CI->session->userdata('error');
			$set_message=$add."<li>".$message."</li>";
			$CI->session->set_userdata('error',$set_message);
		}
		else if($type=="success") {
			$add=$CI->session->userdata('success');
			$set_message=$add."<li>".$message."</li>";
			$CI->session->set_userdata('success',$set_message);
		}
		else if($type=="warning") {
			$add=$CI->session->userdata('warning');
			$set_message=$add."<li>".$message."</li>";
			$CI->session->set_userdata('warning',$set_message);
		}  
	}	

	/* 
		print array in format 
		accept 1 parameter as array.
	*/

	function dsm($var) {
		if(is_array($var) || is_object($var)) {
			echo "<pre>".print_r($var,true)."</pre>";
		}
		else {
			echo "<pre>".$var."</pre>";
		}
		$debug=debug_backtrace();
		echo "<pre>".$debug[0]['file'].", line :".$debug[0]['line']."</pre><br/>";  
	}

	/* print last execulated query */
	function print_last_query() {
		$CI =& get_instance();
		dsm($CI->db->last_query());
	}	

	/* 
		redirect back - redirect to request page.
	*/
	function redirect_back() {
		if(isset($_SERVER['HTTP_REFERER'])) {
			$url=$_SERVER['HTTP_REFERER'];  
		}
		else {
			$url=base_url();
		}
		redirect($url);
	}	

	/* 
		Filter for db queries 
		accept 1 parameter
		1 - array of filter.		
	*/
	function apply_filter($filter) {
		$CI=& get_instance();
		if(is_array($filter)) {
			foreach($filter as $key => $val) {
				/* limit */
				if($key==='LIMIT') {
					if(is_array($val)) {
						$CI->db->limit($val[0],$val[1]);
					}
					else {
						$CI->db->limit($val);
					}
				}

				/* Where clause */
				else if($key==='WHERE') {
					$CI->db->where($val,null,FALSE);
				}
				else if($key==='WHERE_IN') {
					foreach($val as $column => $value) {
						$CI->db->where_in($column,$value);
					}
				}
				else if($key==='HAVING') {
					if(is_array($val)) {
						foreach($val as $col=>$value) {
							$CI->db->having($col,$value);
						}
					}
					else {
						$CI->db->having($val,null,FALSE);
					}
				}

				/* order by */
				elseif($key=='ORDER_BY') {
					foreach($val as $col => $order) {
						$CI->db->order_by($col,$order);
					}
				}

				/* group by */
				elseif($key=='GROUP_BY') {
					$CI->db->group_by($val);
				}

				/* group by */
				elseif($key=="SELECT") {
					$CI->db->select($val);
				}

				/* LIKE */
				elseif($key=='LIKE') {
					foreach($val as $col => $value) {
						$CI->db->like($col,$value);
					}
				}

				/* simple key=>value where condtions */
				else {
					$CI->db->where($key,$val);  
				}
			}
		}
	}	

	/* Check Unique value form database  
		$value - Column value to check
		$column - Table column name
		$table - Table name
		$primary - Primary key column of table to ignore check unique value for own row 
		$edit_id  - primary column value to ignore that row for unique check
	*/
	function check_unique($value, $column, $table, $primary_column=false ,$edit_id=false) {
		$CI=&get_instance();
		if(!empty($edit_id)) {
			$CI->db->where($primary_column." !=", $edit_id);
		}
		$CI->db->where($column,$value);
		$rs=$CI->db->get($table);
		$result=$rs->result_array();
		if(isset($result[0])) {
			return false;
		}
		else {
			return true;
		}
	
	}
	
?>
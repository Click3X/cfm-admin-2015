<?php

class C3X_Model extends CI_Model
{
	var $table;
	var $pk;

	function C3X_Model()
	{
		$this->load->database();
	}
	
	function pk(){
		return $this->pk;
	}

	function get( $options = null ){
		if( isset($options) ){
			$query = $this->db->get_where( $this->table, $options );
		} else {
			$query = $this->db->get( $this->table );
		}
			
		return $query->result();
	}

	function add( $options = array() )
	{
		$this->db->insert($this->table, $options);
		
		return $this->db->insert_id();
	}
	
	function update($pid, $options = array())
	{
		foreach ( $options as $key => $value ) {
			$this->db->set( $key, $value );
		}

		$this->db->where($this->pk, $pid);
		
		$this->db->update($this->table);
		
		return $this->db->affected_rows();
	}

	function update_batch( $options ){
		$this->db->update_batch( $this->table, $options, $this->pk );
		
		return $this->db->affected_rows();
	}

	function delete( $key, $val )
	{
		$this->db->delete( $this->table, array($key=>$val) ); 	
		
		return $this->db->affected_rows();
	}

	function update_record($projectid, $updatedata) {
		$this->db->where("id",$projectid);
		$this->db->update($this->table,$updatedata); 

		return $this->db->affected_rows();
	}
}

?>
<?php

    class Modules_Model extends C3X_Model
    {
    	public function Modules_Model()
    	{
    		$this->table = "modules";
    		$this->pk = "id";
    	}

    	function get( $options = null ){
    		$this->db->join( "project_module_lu", "project_module_lu.module_id = " . $this->table . ".id");
    		$this->db->join( "module_types", "module_types.id = " . $this->table . ".module_type_id");

			if( isset($options) ){
				$query = $this->db->get_where( $this->table, $options );
			} else {
				$query = $this->db->get( $this->table );
			}
				
			return $query->result();
		}

    }

?>
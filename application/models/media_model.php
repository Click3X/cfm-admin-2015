<?php

    class Media_Model extends C3X_Model
    {
    	public function Media_Model()
    	{
    		$this->table = "media";
    		$this->pk = "id";
    	}

    	function get( $options = null ){
    		$this->db->join( "module_media_lu", "module_media_lu.media_id = " . $this->table.".id" );
    		$this->db->join( "media_types", "media_types.id = " . $this->table.".media_type_id" );

			if( isset($options) ){
				$query = $this->db->get_where( $this->table, $options );
			} else {
				$query = $this->db->get( $this->table );
			}
				
			return $query->result();
		}
    }

?>
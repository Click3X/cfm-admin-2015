<?php

	class Projects_Model extends C3X_Model
	{
		public function Projects_Model()
		{
			$this->table 	= "projects";
			$this->pk 		= "id";
		}

		public function get_all_by_category( $category_name ){
			$query = $this->db->query(
	        	"SELECT * FROM 
	        	( SELECT category_name,category_id,project_id FROM project_category_lu CROSS JOIN categories ON categories.id = project_category_lu.category_id AND category_name = '".$category_name."' ) AS filtered_lu 
	        	LEFT JOIN projects ON projects.id = project_id ORDER BY `order`"
	        );
	        return $query->result();
		}
	}

?>
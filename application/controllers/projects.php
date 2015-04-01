<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Projects extends CI_Controller {

		/**
		 * Index Page for this controller.
		 *
		 * Maps to the following URL
		 * 		http://example.com/index.php/welcome
		 *	- or -
		 * 		http://example.com/index.php/welcome/index
		 *	- or -
		 * Since this controller is set as the default controller in
		 * config/routes.php, it's displayed at http://example.com/
		 *
		 * So any other public methods not prefixed with an underscore will
		 * map to /index.php/welcome/<method_name>
		 * @see http://codeigniter.com/user_guide/general/urls.html
		 */
		public function __construct()
		{
			parent::__construct();

			$this->load->model("projects_model");
			$this->load->model("project_category_lu_model");
			$this->load->model("project_link_lu_model");
			$this->load->model("project_module_lu_model");
			$this->load->model("module_media_lu_model");
			$this->load->model("modules_model");
			$this->load->model("media_model");

			$this->load->model("category_model");

			$this->categories = $this->category_model->get();
		}

		public function index()
		{
			$projects = $this->category("project");
		}

		public function category( $category_name ){
			$projects = $this->projects_model->get_all_by_category( $category_name );

			foreach( $projects as $key=>$project ){
				$project->modules = $this->modules_model->get( array( "project_id"=>$project->project_id ) );

				// foreach( $project->modules as $key=>$module ){
				// 	$module->media = $this->media_model->get( array( "module_id"=>$module->module_id ) );
				// }
			}

			$category = $this->category_model->get( array( "category_name"=>$category_name ) );
			$category = $category[0];

			$this->load->view( 'projects_view', array( "projects"=>$projects, "categories"=>$this->categories, "category"=>$category ) );
		}

		// public function modules( $project_id ){
		// 	$project = $this->projects_model->get( array( "id"=>$project_id ) );
		// 	$project = $project[0];

		// 	$modules = $this->modules_model->get( array( "project_id"=>$project_id ) );

		// 	foreach ($modules as $key => $module) {
		// 		$module->media = $this->media_model->get( array( "module_id"=>$module->module_id ) );
		// 	}

		// 	$data = array( "project"=>$project, "modules"=>$modules );

		// 	$this->load->view( "project_modules_view", $data );
		// }

		public function update(){
			$data = $this->input->post( "data" );

			$batch = array();

			foreach( $data as $project ){
				$project['data']['id'] = $project['id'];

				array_push( $batch, $project['data'] );
			}

			$reponse = $this->projects_model->update_batch( $batch );

			echo json_encode( $reponse );
		}

		public function add(){
			$data 			= $this->input->post();

			$category_id 	= $data["category_id"];
			unset( $data["category_id"] );

			$duplicate_slug_project_id = $this->projects_model->get( array( "slug"=>$data['slug'] ) );

			if( !empty( $duplicate_slug_project_id ) && count( $duplicate_slug_project_id ) > 0 ){
				$data['slug'] .= ("-" . (count( $duplicate_slug_project_id )+1) );
			}

			//add project record
			$project_id 	= $this->projects_model->add( $data );
			$project 		= $this->projects_model->get( array("id"=>$project_id) );
			$project 		= $project[0];

			//add category lu record
			$cat_lu_id 		= $this->project_category_lu_model->add( array( "project_id"=>$project_id, "category_id"=>$category_id ) );

			//get the project html template
			$response 		= $this->load->view("project_template_view", array("project"=>$project), true);
			
			echo $response;
		}

		public function delete(){
			$project_id = $this->input->post( "id" );

			$response = array();

			//remove project record
			$response[ "projresp" ] 			= $this->projects_model->delete( "id", $project_id );

			//remove category lookup records with that project id
			$response[ "catluresp" ] 			= $this->project_category_lu_model->delete( "project_id", $project_id );

			//remove link lookup records with that project id
			$response[ "linkluresp" ] 			= $this->project_link_lu_model->delete( "project_id", $project_id );

			//remove modules associated with that project id
			//first get all of the lookup records;
			$response[ "modulelurecords" ] 		= $this->project_module_lu_model->get( array( "project_id"=>$project_id ) );

			//loop through the lookup records
			foreach( $response[ "modulelurecords" ] as $key => $record ) {
				$this->deletemodule( $record->module_id );
			}

			echo json_encode( $response );
		}

		public function deletemodule( $moduleid ){
			$response = array();

			//remove module
			$moduleresponse = $this->modules_model->delete( "id", $moduleid );
			$response["moduleresponse"] = $moduleresponse;

			//remove project lookup records for this module
			$moduleluresponse = $this->project_module_lu_model->delete( "module_id", $moduleid );
			$response["moduleluresponse"] = $moduleluresponse;

			//remove media lookup records for this module
			$medialuresponse = $this->module_media_lu_model->delete( "module_id", $moduleid );
			$response["medialuresponse"] = $medialuresponse;

			return $response;
		}
	}
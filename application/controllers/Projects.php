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


		public function modules( $project_id ){
			$project = $this->projects_model->get( array( "id"=>$project_id ) );
			$project = $project[0];

			$modules = $this->modules_model->get( array( "project_id"=>$project_id ) );

			foreach ($modules as $key => $module) {
				$module->media = $this->media_model->get( array( "module_id"=>$module->module_id ) );
			}

			$data = array( "project"=>$project, "modules"=>$modules );

			$this->load->view( "project_modules_view", $data );
		}

		// media library
		// public function media(){

		// 	$media_jpg = $this->media_model->get( array( "media_type_id"=> 1 ) );
		// 	$media_png = $this->media_model->get( array( "media_type_id"=> 4 ) );
		// 	$media_gif = $this->media_model->get( array( "media_type_id"=> 3 ) );
		// 	$data = array( "media_type_id"=>$media_jpg );

		// 	$this->load->view( "media_view", $data );
		// }

		// update date and time
		public function updateTime() {
			$datetime = $this->input->post('timedata');
			$project_id = $this->input->post('projectid');
			$updatedata = array("date_created" => $datetime);
			$reponse = $this->projects_model->update_record( $project_id, $updatedata );
		}


		public function saveModule() {

			$this->load->library('form_validation');
			// add banner image
			if (isset($_POST['banner-image'])) {

					$header_moduleid = $this->modules_model->add( array( "module_type_id"=>1, "subhead"=>$_POST['banner-image-subhead'] ) );
					// $response["header_moduleid"] = $header_moduleid;
					// //add media
					$header_meidaid = $this->media_model->add( array( "media_type_id"=>$_POST['media-type'], "filename"=>$_POST['banner-image'] ) );

					// $response["header_meidaid"] = $header_meidaid;
					//add module media lu
					$header_medialuid = $this->module_media_lu_model->add( array( "module_id"=>$header_moduleid, "media_id"=>$header_meidaid ) );
					// $response["header_medialuid"] = $header_medialuid;
					//add project module lu
					$header_moduleluid = $this->project_module_lu_model->add( array( "project_id"=>$_POST['project_id'], "module_id"=>$header_moduleid ) );
					// $response["header_moduleluid"] = $header_moduleluid;

					redirect('projects/modules/'. $_POST['project_id'], 'refresh');

			}
			// add banner video
			else if (isset($_POST['banner-video'])) {

					$video_moduleid = $this->modules_model->add( array( "module_type_id"=>2, "title"=>$_POST['banner-video-title'], "subhead"=>$_POST['banner-video-subhead'] ) );
					// $response["video_moduleid"] = $video_moduleid;
					//add media
					$video_mediaid = $this->media_model->add( array( "media_type_id"=>2, "filename"=>$_POST['banner-video'] ) );
					// $response["video_mediaid"] = $video_mediaid;
					//add module media lu
					$video_medialuid = $this->module_media_lu_model->add( array( "module_id"=>$video_moduleid, "media_id"=>$video_mediaid ) );
					// $response["video_medialuid"] = $video_medialuid;
					//add project module lu
					$video_moduleluid = $this->project_module_lu_model->add( array( "project_id"=>$_POST['project_id'], "module_id"=>$video_moduleid ) );
					// $response["video_moduleluid"] = $video_moduleluid;

					redirect('projects/modules/'. $_POST['project_id'], 'refresh');

			}

			// add gallery image(s)
			else if (isset($_POST['gallery'])) {
				$filenames = $_POST['gallery'];
				$media_types = $_POST['media-type'];
				$titles = $_POST['gallery-title'];
				$links = $_POST['gallery-link'];
				$media_info = array();
					// add gallery module
					$gallery_moduleid = $this->modules_model->add( array( "module_type_id"=>5, "subhead"=>$_POST['gallery-subhead'] ) );

					// //add project module lu
					$gallery_moduleluid = $this->project_module_lu_model->add( array( "project_id"=>$_POST['project_id'], "module_id"=>$gallery_moduleid ) );
					
					// push mediatype, title, and subhead into a new array $media_info
					$max = sizeof($filenames);

					for ($i = 0; $i < $max; $i++) {
						$media_info[$i] = array('media-type' => $media_types[$i], 'media-title' => $titles[$i], 'media-link' => $links[$i]);
					}
					
					// combine gallary and media-type arrays
					$gallery_files = array_combine($filenames, $media_info);
					// echo '<pre>';
					// print_r($gallery_files);
					// echo '</pre>';

					
					foreach( $gallery_files as $filename => $media_info ) {
						// replace empty string with NULL
						$media_info = array_map(function($value) {
						   return $value === "" ? NULL : $value;
						}, $media_info); 
						// add media(s)
						$gallery_mediaid = $this->media_model->add( array( "media_type_id"=>$media_info['media-type'], "title" => $media_info['media-title'], "href" => $media_info['media-link'], "filename"=>$filename ) );	
						// echo 'gallery_mediaid: ' . $gallery_mediaid . '<br>';

						// add module media lu(s)	
						$gallery_medialuid = $this->module_media_lu_model->add( array( "module_id"=>$gallery_moduleid, "media_id"=>$gallery_mediaid ) );	
						// echo 'gallery_media_lu_id: ' . $gallery_medialuid . '<br>';
					}
										
					redirect('projects/modules/'. $_POST['project_id'], 'refresh');

			}
			
		}

		public function deletemodule( $module_id = null ) {			
			if ( !empty( $_POST['module_id']) ){
				$module_id = $_POST['module_id'];
			}

			//remove module
			$moduleresponse = $this->modules_model->delete( "id", $module_id );
			// $response["moduleresponse"] = $moduleresponse;

			//remove project lookup records for this module
			$moduleluresponse = $this->project_module_lu_model->delete( "module_id", $module_id );
			// $response["moduleluresponse"] = $moduleluresponse;

			//remove media lookup records for this module
			$medialuresponse = $this->module_media_lu_model->delete( "module_id", $module_id );

				// echo $response;
	

		}

		public function deleteGalleryMedia() {
			$moduleid = $_POST['module_id'];
			$mediaid = $_POST['media_id'];
			$medialuresponse = $this->module_media_lu_model->delete( "media_id", $mediaid );
		}

		public function update(){
			$data = $this->input->post( "data" );

			$batch = array();

			foreach( $data as $project ){
				$project['data']['id'] = $project['id'];

				array_push( $batch, $project['data'] );
			}

			$reponse = $this->projects_model->update_batch( $batch );

			// echo json_encode( $reponse );
		}

		public function updateModule() {
			$data = $this->input->post();
			$filename = $data['filename'];
			$mid = $data['module_id'];
			unset($data['submit']);
			unset($data['filename']);
			// print_r($data);
			
			$cur_column = $this->modules_model->get( array( "module_id"=>$data['module_id'] ) );
			$cur_column_media = $this->media_model->get( array( "module_id"=>$data['module_id'] ) );
			$media_id = $cur_column_media[0]->media_id;
			// print_r($cur_column_media[0]);
			// echo $cur_column[0]->module_type_id;
			
			foreach ( $data as $key => $value ) {
				if ($value !== $cur_column[0]->$key ) {
					$module_response = $this->modules_model->update_record($mid, array($key => $value) );
				}
			}

			if ($filename !== $cur_column_media[0]->filename) {
				$media_response = $this->media_model->update_record($media_id, array("filename" => $filename) );
			}
			

		}

		public function togglePublish() {

			$datapublish = $_POST['datapublish'];
			$project_id = $_POST['project_id'];
			$updatedata = array("published" => $datapublish);
			$reponse = $this->projects_model->update_record( $project_id, $updatedata );
			print_r($reponse);
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
			
			// echo $response;
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

			// echo json_encode( $response );
		}


	}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utils extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->model("projects_model");
		$this->load->model("modules_model");
		$this->load->model("project_module_lu_model");
		$this->load->model("project_category_lu_model");
		$this->load->model("project_link_lu_model");
		$this->load->model("module_media_lu_model");
		$this->load->model("media_model");
	}

	public function index()
	{
		echo "You shouldn't be here.";
	}

	public function deleteunusedmedia(){
		$media = $this->media_model->get();

		// var_dump($media);

		foreach ($media as $key => $value) {
			$lu = $this->module_media_lu_model->get( array( "media_id"=>$value->id ) );
			echo "modules using media : ". $value->id . " : ". count($lu) . "; ";
			if( count($lu) == 0 ) $this->media_model->delete( "id", $value->id);
		}
	}

	public function deleteproject($pid,$modulesonly){
		echo "deletiing project: ". $pid;

		$response = array();

		//remove project record
		if($modulesonly == 0){
			$response["projresp"] = $this->projects_model->delete( "id",$pid );

			//remove category lookup records with that project id
			$response["catluresp"] = $this->project_category_lu_model->delete( "project_id",$pid );

			//remove link lookup records with that project id
			$response["linkluresp"] = $this->project_link_lu_model->delete( "project_id",$pid );
		}

		//remove modules associated with that project id
		//first get all of the lookup records;
		$response["modulelurecords"] = $this->project_module_lu_model->get( array("project_id"=>$pid) );

		//loop through the lookup records
		foreach($response["modulelurecords"] as $key => $record) {
			$this->deletemodule( $record->module_id );
		}

		var_dump($response);
	}

	public function addproject( $slug, $thumbnail_name, $header_name, $video_name, $categoryid=1 ){
		echo "add project with header and video: ". $slug . ":" .$thumbnail_name. ":" .$header_name. ":" .$video_name. ":" .$categoryid;

		$response = array();

		//create project record
		$projectid = $this->projects_model->add( array( "slug"=>$slug, "thumbnail_image"=>$thumbnail_name ) );
		$response["projectid"] = $projectid;

		//add category lu record
		$categoryluid = $this->project_category_lu_model->add( array("project_id"=>$projectid, "category_id"=>$categoryid ) );
		$response["categoryluid"] = $categoryluid;

		//ADD HEADER ASSET
		//add module 
		if($header_name != "NULL"){
			$header_moduleid = $this->modules_model->add( array( "module_type_id"=>1 ) );
			$response["header_moduleid"] = $header_moduleid;
			//add media
			$header_meidaid = $this->media_model->add( array( "media_type_id"=>1, "filename"=>$header_name ) );
			$response["header_meidaid"] = $header_meidaid;
			//add module media lu
			$header_medialuid = $this->module_media_lu_model->add( array( "module_id"=>$header_moduleid, "media_id"=>$header_meidaid ) );
			$response["header_medialuid"] = $header_medialuid;
			//add project module lu
			$header_moduleluid = $this->project_module_lu_model->add( array( "project_id"=>$projectid, "module_id"=>$header_moduleid ) );
			$response["header_moduleluid"] = $header_moduleluid;
		}

		//ADD VIDEO ASSET
		//add module 
		$video_moduleid = $this->modules_model->add( array( "module_type_id"=>2 ) );
		$response["video_moduleid"] = $video_moduleid;
		//add media
		$video_mediaid = $this->media_model->add( array( "media_type_id"=>2, "filename"=>$video_name ) );
		$response["video_mediaid"] = $video_mediaid;
		//add module media lu
		$video_medialuid = $this->module_media_lu_model->add( array( "module_id"=>$video_moduleid, "media_id"=>$video_mediaid ) );
		$response["video_medialuid"] = $video_medialuid;
		//add project module lu
		$video_moduleluid = $this->project_module_lu_model->add( array( "project_id"=>$projectid, "module_id"=>$video_moduleid ) );
		$response["video_moduleluid"] = $video_moduleluid;

		var_dump($response);
	}

	public function addprojectvideo( $projectid,$video_name ){
		echo "adding project video to: ". $projectid. " with filename:" .$video_name;

		$response = array();

		//ADD VIDEO ASSET
		//add module 
		$video_moduleid = $this->modules_model->add( array( "module_type_id"=>2 ) );
		$response["video_moduleid"] = $video_moduleid;
		//add media
		$video_mediaid = $this->media_model->add( array( "media_type_id"=>2, "filename"=>$video_name ) );
		$response["video_mediaid"] = $video_mediaid;
		//add module media lu
		$video_medialuid = $this->module_media_lu_model->add( array( "module_id"=>$video_moduleid, "media_id"=>$video_mediaid ) );
		$response["video_medialuid"] = $video_medialuid;
		//add project module lu
		$video_moduleluid = $this->project_module_lu_model->add( array( "project_id"=>$projectid, "module_id"=>$video_moduleid ) );
		$response["video_moduleluid"] = $video_moduleluid;

		var_dump($response);
	}

	public function deletemodule( $moduleid ){
		echo "deleting module: ". $moduleid;

		$response = array();

		//remove module
		$moduleresponse = $this->modules_model->delete(  "id",$moduleid );
		$response["moduleresponse"] = $moduleresponse;

		//remove project lookup records for this module
		$moduleluresponse = $this->project_module_lu_model->delete(  "module_id",$moduleid );
		$response["moduleluresponse"] = $moduleluresponse;

		//remove media lookup records for this module
		$medialuresponse = $this->module_media_lu_model->delete(  "module_id",$moduleid );
		$response["medialuresponse"] = $medialuresponse;

		//remove module
		$moduleresponse = $this->modules_model->delete(  "id",$moduleid );
		$response["moduleresponse"] = $moduleresponse;

		var_dump($response);
	}
}
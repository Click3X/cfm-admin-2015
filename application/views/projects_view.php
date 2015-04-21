<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo strtoupper(SITE.' admin '.ENVIRONMENT); ?></title>


		<script src="<?php echo base_url(); ?>js/vendor/modernizr-2.8.3.min.js"></script>
		<script src="//use.typekit.net/hqh3atb.js"></script>
        <script>try{Typekit.load();}catch(e){}</script>

        <script>
        	var base_url = "<?php echo base_url(); ?>";
        	var client_domain = "<?php echo $this->config->item('client_domain'); ?>";
        	var site_id = "<?php echo SITE; ?>";
        </script>

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.simple-dtpicker.css">


       
	</head>
	<body>
		<div class="toolbar">
			<select onchange="location = this.options[this.selectedIndex].value;">
				<?php foreach ($categories as $k => $c): ?>
				<option value="<?php echo base_url(); ?>projects/category/<?php echo $c->category_name; ?>" <?php echo ( $c->category_name == $category->category_name ) ? 'selected' : ''; ?> ><?php echo $c->category_name; ?></option>
				<?php endforeach; ?>
			</select>
			<!-- <a class="view-media-button button">VIEW ALL MEDIA</a> -->
			<a class="add-toggle-button button">+</a>
			<a class="save-button button">SAVE</a>
			<!-- <input class="view-toggle" type="radio" name="view" value="details" checked /><label>Details</label>
			<input class="view-toggle" type="radio" name="view" value="list" /><label>List</label> -->
		</div> 

		<div class="projects-container">
			<div class="projects-container-inner">
				<ul class="projects-list">
					<?php foreach ($projects as $k => $p): ?>
					<?php $this->load->view( "project_template_view", array("project"=>$p) ); ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<!-- add project modal -->
		<div class="modal-container" id="add-project-modal">
			<div class="addproject-panel">
				<form id="addproject-form">
					<input type="hidden" value="0" name="order" />
					<input type="hidden" value="default" name="client_logo" />
					<input type="hidden" value="default" name="thumbnail_image" />
					<input type="hidden" value="<?php echo $category->id; ?>" name="category_id" />
					<fieldset>
						<div class="project-title"><h1><textarea data-border-offset="-30" name="title">Title</textarea></h1></div>
						<p id="slug"><textarea data-border-offset="-22" name="slug">slug</textarea></p>
						<h2><textarea data-border-offset="-30" name="heading">Heading</textarea></h2>
						<h3><textarea data-border-offset="-42" name="subhead">Subhead</textarea></h3>
						<p><textarea data-border-offset="-22" name="description">Description</textarea></p>
					</fieldset>
					<a class="add-button button">ADD</a>
				</form>
			</div>
		</div>

		<script src='<?php echo base_url(); ?>js/vendor/jquery-1.11.2.min.js'></script>
        <script src='<?php echo base_url(); ?>js/vendor/jquery.autosize.js'></script>
        <script src="<?php echo base_url(); ?>js/projects.js"></script>
        <script src='<?php echo base_url(); ?>js/vendor/jquery.easyModal.js'></script>
        <script src="<?php echo base_url(); ?>js/vendor/jquery.simple-dtpicker.js"></script>

	</body>
</html>
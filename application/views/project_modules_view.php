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
        </script>

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
	</head>
	<body>
		<!-- ADD A MODULE -->
		<div class="select-container toolbar">
			<select onchange="btnShow()" id="module-selection">
				<option selected="true" disabled="disabled" value="">Add Module</option>
				<option value="banner-image">Banner Image</option> 
				<option value="banner-video">Banner Video</option>
				<option value="gallery">Gallery</option>			
			</select>
			<!-- <button id="addm-btn">Add</button> -->
			<a class="add-module-button button">+</a>
			<a href="<?php echo base_url(); ?>projects" class="back-button button">BACK</a>
		</div>

		<!-- MODULE LIST -->
		<div class="project" data-id="<?php echo $project->id; ?>">
			<div class="project-inner">
				<div class="project-title"><h1><?php echo $project->title; ?></h1></div>
				<div class="project-details">

					<div class="module-container">
						<?php foreach($modules as $module) : ?>


							<div class="module <?php echo $module->module_type_name; ?>">
								<h2><?php echo strtoupper( $module->module_type_name ); ?></h2>
								<div class="media-container">
									<?php foreach ($module->media as $key => $media): ?> 

									<!-- if module is video or banner image -->
									<?php if($module->module_type_name != "gallery") : ?>
										<div class="media">
											<?php if($module->module_type_name == "banner-video") : ?>
												<video width="700" height="394" controls poster="http://media.click3x.com/images/<?php echo SITE; ?>/modules/<?php echo $module->module_type_name."/".$media->filename.".jpg"; ?>">
												  <source src="http://media.click3x.com/video/<?php echo $media->filename.".mp4"; ?>" type="video/mp4">
												  <source src="http://media.click3x.com/video/<?php echo $media->filename.".webm"; ?>" type="video/webm">
												</video>
											<?php else: ?>
												<img src="http://media.click3x.com/images/<?php echo SITE; ?>/modules/<?php echo $module->module_type_name."/".$media->filename.".".$media->media_type_name; ?>" />
											<?php endif; ?>
											
											<h4 class="module-filename"><?php echo $media->filename; ?></h4>
											<!-- <a class="delete-module-button button" data-module-id="<?php echo $module->module_id; ?>" data-project-id="<?php echo $project->id; ?>">X</a> -->
										</div>

										<!-- hidden modal for edit -->
										<div class="modal-container edit-modal" id="edit-modal-<?php echo $module->module_id; ?>">
											<h2>Edit <?php echo strtoupper( $module->module_type_name ); ?></h2>
											
											<?php echo form_open('projects/updateModule') ?>
												<label for="<?php echo $module->module_type_name; ?>">Edit <?php echo $module->module_type_name; ?> file name:</label><br>
												<input type="text" name="<?php echo $module->module_type_name; ?>" value="<?php echo $media->filename; ?>" required /><br>
												<label for="<?php echo $module->module_type_name; ?>-title">Edit banner video play button text: </label><br>
												<input type="text" name="<?php echo $module->module_type_name; ?>-title" value="<?php echo (!isset($module->title) || empty( $module->title )) ? '' : $module->title ; ?>"><br> 
												<label for="<?php echo $module->module_type_name; ?>-subhead">Edit <?php echo $module->module_type_name; ?> heading: </label><br>
												<input type="text" name="<?php echo $module->module_type_name; ?>-subhead" value="<?php echo (!isset($module->subhead) || empty( $module->subhead )) ? '' : $module->subhead ; ?>">
												<input type="hidden" value="<?php echo $project->id; ?>" name="project_id">
												<div>
													<input type="submit" name="submit" value="Save Changes" class="update-modal button" data-project-id="<?php echo $project->id; ?>" />
													<a class="cancel-modal button">Cancel</a>
												</div>
											</form>
										</div>
										<!-- end of modal -->

									<!-- if module is gallery -->
									<?php else: ?>
										<div class="media gallery-media">
											<div class="gallery-media-container">
												<img src="http://media.click3x.com/images/<?php echo SITE; ?>/modules/<?php echo $module->module_type_name."/".$media->filename.".".$media->media_type_name; ?>" />
											</div>
											<h4 class="module-filename"><?php echo $media->filename; ?></h4>
											<a class="delete-gallery-media-button button" data-media-id="<?php echo $media->media_id; ?>" data-module-id="<?php echo $module->module_id; ?>" data-project-id="<?php echo $project->id; ?>">x</a>


										</div>
									<?php endif; ?>



									<?php endforeach; ?>
								</div>
								<a class="edit-module-button button" data-module-id="<?php echo $module->module_id; ?>" data-project-id="<?php echo $project->id; ?>" href="#edit-modal-<?php echo $module->module_id; ?>">EDIT</a>
								<a class="delete-module-button button" data-module-id="<?php echo $module->module_id; ?>" data-project-id="<?php echo $project->id; ?>">X</a>
							</div>
						<?php endforeach; ?>
						<!-- end of module foreach -->
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL -->
		<!-- banner  -->
		<div class="modal-container" id="banner-image-modal">
			<h2>Add new banner image</h2>
			<?php //$attributes = array('name' => 'bannerImageForm'); ?>
			<?php //echo validation_errors(); ?>
			<?php echo form_open('projects/saveModule') ?>
				<label for="banner-image">Enter banner image file name (no extension):</label><br>
				<input type="text" name="banner-image" required />
				<label for="media-type"></label>
				<select name="media-type" class="media-type">
					<option value="1">jpg</option>
					<option value="4">png</option>
				</select><br>
<!-- 				<label for="banner-image-title"></label><br>
				<input type="text" name="banner-image-title"> -->
				<label for="banner-image-subhead">Banner image heading: </label><br>
				<input type="text" name="banner-image-subhead">
				<input type="hidden" value="<?php echo $project->id; ?>" name="project_id">
				<div>
					<input type="submit" name="submit" value="Save" class="save-modal button"/>
					<a class="cancel-modal button">Cancel</a>
				</div>
			</form>

		</div>
		<!-- video -->
		<div class="modal-container" id="banner-video-modal">
			<h2>Add new banner video</h2>
			<?php //echo validation_errors(); ?>
			<?php echo form_open('projects/saveModule') ?>
				<label for="banner-video">Enter banner video file name (no extension):</label><br>
				<input type="text" name="banner-video" required /><br>
				<label for="banner-image-title">Banner video play button text: </label><br>
				<input type="text" name="banner-video-title"><br> 
				<label for="banner-image-subhead">Banner video heading: </label><br>
				<input type="text" name="banner-video-subhead">
				<input type="hidden" value="<?php echo $project->id; ?>" name="project_id">
				<div>
					<input type="submit" name="submit" value="Save" class="save-modal button"/>
					<a class="cancel-modal button">Cancel</a>
				</div>
			</form>
		</div>
		<!-- gallery -->
		<div class="modal-container" id="gallery-modal">
			<h2>Add new gallery</h2>
			<span><em>Click plus to add multiple images</em></span>
			<!-- add entry button -->
			<a class="add-gallery-img-button button">+</a>
			<?php //$attributes = array('class' => 'galleryForm'); ?>
			<?php //echo validation_errors(); ?>
			<?php echo form_open('projects/saveModule') ?>
				<div id="gallery-input-container">

					<div class="single-media-container">
						<label >Enter gallery image file name (no extension):</label><br> 
						<input type="text" name="gallery[]" required />
						<label for="media-type"></label>
						<select name="media-type[]" class="media-type">
							<option value="1">jpg</option>
							<option value="3">gif</option>
							<option value="4">png</option>
						</select><br> 
						<!-- add title -->
						<label >Enter gallery title (appear as hover text):</label><br> 
						<input type="text" name="gallery-title[]" /><br> 
						<!-- add link -->
						<label >Enter gallery link (eg: http://google.com):</label><br> 
						<input type="text" name="gallery-link[]" />
					</div>
					<!-- append new entries here -->
				</div>
				<hr>
				
				<label for="banner-image-subhead" id="gallery-head">Gallery heading: </label><br>
				<input type="text" name="gallery-subhead">
				<input type="hidden" value="<?php echo $project->id; ?>" name="project_id">
				<div>
					<input type="submit" name="submit" value="Save" class="save-modal button"/>
					<a class="cancel-modal button">Cancel</a>
				</div>
			</form>
		</div>
		<!-- END OF MODAL -->

		<script src='<?php echo base_url(); ?>js/vendor/jquery-1.11.2.min.js'></script>
		<script src='<?php echo base_url(); ?>js/vendor/jquery.easyModal.js'></script>
		<script src='<?php echo base_url(); ?>js/modules.js'></script>
	</body>
</html>
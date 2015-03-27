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
									<div class="media">
										<?php if($module->module_type_name == "banner-video") : ?>
											<video width="700" height="394" controls poster="http://media.click3x.com/images/<?php echo SITE; ?>/modules/<?php echo $module->module_type_name."/".$media->filename.".jpg"; ?>">
											  <source src="http://media.click3x.com/video/<?php echo $media->filename.".mp4"; ?>" type="video/mp4">
											  <source src="http://media.click3x.com/video/<?php echo $media->filename.".webm"; ?>" type="video/webm">
											</video>
										<?php else: ?>
											<img src="http://media.click3x.com/images/<?php echo SITE; ?>/modules/<?php echo $module->module_type_name."/".$media->filename.".".$media->media_type_name; ?>" />
										<?php endif; ?>
										<input type="text" name="filename" value="<?php echo $media->filename; ?>"/>
									</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<script src='<?php echo base_url(); ?>js/vendor/jquery-1.11.2.min.js'></script>
	</body>
</html>
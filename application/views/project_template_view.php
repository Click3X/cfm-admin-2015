<li id="project-<?php echo $project->id; ?>" class="project-wrapper">
	<div class="project" data-id="<?php echo $project->id; ?>">
		<div class="project-inner">
			<div class="project-title"><h1><textarea data-border-offset="-30" data-column-name="title"><?php echo $project->title; ?></textarea></h1><a class="delete-button button">X</a></div>
			<div class="project-details">
				<div class="project-thumbnails">
					<div class="project-thumbnails-inner">
						<div class="project-thumbnail thumbnail">
							<div class="thumbnail-inner">
								<img id="thumbnail_image" src="http://media.click3x.com/images/<?php echo SITE; ?>/project_thumbnails/<?php echo $project->thumbnail_image; ?>.jpg" />
								<p><textarea class="thumbnail" data-border-offset="-22" data-column-name="thumbnail_image"><?php echo $project->thumbnail_image; ?></textarea></p>
							</div>
						</div>
						<div class="client-logo thumbnail">
							<div class="thumbnail-inner">
								<img id="client_logo" src="http://media.click3x.com/images/<?php echo SITE; ?>/client_logos/<?php echo $project->client_logo; ?>.jpg" />
								<p><textarea class="thumbnail" data-border-offset="-22" data-column-name="client_logo"><?php echo $project->client_logo; ?></textarea></p>
							</div>
						</div>
					</div>
				</div>
				<div class="project-about">
					<?php 
						$desc = $project->description;
						$desc = str_replace("</p><p>", "\n\n", $project->description); 
						$desc = str_replace("<br />", "\r", $desc); 												
					?>
					<div class="project-about-inner">
						<p><textarea data-border-offset="-22" data-column-name="slug"><?php echo $project->slug; ?></textarea></p>
						<h2><textarea data-border-offset="-30" data-column-name="heading"><?php echo $project->heading; ?></textarea></h2>
						<h3><textarea data-border-offset="-42" data-column-name="subhead"><?php echo $project->subhead; ?></textarea></h3>
						<p><textarea data-border-offset="-22" data-column-name="description"><?php echo $desc; ?></textarea></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</li>
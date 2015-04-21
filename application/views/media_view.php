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

		<script src='<?php echo base_url(); ?>js/vendor/jquery-1.11.2.min.js'></script>
        <script src='<?php echo base_url(); ?>js/vendor/jquery.autosize.js'></script>
        <script src="<?php echo base_url(); ?>js/media.js"></script>
	</body>
</html>
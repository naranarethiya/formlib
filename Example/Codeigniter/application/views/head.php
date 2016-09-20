	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		<?php 
			if(isset($pageTitle)) {
				echo $pageTitle.' | formLib';
			}
		?>
	</title>
	<!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url().'public/bootstrap/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url().'public/plugins/font-awesome-4.5.0/css/font-awesome.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url().'public/dist/css/Admin.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url().'public/dist/css/skins/_all-skins.min.css'; ?>">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script type="text/javascript">
		var base_url='<?php echo base_url(); ?>';
	</script>
	<!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url().'public/plugins/jQuery/jQuery-3.1.0.min.js'; ?>"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url().'public/bootstrap/js/bootstrap.min.js'; ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url().'public/dist/js/app.min.js'; ?>"></script>
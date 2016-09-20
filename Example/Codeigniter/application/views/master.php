<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('head'); ?>	
	</head>

	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php $this->load->view('header'); ?>
			<?php $this->load->view('sidebar'); ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<?php
					if(isset($title)) {
						echo "<h1>".$title."</h1>";
					}
					?>
				</section>
				<div class="row">
					<div class="col-md-12">
						<?php $this->load->view("show_msg"); ?>
					</div>
				</div>

				<!-- Main content -->
				<section class="content">
					<!-- Your Page Content Here -->
					<?php echo $main_content; ?>
				</section>
				<!-- /.content -->

			</div>
			<!-- /.content-wrapper -->
		</div>
		<?php $this->load->view('footer.php') ?>
	</body>
</html>
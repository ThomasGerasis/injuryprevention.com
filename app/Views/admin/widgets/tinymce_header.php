<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" context="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('admin/assets/css/icons/icomoon/styles.min.css') ?>?v=2" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/bootstrap.min.css') ?>?v=2" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/bootstrap_limitless.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/layout.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/components.min.css') ?>?v=2" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/colors.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/sweetalert2.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/custom.css') ?>?v=<?php echo time(); ?>" rel="stylesheet">
	<link href="<?php echo base_url('admin/assets/css/token-input.css') ?>" rel="stylesheet">

   <script type="text/javascript">
		var config = {
			base_url: "<?php echo base_url(); ?>"
		};
	</script>
</head>
<body>
		
	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">			
			<?php if (session()->getFlashdata('success')) : ?>
				<div class="alert alert-success mt-2 mx-2 mb-0"><?php echo session()->getFlashdata('success') ?>
				</div>
			<?php endif ?>
			<?php if (session()->getFlashdata('info')) : ?>
				<div class="alert alert-info mt-2 mx-2 mb-0"><?php echo session()->getFlashdata('info') ?>
				</div>
			<?php endif ?>
			<?php if (isset($errors) && is_array($errors)) : ?>
				<?php foreach ($errors as $error) : ?>
					<div class="alert alert-danger mt-2 mx-2 mb-0"><?= $error ?? '' ?></div>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (session()->getFlashdata('error')) : ?>
				<div class="alert alert-danger mt-2 mx-2 mb-0"><?php echo session()->getFlashdata('error') ?>
				</div>
			<?php endif; ?>
			
			<div class="content">
				<div class="card">
					<div class="card-body">
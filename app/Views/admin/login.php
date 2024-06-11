<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" context="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Login :: ADMIN</title>
<link rel="icon" href="<?php echo base_url('admin/assets/img/fav.png?v=2') ?>">
<meta name="description" content="">
<link href="<?php echo base_url('admin/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('admin/assets/css/sweetalert2.min.css') ?>" rel="stylesheet">
<script type="text/javascript">
    let config = {
        base_url: "<?php echo base_url(); ?>"
    };
</script>
</head>
	<body>
		<div id="login_div" class="mt-5">
			<div class="container">
				<div class="row justify-content-md-center">
					<div class="col-12 col-md-6">
						<h3 class="text-center py-2 mb-3">
                            <img src="<?php echo base_url('/assets/img/footer-logo.svg');?>" height="300" class="img-responsive center-block">
                        </h3>
						<?php if(session()->getFlashdata('success')):?>
							<div class="alert alert-success mt-2 mx-2 mb-0"><?php echo session()->getFlashdata('success')?></div>
						<?php endif?>
						<?php if(session()->getFlashdata('info')):?>
							<div class="alert alert-info mt-2 mx-2 mb-0"><?php echo session()->getFlashdata('info')?></div>
						<?php endif?>
						<?php if(session()->getFlashdata('error')):?>
							<div class="alert alert-danger mt-2 mx-2 mb-0"><?php echo session()->getFlashdata('error')?></div>
						<?php endif?>
                        <div class="row">
                            <div class="col-auto mx-auto">
                                <div id="g_id_onload"
                                     data-client_id="<?php echo GOOGLE_CLIENT_ID; ?>"
                                     data-login_uri=<?php echo SITE_URL . 'admin/login/googleauth'; ?>
                                     data-auto_prompt="false">
                                </div>
                                <div class="g_id_signin mt-3"
                                     data-type="standard"
                                     data-size="large"
                                     data-theme="outline"
                                     data-text="sign_in_with"
                                     data-shape="rectangular"
                                     data-logo_alignment="center">
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
        <script src="https://accounts.google.com/gsi/client" async="" defer=""></script>
	</body>
</html>
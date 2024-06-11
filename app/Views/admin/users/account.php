<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><?php echo $pageData['title']; ?></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url('admin/dashboard'); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
			</div>
		</div>

	</div>
</div>

<div class="content">
	<div class="card">
		<div class="card-body">
			<form method="post" class="myvalidation">
				<div class="form-group row">
					<label class="col-form-label col-lg-2">Username*</label>
					<div class="col-lg-10">
						<?php if (empty($user['id'])) { ?>
							<input type="text" class="form-control required" name="username" value="<?php echo @$user['username']; ?>">
						<?php } else { ?>
							<input type="text" class="form-control" readonly value="<?php echo $user['username']; ?>">
						<?php } ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-lg-2">Email*</label>
					<div class="col-lg-10">
						<input type="text" class="form-control required" name="email" value="<?php echo @$user['email']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-lg-2">Current password</label>
					<div class="col-lg-10">
						<input type="password" class="form-control" name="current_pass" value="" autocomplete="off">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-lg-2">New password</label>
					<div class="col-lg-10">
						<input type="password" class="form-control" name="new_pass" value="" autocomplete="off">
						<span class="form-text text-muted">Τουλάχιστον 8 χαρακτήρες, από τους οποίος 1 αριθμό, 1 κεφαλαίο και 1 πεζο γράμμα και 1 μη αλφαριθμητικό χαρακτήρα.</span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-lg-2">Retype new password</label>
					<div class="col-lg-10">
						<input type="password" class="form-control" name="new_pass2" value="" autocomplete="off">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-lg-2"></label>
					<div class="col-lg-10">
						<button type="submit" class="btn btn-primary">Αποθήκευση <i class="icon-database-add ml-2"></i></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
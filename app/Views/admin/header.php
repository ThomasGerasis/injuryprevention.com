<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" context="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php echo @$pageData['title']; ?></title>
	<link rel="icon" href="<?php echo base_url('admin/assets/img/image.png') ?>?v=2">
	<meta name="description" content="">
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

	<?php if (isset($cssFiles) && is_array($cssFiles)) {
		foreach ($cssFiles as $cssFile) { ?>
			<link href="<?php echo "/dist/css/$cssFile.css" ?>?v=2" rel="stylesheet">
	<?php }
	} ?>

	<?php if (isset($css_files) && is_array($css_files)) {
		foreach ($css_files as $css_file) { ?>
			<link href="<?php echo base_url('admin/assets/css/' . $css_file) ?>?v=2" rel="stylesheet">
	<?php }
	} ?>

	<script type="text/javascript">
		var config = {
			base_url: "<?php echo base_url(); ?>"
		};
	</script>

</head>

<body class="navbar-top">

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark fixed-top">
		<div class="navbar-brand py-0">
			<a href="<?php echo base_url(); ?>" class="d-inline-block">
				<img src="<?php echo base_url('assets/img/logo.svg'); ?>" alt="">
			</a>
		</div>
		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>
			<?php
			$user = new \App\Libraries\User(session()->get('loggedUser'));
			$userData = $user->getInfo();
			?>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<img src="<?php echo base_url('admin/assets/img/image.png'); ?>" class="rounded-circle mr-2" height="34" alt="">
						<span><?php echo $userData['username'] ?? ''; ?></span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<?php if (!empty($userData['swapped_admin']) && !empty($userData['swapped_admin_name'])) { ?>
							<a href="<?php echo base_url('admin/dashboard/swapAdmin'); ?>" class="dropdown-item"><i class="icon-backward2"></i> Back to <?php echo $userData['swapped_admin_name']; ?></a>
						<?php } ?>
						<div class="dropdown-divider"></div>
						<a href="<?php echo base_url('admin/logout'); ?>" class="dropdown-item"><i class="icon-switch2"></i> Αποσύνδεση</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-fixed sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->

			<!-- Sidebar content -->
			<div class="sidebar-content">
				<?php $router = service('router');
				$controller = $router->controllerName();
				$method = $router->methodName();
				$params = $router->params(); ?>
				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<li class="nav-item">
							<a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\Dashboard' ? ' active' : ''; ?>">
								<i class="icon-home4"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<?php if (isset($userData['is_admin'])) { ?>
							<li class="nav-item nav-item-submenu <?php echo (($controller == '\App\Controllers\Admin\GeoCountries' || $controller == '\App\Controllers\Admin\AffiliateLinks' || $controller == '\App\Controllers\Admin\Menu' || $controller == '\App\Controllers\Admin\Banners' || $controller == '\App\Controllers\Admin\BannersSchedule' || $controller == '\App\Controllers\Admin\FaqCategories' || $controller == '\App\Controllers\Admin\SiteOptions') ? 'nav-item-expanded nav-item-open' : ''); ?>">
								<a href="#" class="nav-link"><i class="icon-cogs"></i> <span>Settings Panel</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Settings Panel">
									<li class="nav-item"><a href="<?php echo base_url('admin/menu'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\Menu' ? 'active' : ''; ?>">Menu</a></li>
									<li class="nav-item-divider"></li>
                                    <li class="nav-item"><a href="<?php echo base_url('admin/footerMenu'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\FooterMenu' ? 'active' : ''; ?>">Footer Menu</a></li>
<!--                                    <li class="nav-item-divider"></li>-->
<!--									<li class="nav-item"><a href="--><?php //echo base_url('admin/faqCategories'); ?><!--" class="nav-link --><?php //echo ($controller == '\App\Controllers\Admin\FaqCategories' ? 'active' : ''); ?><!--">FAQ categories</a></li>-->
									<li class="nav-item-divider"></li>
									<li class="nav-item nav-item-submenu <?php echo ($controller == '\App\Controllers\Admin\SiteOptions' ? 'nav-item-open' : '');?>">
										<a href="#" class="nav-link <?php echo ($controller == '\App\Controllers\Admin\SiteOptions' ? 'active' : '');?>">Site options</a>
										<ul class="nav nav-group-sub" <?php echo ($controller == '\App\Controllers\Admin\SiteOptions' ? 'style="display:block;"' : '');?>>
											<li class="nav-item"><a href="<?php echo base_url('admin/siteOptions/info');?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\SiteOptions' && @$params[0] == 'info' ? 'active' : ''; ?>">Information / Socials</a></li>
										</ul>
									</li>
								</ul>
							</li>
						<?php } ?>

						<?php if (isset($userData['is_admin'])) { ?>
							<li class="nav-item">
								<a href="<?php echo base_url('admin/users'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\Users' ? ' active' : ''; ?>">
									<i class="icon-users"></i>
									<span>Backend users</span>
								</a>
							</li>
						<?php } ?>

						<?php if (isset($userData['is_admin'])) { ?>
							<li class="nav-item">
								<a href="<?php echo base_url('admin/homepage'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\Homepage' ? ' active' : ''; ?>">
									<i class="icon-home5"></i>
									<span>Homepage</span>
								</a>
							</li>
						<?php } ?>

                        <?php if ($user->can('admin/articles')) { ?>
                            <li class="nav-item nav-item-submenu <?php echo ($controller == '\App\Controllers\Admin\Articles' || $controller == '\App\Controllers\Admin\ArticleCategories' ? ' nav-item-expanded nav-item-open' : ''); ?>">
                                <a href="<?php echo base_url('admin/articles'); ?>" class="nav-link"><i class="icon-stack"></i> <span>Articles</span></a>
                                <ul class="nav nav-group-sub" data-submenu-title="Articles">
                                    <li class="nav-item"><a href="<?php echo base_url('admin/articles'); ?>" class="nav-link <?php echo ($controller == '\App\Controllers\Admin\Articles' && $method == 'index' ? 'active' : ''); ?>">List articles</a>
                                    </li>
                                    <li class="nav-item"><a href="<?php echo base_url('admin/articles/edit'); ?>" class="nav-link <?php echo ($controller == '\App\Controllers\Admin\Articles' && $method == 'edit' && empty($params) ? 'active' : ''); ?>">New article</a></li>
                                    <li class="nav-item-divider"></li>
                                    <li class="nav-item"><a href="<?php echo base_url('admin/articleCategories'); ?>" class="nav-link <?php echo ($controller == '\App\Controllers\Admin\ArticleCategories' && $method == 'index' ? 'active' : ''); ?>">List categories</a>
                                    </li>
                                    <li class="nav-item"><a href="<?php echo base_url('admin/articleCategories/edit'); ?>" class="nav-link <?php echo ($controller == '\App\Controllers\Admin\ArticleCategories' && $method == 'edit' && empty($params) ? 'active' : ''); ?>">New category</a></li>
                                </ul>
                            </li>
                        <?php } ?>

						<?php if ($user->can('pages')) { ?>
							<li class="nav-item nav-item-submenu <?php echo $controller == '\App\Controllers\Admin\Pages' ? ' nav-item-expanded nav-item-open' : ''; ?>">
								<a href="<?php echo base_url('admin/pages'); ?>" class="nav-link"><i class="icon-book"></i> <span>Pages</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Pages">
									<li class="nav-item"><a href="<?php echo base_url('admin/pages'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\Pages' && $method == 'index' ? 'active' : ''; ?>">List pages</a>
									</li>
									<li class="nav-item"><a href="<?php echo base_url('admin/pages/edit'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\Pages' && $method == 'edit' && empty($params) ? 'active' : ''; ?>">New page</a></li>
								</ul>
							</li>
						<?php } ?>

                        <?php if ($user->can('content')) { ?>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/mediaLibrary'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\MediaLibrary' ? ' active' : ''; ?>">
                                    <i class="icon-images3"></i>
                                    <span>Media library</span>
                                </a>
                            </li>
                        <?php } ?>


                        <?php if ($user->can('site_users')) { ?>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/siteUsers'); ?>" class="nav-link <?php echo $controller == '\App\Controllers\Admin\SiteUsers' ? ' active' : ''; ?>">
                                    <i class="icon-users2"></i>
                                    <span>Site Users</span>
                                </a>
                            </li>
                        <?php } ?>


                    </ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
		</div>
		<!-- /main sidebar -->

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
			<?php endif;

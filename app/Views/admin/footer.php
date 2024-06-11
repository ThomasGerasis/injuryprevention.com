			<a href="#" id="scroll-toper" class="btn btn-sm btn-outline-primary position-fixed rounded-round px-1" style="display:none;bottom:10px;right:5px;width:32px;"><i class="icon-arrow-up7"></i></a>
			</div>
			<!-- /main content -->

			</div>

			<!-- MODALS -->

			<div class="modal fade" id="publish_date_modal">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Ενημέρωση ημ/νίας δημοσίευσης</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form class="form-horizontal" id="update_publishdate_form" data-ajax-url="<?php echo base_url('admin/publishDates/update/'); ?>" data-orig-ajax-url="<?php echo base_url('admin/publishDates/update/'); ?>">
								<div class="form-group">
									<label class="col-sm-2 control-label">Ημ/νία*</label>
									<div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-prepend">
												<span class="input-group-text"><i class="icon-calendar5"></i></span>
											</span>
											<input type="text" name="publish_date" class="form-control pickadate-format" placeholder="" value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Ώρα*</label>
									<div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-prepend">
												<span class="input-group-text"><i class="icon-watch2"></i></span>
											</span>
											<input type="text" id="change_publish_time" name="publish_time" class="form-control pickatime-format" value="">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" class="btn btn-primary" value="Ενημέρωση">
									</div>
								</div>
							</form>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade" id="shortcode_modal" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="text-center my-3"><img src="<?php echo base_url() ?>/assets/img/ajax-loader.gif">
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<div class="modal fade" id="image_modal">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Image bank</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="text-center my-3"><img src="<?php echo base_url() ?>/assets/img/ajax-loader.gif">
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade" id="simple_modal">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="text-center my-3"><img src="<?php echo base_url() ?>/assets/img/ajax-loader.gif">
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade" id="loadingModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Please wait</h4>
						</div>
						<div class="modal-body">
							<div class="text-center"><img src="<?php echo base_url(); ?>/assets/img/ajax-loader.gif">
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<script src="<?php echo base_url('admin/assets/js/main/jquery.min.js') ?>">
			</script>
			<script src="<?php echo base_url('admin/assets/js/main/bootstrap.bundle.min.js') ?>">
			</script>
			<script src="<?php echo base_url('admin/assets/js/plugins/loaders/blockui.min.js') ?>">
			</script>

			<script src="<?php echo base_url('admin/assets/js/app.js') ?>?v=4">
			</script>
			<script src="<?php echo base_url('admin/assets/js/sweetalert2.min.js') ?>">
			</script>

			<?php if (!empty($load_datetime) || !empty($tinymce)) { ?>
				<script src="<?php echo base_url('admin/assets/js/moment.js') ?>"></script>
				<script src="<?php echo base_url('admin/assets/js/plugins/pickers/daterangepicker.js') ?>"></script>
				<script src="<?php echo base_url('admin/assets/js/plugins/pickers/anytime.min.js') ?>"></script>
				<script src="<?php echo base_url('admin/assets/js/plugins/pickers/pickadate/picker.js') ?>"></script>
				<script src="<?php echo base_url('admin/assets/js/plugins/pickers/pickadate/picker.date.js') ?>"></script>
				<script src="<?php echo base_url('admin/assets/js/plugins/pickers/pickadate/picker.time.js') ?>"></script>
				<script src="<?php echo base_url('admin/assets/js/plugins/pickers/pickadate/legacy.js') ?>"></script>
			<?php } ?>

			<?php if (!empty($tinymce)) { ?>
				<script src="<?php echo base_url('admin/assets/js/tinymce/js/tinymce.js'); ?>?v=3" referrerpolicy="origin"></script>
				<script src="<?php echo base_url('admin/assets/js/main/jquery-ui.min.js') ?>">
				</script>
				<script src="<?php echo base_url('admin/assets/js/plugins/forms/selects/select2.min.js') ?>">
				</script>
				<script src="<?php echo base_url('admin/assets/js/jquery.fileupload.js') ?>">
				</script>
				<script src="<?php echo base_url('admin/assets/js/jquery.tokeninput.js') ?>">
				</script>
				<script src="<?php echo base_url('admin/assets/js/jquery.tmpl.min.js') ?>">
				</script>
			<?php } ?>
			<?php if (isset($load_js) && is_array($load_js)) {
				foreach ($load_js as $js_file) { ?>
					<script src="<?php echo "/dist/js/$js_file.js" ?>?v=<?php echo time(); ?>">
					</script>
				<?php }
			}
			if (isset($loadJs) && is_array($loadJs)) {
				foreach ($loadJs as $jsFile) { ?>
					<script src="<?php echo base_url('admin/assets/js/' . $jsFile) ?>?v=<?php echo time(); ?>">
					</script>
			<?php }
			} ?>
			<script src="<?php echo base_url('admin/assets/js/igaming.js') ?>?v=<?php echo time(); ?>">
			</script>
			</body>

			</html>
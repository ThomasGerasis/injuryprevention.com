<div class="card">
	<div class="card-header header-elements-inline p-1">
		<a class="btn btn-outline bg-primary text-primary-800 btn-icon btn-movefaq-part"><i class="icon-move"></i></a>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="remove"></a>
			</div>
		</div>
	</div>

	<div class="card-body p-1">
		<div class="form-group row">
			<label class="col-form-label col-sm-2">Ερώτηση</label>
			<div class="col-sm-10">
				<input type="text" name="faq[<?php echo $counter;?>][question]" class="form-control" required value="<?php echo $faq['question'];?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-2">Απάντηση</label>
			<div class="col-sm-10">
				<textarea class="form-control inline_editor" id="faq_<?php echo $counter;?>_answer" name="faq[<?php echo $counter;?>][answer]"><?php echo @$faq['answer'];?></textarea>
			</div>
		</div>
		<input type="hidden" class="sort_order" name="faq[<?php echo $counter;?>][order_num]" value="<?php echo $order;?>">
		<input type="hidden" name="faq[<?php echo $counter;?>][id]" value="<?php echo $faq['id'];?>">
	</div>
</div>
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4>Timeline Setup</h4>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="<?php echo base_url('admin/dashboard'); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                <span class="breadcrumb-item">Settings panel</span>
                <span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <input id="edit_type" class="d-none" value="option">
    <input id="edit_type_id" class="d-none" value="<?php echo $option['name']; ?>">
    <input id="edit_back_url" class="d-none" value="<?php echo base_url('admin/dashboard'); ?>">

    <form method="post" class="myvalidation" id="tinymce_form">
        <?php $values = (empty($option['value']) ? array() : json_decode($option['value'], true)); ?>
        <label>Main Title</label>
        <div class="form-group">
            <input type="text" name="main_title" class="form-control" placeholder="Main title" value="<?php echo @$values['main_title'];?>">
        </div>

        <label>Main mini description</label>
        <div class="form-group">
            <input type="text" name="main_description" class="form-control" placeholder="Main mini description" value="<?php echo @$values['main_description'];?>">
        </div>

        <label>Copyright Message</label>
        <div class="form-group">
            <input type="text" name="copyright_text" class="form-control" placeholder="Copyright Message" value="<?php echo @$values['copyright_text'];?>">
        </div>


        <label>All Players Button Text</label>
        <div class="form-group">
            <input type="text" name="button_players_text" class="form-control" placeholder="All Players Button Text" value="<?php echo @$values['button_players_text'];?>">
        </div>

        <label>Variance Button Text</label>
        <div class="form-group">
            <input type="text" name="variance_button_text" class="form-control" placeholder="Variance Button Text" value="<?php echo @$values['variance_button_text'];?>">
        </div>

        <label>Team Stats Button Text</label>
        <div class="form-group">
            <input type="text" name="team_stats_button_text" class="form-control" placeholder="Team Stats Button Text" value="<?php echo @$values['team_stats_button_text'];?>">
        </div>


        <div class="mb-3">
            <label>Player read more info text</label>
            <textarea class="tinymce_editor" name="players_info" class="form-control"><?php echo htmlentities(@$values['players_info']);?></textarea>
        </div>

        <div class="mb-3">
            <label>Variance read more info text</label>
            <textarea class="tinymce_editor" name="variance_info" class="form-control"><?php echo htmlentities(@$values['variance_info']);?></textarea>
        </div>

        <div class="mb-3">
            <label>Team Stats read more info text</label>
            <textarea class="tinymce_editor" name="team_stats_info" class="form-control"><?php echo htmlentities(@$values['team_stats_info']);?></textarea>
        </div>

        <div class="mb-3">
            <label>Player movement analysis read more info text</label>
            <textarea class="tinymce_editor" name="player_movement_info" class="form-control"><?php echo htmlentities(@$values['player_movement_info']);?></textarea>
        </div>


        <div class="form-group row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save <i class="icon-database-add ml-2"></i></button>
            </div>
        </div>
        
    </form>
</div>



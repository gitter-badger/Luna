<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

define( 'JEWEL_ROOT', '../' );
include( 'include/header.php' );
define( 'ASTRID', 2 );

Astrid::DrawAstridNav( 'settings', 'settings' );

?>
<form class="form-horizontal" method="post" action="settings.php">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Essentials<span class="pull-right"><button class="btn btn-primary" type="submit" name="save"><span class="fa fa-fw fa-check"></span> Save</button></span></h3>
		</div>
		<div class="panel-body">
			<input type="hidden" name="form_sent" value="1" />
			<fieldset>
				<div class="form-group">
					<label class="col-sm-3 control-label">Board title</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="form[board_title]" maxlength="255" value="<?php echo $luna_config['o_board_title'] ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Board description<span class="help-block">What's this board about?</span></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="form[board_desc]" maxlength="255" value="<?php echo $luna_config['o_board_desc'] ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Board tags<span class="help-block">Add some words that describe your board, separated by a comma</span></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="form[board_tags]" maxlength="255" value="<?php echo $luna_config['o_board_tags'] ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Board URL</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="form[base_url]" maxlength="100" value="<?php echo $luna_config['o_base_url'] ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Default language<span class="help-block">The default language</span></label>
					<div class="col-sm-9">
						<select class="form-control" name="form[default_lang]">
							<option value="English" selected>English</option>
						</select>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>
<?php include( 'include/footer.php' ) ?>
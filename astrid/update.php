<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

define( 'JEWEL_ROOT', '../' );
include( 'include/header.php' );

Astrid::DrawAstridNav( 'update', 'backstage' );

?>
<div class="row">
	<div class="col-sm-4 col-md-3">
		<form method="post" action="update.php">
			<input type="hidden" name="form_sent" value="1" />
			<fieldset>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Update ring<span class="pull-right"><button class="btn btn-primary" type="submit" name="save"><span class="fa fa-fw fa-check"></span> Save</button></span></h3>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td>
									<select class="form-control" id="update_ring" name="form[update_ring]" tabindex="1">
										<option value="0">Slow</option>
										<option value="1" selected>Normal</option>
										<option value="2">Preview</option>
										<option value="3">Nightly</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="col-sm-8 col-md-9">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title">End of life</h3>
			</div>
			<div class="panel-body">
				<p>You've selected to download updates only from the current branch, however, support for this branch has been dropped. Please check for updates in the Normal branch.</p>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Luna software updates<span class="pull-right"><a href="update.php?action=check_update" class="btn btn-primary"><span class="fa fa-fw fa-refresh"></span> Check for updates</a></span></h3>
			</div>
			<div class="panel-body">
				<h3>A new version is available!</h3>
				<p>A new version, Luna %s has been released. It's a good idea to update to the latest version of Luna, as it contains not only new features, improvements and bugfixes, but also the latest security updates.</p>
				<div class="btn-group">
					<a href="http://modernbb.be/cnt/get.php?id=4" class="btn btn-primary">Download Luna</a>
					<a href="http://getluna.org/changelog.php" class="btn btn-primary">Changelog</a>
				</div>
				<h3>You're using the latest version of Luna!</h3>
				<p>You're on our latest release! Nothing to worry about.</p>
				<h3>You're using a development version of Luna. Be sure to stay up-to-date.</h3>
				<p>We release every now and then a new build for Luna, one more stable then the other, for you to check out. You can keep track of this at <a href="http://getluna.org/lunareleases.php">our website</a>. New builds can contain new features, improved features, and/or bugfixes.</p>
				<p>At this point, we can only tell you that a new you're beyond the latest release. We can't tell you if there is a new preview available. You'll have to find out for yourself.</p>
			</div>
		</div>
	</div>
</div>
<?php include('include/footer.php') ?>
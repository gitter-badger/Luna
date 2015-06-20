<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

define( 'JEWEL_ROOT', '../' );
include('include/header.php');

Astrid::DrawAstridNav( 'about', 'backstage' );

?>
<div class="row">
	<div class="col-sm-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Navigation</h3>
			</div>
			<div class="list-group">
				<a href="#astrid" class="list-group-item"><span class="fa fa-fw fa-dashboard"></span> Astrid</a>
				<a href="#database" class="list-group-item"><span class="fa fa-fw fa-database"></span> Database</a>
				<a href="#others" class="list-group-item">Other improvements and notes</a>
			</div>
		</div>
	</div>
	<div class="col-sm-9">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">About Luna 2.0 Denim</h3>
			</div>
			<div class="panel-body panel-about">
				<a id="astrid"></a><h3><span class="fa fa-fw fa-dashboard"></span> Astrid</h3>
				<div class="row">
					<div class="col-sm-6">
						<h4>Rebuild from the ground up</h4>
						<p>Luna 2.0 comes with a whole new Backstage that has been rebuild from the ground up to suit your needs.</p>
					</div>
				</div>
				<a id="database"></a><h3><span class="fa fa-fw fa-database"></span> Database</h3>
				<div class="row">
					<div class="col-sm-6">
						<h4>Database layer</h4>
						<p>The database layers have been rewritten to include new functions that make our codebase smaller and other performance improvements and APIs for developers.</p>
					</div>
				</div>
				<a id="others"></a><h3>Other improvements and notes</h3>
				<div class="row">
					<div class="col-sm-6">
						<h4>Packages</h4>
						<p><b>Core</b> version 1.1.4755 has been removed.<br />
						<b>Jewel</b> version 2.0.0.59 has been added.<br /></p>
					</div>
					<div class="col-sm-6">
						<h4>Bug fixes</h4>
						<p>0 bugs have been fixed.</p>
						<h4>Security fixes</h4>
						<p>0 security issues has been fixed.</p>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<p>Luna is developed by the <a href="http://getluna.org/">Luna Group</a>. Copyright 2013-2015. Released under the MIT license.</p>
			</div>
		</div>
	</div>
</div>
<?php include('include/footer.php') ?>
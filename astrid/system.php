<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

define( 'JEWEL_ROOT', '../' );
include('include/header.php');

Astrid::DrawAstridNav( 'system', 'backstage' );

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Luna version information</h3>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th class="col-md-3"></th>
				<th class="col-md-3">Version</th>
				<th class="col-md-3"></th>
				<th class="col-md-3">Version</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Software version</td>
				<td><?php echo Version::LUNA_VERSION ?></td>
				<td>Bootstrap version</td>
				<td>3.3.4</td>
			</tr>
			<tr>
				<td>Jewel version</td>
				<td><?php echo Version::LUNA_JEWEL_VERSION ?></td>
				<td>Font Awesome version</td>
				<td>4.3.0</td>
			</tr>
			<tr>
				<td>Database version</td>
				<td><?php echo Version::LUNA_DB_VERSION ?></td>
				<td>jQuery version</td>
				<td>2.1.4</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Server statistics</h3>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th class="col-md-4">Server load</th>
				<th class="col-md-4">Environment</th>
				<th class="col-md-4">Database</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Not available - 1 user(s) online</td>
				<td>
					<?php printf("Operating system: %s", PHP_OS) ?><br />
					<?php printf("PHP: %s - %s", phpversion(), '<a href="system.php?action=phpinfo">Show info</a>') ?><br />
					Accelerator
				</td>
				<td>
					MySQL Improved 5.6.24
					<br />Rows
					<br />Size
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php include('include/footer.php') ?>
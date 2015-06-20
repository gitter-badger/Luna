<?php
include( 'include/header.php' );

Astrid::DrawAstridNav( 'backstage', 'backstage' );
?>
<div class="row">
	<div class="col-sm-7">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Reports</h3>
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>Reported by</th>
								<th>Time</th>
								<th>Message</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Aero</td>
								<td>21:05 31.03.'15</td>
								<td>This is just not polite in any meaning of the word.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Statistics</h3>
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>Posts</th>
								<th>Topics</th>
								<th>Users</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1.421</td>
								<td>389</td>
								<td>426</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">About Astrid</h3>
					</div>
					<div class="panel-body">
						<a class="btn btn-default btn-block" href="http://getluna.org/astrid.php">About Astrid</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-5">
		<div class="widget admin-widget">
			<div class="widget-heading">
				<h2>Admin notes</h2>
			</div>
			<div class="widget-body">
				<div class="media admin-note">
					<div class="media-left">
						<a href="#">
							<img class="media-object" src="../img/avatars/placeholder.png" alt="..." height="100">
						</a>
					</div>
					<div class="media-body">
						<h4 class="media-heading">Aero<span class="pull-right"><a class="btn btn-success btn-xs" href="#"><span class="fa fa-fw fa-check"></span> Add</a></span></h4>
						<textarea class="form-control" placeholder="Add a note"></textarea>
					</div>
				</div>
				<div class="media admin-note">
					<div class="media-left">
						<a href="#">
							<img class="media-object" src="../img/avatars/placeholder.png" alt="..." height="100">
						</a>
					</div>
					<div class="media-body">
						<h4 class="media-heading">Aero<span class="pull-right"><a class="btn btn-success btn-xs" href="index.php"><span class="fa fa-fw fa-check"></span> Done</a></span></h4>
						<p>We need to update Luna to version 1.1, it's important to stay up-to-date and to keep security tight!</p>
					</div>
				</div>
				<div class="media admin-note">
					<div class="media-left">
						<a href="#">
							<img class="media-object" src="../img/avatars/placeholder.png" alt="..." height="100">
						</a>
					</div>
					<div class="media-body">
						<h4 class="media-heading">Bittersweet Shimmer<span class="pull-right"><a class="btn btn-success btn-xs" href="index.php"><span class="fa fa-fw fa-check"></span> Done</a></span></h4>
						<p>1.1 Beta is out, we should start testing!</p>
					</div>
				</div>
				<div class="media admin-note">
					<div class="media-left">
						<a href="#">
							<img class="media-object" src="../img/avatars/placeholder.png" alt="..." height="100">
						</a>
					</div>
					<div class="media-body">
						<h4 class="media-heading">Aero<span class="pull-right"><a class="btn btn-success btn-xs" href="index.php"><span class="fa fa-fw fa-check"></span> Done</a></span></h4>
						<p>Update the rules, they are out of date.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('include/footer.php') ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Luna / Astrid</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/astrid.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="../"><i class="fa fa-fw fa-arrow-left"></i></a>
				</div>
				<div class="collapse navbar-collapse" id="main-nav">
					<ul class="nav navbar-nav">
						<li><a href="#"><i class="fa fa-fw fa-dashboard"></i> Backstage</a></li>
						<li><a href="#"><i class="fa fa-fw fa-file"></i> Content</a></li>
						<li><a href="#"><i class="fa fa-fw fa-users"></i> Users</a></li>
						<li><a href="#"><i class="fa fa-fw fa-cogs"></i> Settings</a></li>
						<li><a href="#"><i class="fa fa-fw fa-coffee"></i> Maintenance</a></li>
						<li><a href="#"><i class="fa fa-fw fa-plus"></i> Extras</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Astrid <i class="fa fa-fw fa-angle-down"></i></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#">Profile</a></li>
								<li><a href="#">Settings</a></li>
								<li class="divider"></li>
								<li><a href="#">Help</a></li>
								<li><a href="#">Support</a></li>
								<li class="divider"></li>
								<li><a href="#">Log out</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="jumbotron jumboheader">
			<div class="container">
				<div class="row">
					<h2 class="hidden-xs">
						<i class="fa fa-fw fa-dashboard"></i> Welcome back, Astrid!
						<span class="pull-right" style="font-size: 70%;">Core 1.9.4712</span>
					</h2>
					<ul class="nav nav-tabs" role="tablist">
						<li class="active"><a href="index.php"><i class="fa fa-fw fa-tachometer"></i><span class="hidden-xs"> Backstage</span></a></li>
						<li><a href="system.php"><i class="fa fa-fw fa-info-circle"></i><span class="hidden-xs"> System info</span></a></li>
						<li><a href="update.php"><i class="fa fa-fw fa-cloud-upload"></i><span class="hidden-xs"> Update</span></a></li>
						<li class="pull-right"><a href="about.php"><i class="fa fa-fw fa-moon-o"></i><span class="hidden-xs"> About</span></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
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
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
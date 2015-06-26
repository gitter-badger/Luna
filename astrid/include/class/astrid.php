<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

class Astrid {
	public function DrawAstridNav( $page, $section ) {
		// Backstage section
		if ( $page == 'backstage' )
			$page_title = '<i class="fa fa-fw fa-dashboard"></i> Welcome back, Astrid!';
		if ( $page == 'system' )
			$page_title = '<i class="fa fa-fw fa-info-circle"></i> System info';
		if ( $page == 'update' )
			$page_title = '<i class="fa fa-fw fa-cload-upload"></i> Luna software update';
		if ( $page == 'about' )
			$page_title = '<i class="fa fa-fw fa-moon-o"></i> About Luna';
		
		// Settings section
		if ( $page == 'settings' )
			$page_title = '<i class="fa fa-fw fa-cogs"></i>  Essentials';
		
?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand visible-xs-inline" href="../">Backstage</a>
		</div>
		<div class="collapse navbar-collapse" id="main-nav">
			<ul class="nav navbar-nav">
				<li class="<?php if ($section == 'backstage') echo 'active'; ?>"><a href="index.php"><span class="fa fa-fw fa-dashboard"></span> Backstage</a></li>
				<!--<li class="<?php if ($section == 'content') echo 'active'; ?>"><a href="board.php"><span class="fa fa-fw fa-file"></span> Content</a></li>
				<li class="<?php if ($section == 'users') echo 'active'; ?>"><a href="users.php"><span class="fa fa-fw fa-users"></span> Users</a></li>-->
				<li class="<?php if ($section == 'settings') echo 'active'; ?>"><a href="settings.php"><span class="fa fa-fw fa-cog"></span> Settings</a>
				<!--<li class="<?php if ($section == 'maintenance') echo 'active'; ?>"><a href="maintenance.php"><span class="fa fa-fw fa-coffee"></span> Maintenance</a></li>
				<li class="<?php if ($section == 'extras') echo 'active'; ?>"><a href="plugins.php"><i class="fa fa-fw fa-plus"></i> Extras</a></li>-->
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"><i class="fa fa-fw fa-circle-o"></i><span class="visible-xs-inline"> Notifications</span></a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-fw fa-angle-down"></i><span class="visible-xs-inline"> Astrid</span></a>
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
				<?php echo $page_title ?>
				<span class="pull-right" style="font-size: 70%;"> <?php echo Version::LUNA_JEWEL_VERSION ?></span>
			</h2>
			<ul class="nav nav-tabs" role="tablist">
				<?php if ( $section == "backstage" ) { ?>
					<li class="<?php if ($page == 'backstage') echo 'active'; ?>"><a href="index.php"><i class="fa fa-fw fa-tachometer"></i><span class="hidden-xs"> Backstage</span></a></li>
					<li class="<?php if ($page == 'system') echo 'active'; ?>"><a href="system.php"><i class="fa fa-fw fa-info-circle"></i><span class="hidden-xs"> System info</span></a></li>
					<li class="<?php if ($page == 'update') echo 'active'; ?>"><a href="update.php"><i class="fa fa-fw fa-cloud-upload"></i><span class="hidden-xs"> Update</span></a></li>
					<li class="pull-right<?php if ($page == 'about') echo ' active'; ?>"><a href="about.php"><i class="fa fa-fw fa-moon-o"></i><span class="hidden-xs"> About</span></a></li>
				<?php } if ( $section == "settings" ) { ?>
					<li class="<?php if ($page == 'settings') echo 'active'; ?>"><a href="settings.php"><i class="fa fa-fw fa-cogs"></i><span class="hidden-xs"> Essentials</span></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
<?php
	}
}
?>
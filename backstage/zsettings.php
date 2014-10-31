<?php

/*
 * Copyright (C) 2013-2014 Luna
 * Based on code by FluxBB copyright (C) 2008-2012 FluxBB
 * Based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * Licensed under GPLv3 (http://modernbb.be/license.php)
 */

define('FORUM_ROOT', '../');
require FORUM_ROOT.'include/common.php';

if (!$luna_user['is_admmod']) {
    header("Location: ../login.php");
}

if (file_exists('../z.txt'))
	$zset = '1';

if (($luna_user['g_id'] != FORUM_ADMIN) || (!isset($zset)))
	message_backstage($lang['No permission'], false, '403 Forbidden');

if (isset($_POST['form_sent'])) {
	confirm_referrer('backstage/zsettings.php', $lang['Bad HTTP Referer message']);

	$form = array(
		'backstage_dark'		=> isset($_POST['form']['backstage_dark']) ? '1' : '0',
		'notifications'			=> isset($_POST['form']['notifications']) ? '1' : '0',
		'forum_new_style'		=> isset($_POST['form']['forum_new_style']) ? '1' : '0',
		'user_menu_sidebar'		=> isset($_POST['form']['user_menu_sidebar']) ? '1' : '0'
	);

	foreach ($form as $key => $input) {
		// Only update values that have changed
		if (array_key_exists('o_'.$key, $luna_config) && $luna_config['o_'.$key] != $input) {
			if ($input != '' || is_int($input))
				$value = '\''.$db->escape($input).'\'';
			else
				$value = 'NULL';

			$db->query('UPDATE '.$db->prefix.'config SET conf_value='.$value.' WHERE conf_name=\'o_'.$db->escape($key).'\'') or error('Unable to update board config', __FILE__, __LINE__, $db->error());
		}
	}

	// Regenerate the config cache
	if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
		require FORUM_ROOT.'include/cache.php';

	generate_config_cache();
	clear_feed_cache();

	redirect('backstage/zsettings.php?saved=true');
}

$page_title = array(luna_htmlspecialchars($luna_config['o_board_title']), $lang['Admin'], $lang['Options']);
define('FORUM_ACTIVE_PAGE', 'admin');
require 'header.php';
load_admin_nav('settings', 'zsettings');

if (isset($_GET['saved']))
	echo '<div class="alert alert-success"><h4>'.$lang['Settings saved'].'</h4></div>'
?>
<form class="form-horizontal" method="post" action="zsettings.php">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">zSettings<span class="pull-right"><input class="btn btn-primary" type="submit" name="save" value="<?php echo $lang['Save'] ?>" /></span></h3>
        </div>
        <div class="panel-body">
            <input type="hidden" name="form_sent" value="1" />
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-3 control-label">zBackstageDark<span class="help-block">zBackstageDarkHelp</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox">
							<label>
								<input type="checkbox" name="form[backstage_dark]" value="1" <?php if ($luna_config['o_backstage_dark'] == '1') echo ' checked="checked"' ?> />
								enabled dark mode for backstage, including improved design
							</label>
						</div>
					</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">zForumNewStyle<span class="help-block">zForumNewStyleHelp</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox">
							<label>
								<input type="checkbox" name="form[forum_new_style]" value="1" <?php if ($luna_config['o_forum_new_style'] == '1') echo ' checked="checked"' ?> />
								use the new (experimental) index design
							</label>
						</div>
					</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">zHooks<span class="help-block">zHooksHelp</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox">
							<label>
								<input disabled type="checkbox" name="form[user_menu_sidebar]" value="1" <?php if ($luna_config['o_hooks'] == '1') echo ' checked="checked"' ?> />
								enable hooks to be used by plugins
							</label>
						</div>
					</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">zNotifications<span class="help-block">zNotificationsHelp</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox">
							<label>
								<input type="checkbox" name="form[notifications]" value="1" <?php if ($luna_config['o_notifications'] == '1') echo ' checked="checked"' ?> />
								enable notifications throug Luna
							</label>
						</div>
					</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">zUserMenu<span class="help-block">zUserMenuHelp</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox">
							<label>
								<input type="checkbox" name="form[user_menu_sidebar]" value="1" <?php if ($luna_config['o_user_menu_sidebar'] == '1') echo ' checked="checked"' ?> />
								enable the user menu in the index sidebar to replace the navbar user menu
							</label>
						</div>
					</div>
                </div>
            </fieldset>
        </div>
    </div>
</form>
<?php

require 'footer.php';
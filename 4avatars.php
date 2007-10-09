<?php

/*
Plugin Name: 4Avatars
Plugin URI: http://www.b4it.xorg.pl/4avatars/
Description: This plugin allows you to add MyBlogLog.com or Gravatar.com or Avatars.pl avatars to Wordpress comments.
Version: 0.01
Author: <a href="http://www.b4it.xorg.pl/">b4it</a>
*/

function foravatars() 
{
	if (get_option('avatar_site') == 'mybloglog') {
		global $comment;
		$url      = $comment->comment_author_url;
		$email    = $comment->comment_author_email;
		$nickname = $comment->comment_author;
		if($email == "") {
			$url = explode("/",$url);
			$url = "http://" . $url[2];
			$nickname = ""; 
		}
		if($url != ""  &&  $url != "http://")
			$mybloglog_URL = "http://www.mybloglog.com/buzz/co_redir.php?t=&amp;href=" . $url . "&amp;n=". $nickname;
		else
			$mybloglog_URL = "http://www.mybloglog.com/buzz/co_redir.php?t=";

		if($url != ""  &&  $url != "http://")
			$mybloglog_IMG = "http://pub.mybloglog.com/coiserv.php?href=" . $url . "&amp;n=". $nickname;
		else
			$mybloglog_IMG = get_option("avatar_default"); 
 
		echo "<a rel=\"nofollow\" href=\"".$mybloglog_URL."\" target=\"_blank\" title=\"Check my profile on MyBlogLog.com!\">";
		echo "<img class=\"foravatars\" src=\"".$mybloglog_IMG."\" onload=\"if (this.width > 48) { this.width = 48; this.height = 48; } if (this.width < 48) {  this.src='".get_option("avatar_default")."'; this.onload=void(null); }\" alt=\"4Avatars\" />";
		echo "</a>";
	} elseif (get_option('avatar_site') == 'gravatar') {
		global $comment;
		$mail    = $comment->comment_author_email;
		echo "<img class=\"foravatars\" src=\"http://www.gravatar.com/avatar.php?gravatar_id=".md5($mail)."&amp;size=48&amp;default=".urlencode(get_option('avatar_default'))."\" />";
	} elseif (get_option('avatar_site') == 'avatars') {
		global $comment;
		$mail    = $comment->comment_author_email;
		echo "<img class=\"foravatars\" src=\"http://www.avatars.pl/avatar.php?id=".md5($mail)."&amp;size=48&amp;default=".urlencode(get_option('avatar_default'))."\" />";
	}
} 

function foravatars_options()
{
	if (!empty($_POST['avatar_site']))
	{
		// Małe zabezpieczenie
		if (!in_array($_POST['avatar_site'], array('mybloglog', 'gravatar', 'avatars')))
		{
			$_POST['avatar_site'] = 'mybloglog';
		}

		update_option('avatar_site', $_POST['avatar_site']);
	}

	if (!empty($_POST['avatar_default']))
	{
		update_option('avatar_default', $_POST['avatar_default']);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	?>

	<div id="message" class="updated fade"><p><strong>Options saved!</strong></p></div>

	<?php
	}
?>

<div class="wrap">
	<h2>4Avatars Options</h2>
	<p>Paste <code>&lt;?php foravatars(); ?&gt;</code> in comments.php to avatars appeared.</p>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo attribute_escape($_GET['page']); ?>" method="post">

	<?php
	if ( function_exists('wp_nonce_field') ) {
		wp_nonce_field('wp-bulb-update-options');
	}
	?>

		<table width="100%" cellspacing="2" cellpadding="5" class="optiontable editform">
			<tr valign="top">
				<th width="33%" scope="row">Choose site:</th>
				<td>
					<input type="radio" name="avatar_site" value="mybloglog" <?php if (get_option('avatar_site') == 'mybloglog') echo "checked=\"checked\"" ?>> MyBlogLog<br />
					<input type="radio" name="avatar_site" value="gravatar" <?php if (get_option('avatar_site') == 'gravatar') echo "checked=\"checked\"" ?>> Gravatar<br />
					<input type="radio" name="avatar_site" value="avatars" <?php if (get_option('avatar_site') == 'avatars') echo "checked=\"checked\"" ?>> Avatars.pl
				</td>
			</tr>

			<tr valign="top">
				<th width="33%" scope="row">Default avatar:</th>
				<td>
					<input name="avatar_default" type="text" value="<?php echo get_option('avatar_default'); ?>" /><br />
					URL to default avatar!
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="Submit" value="Save &raquo;" />
		</p>
	</form>
</div>

<?php
}

function foravatars_menu()
{
	add_options_page('4Avatars', '4Avatars', 7, 'foravatars', 'foravatars_options');
}

function foravatars_activate()
{
	// Dodajemy opcje
	add_option('avatar_site', 'mybloglog', 'Site with avatars.');
	add_option('avatar_default', '/wp-content/plugins/4avatars/default.gif', 'URL to default avatar.');
}


function foravatars_deactivate()
{
	// Zamiatamy...
	delete_option('avatar_site');
	delete_option('avatar_default');
}

add_action('admin_menu', 'foravatars_menu');
add_action('activate_foravatars', 'foravatars_activate');
add_action('deactivate_foravatars', 'foravatars_deactivate');
?>

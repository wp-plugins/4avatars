<?php

/*
Plugin Name: 4Avatars
Plugin URI: http://www.b4it.xorg.pl/4avatars/
Description: This plugin allows you to add MyBlogLog.com or Gravatar.com or Avatars.pl avatars to Wordpress comments.
Version: 0.2
Author: b4it
Author URI: http://www.b4it.xorg.pl/
*/

function foravatars() 
{
	global $comment;
	$site = get_option('avatar_site');
	$size = get_option('avatar_size');
	$mail = $comment->comment_author_email;
	$url  = $comment->comment_author_url;
	$nickname = $comment->comment_author;
	
	switch ($site) {
		case "mybloglog":
			//MyBlogLog
			if($mail == "") {
				$url = explode("/",$url);
				$url = "http://" . $url[2];
				$nickname = ""; 
			}
			if($url != ""  &&  $url != "http://")
				echo "<a rel=\"nofollow\" href=\"http://www.mybloglog.com/buzz/co_redir.php?t=&amp;href=" . $url . "&amp;n=". $nickname."\" target=\"_blank\" title=\"Check my profile on MyBlogLog.com!\">";
	
			if($url != ""  &&  $url != "http://")
				$mybloglog_IMG = "http://pub.mybloglog.com/coiserv.php?href=" . $url . "&amp;n=". $nickname;
			else
				$mybloglog_IMG = get_option("avatar_default"); 
 
			echo "<img class=\"foravatars\" src=\"".$mybloglog_IMG."\" onload=\"if (this.width > ".$size.") { this.width = ".$size."; this.height = ".$size."; } if (this.width < ".$size.") { this.width = ".$size."; this.src='".get_option("avatar_default")."'; this.onload=void(null); }\" alt=\"4Avatars\" />";
			
			if($url != ""  &&  $url != "http://")
				echo "</a>";
			break;
		case "gravatar":
			//Gravatar
			echo "<img class=\"foravatars\" src=\"http://www.gravatar.com/avatar.php?gravatar_id=".md5($mail)."&amp;size=".$size."&amp;default=".urlencode(get_option('avatar_default'))."\" alt=\"4Avatars\" />";
			break;
		case "avatars":
			//Avatars.pl
			echo "<img class=\"foravatars\" src=\"http://www.avatars.pl/avatar.php?id=".md5($mail)."&amp;size=".$size."&amp;default=".urlencode(get_option('avatar_default'))."\" alt=\"4Avatars\" />";
			break;
	}
} 

function foravatars_options()
{
	$blad = "";
	if (!empty($_POST['avatar_site'])) {
		// Małe zabezpieczenie
		if (!in_array($_POST['avatar_site'], array('mybloglog', 'gravatar', 'avatars'))) {
			$_POST['avatar_site'] = 'mybloglog';
		}

		update_option('avatar_site', $_POST['avatar_site']);
	} else 
		$blad .= "<br />Wybież stronę.";

	if (!empty($_POST['avatar_default'])) {
		update_option('avatar_default', $_POST['avatar_default']);
	} else 
		$blad .= "<br />Podaj adres podstawowego avatara.";

	if (!empty($_POST['avatar_size']) && is_numeric($_POST['avatar_size'])) {
		update_option('avatar_size', $_POST['avatar_size']);
	} else 
		$blad .= "<br />Rozmiar avatara nie jest liczbą.";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($blad != "") {
			echo "<div id=\"message\" class=\"error fade\"><p><strong>Zonk:".$blad."</strong></p></div>";
			$blad = "";
		} else {
			echo "<div id=\"message\" class=\"updated fade\"><p><strong>Options saved!</strong></p></div>";
		}
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
				<th width="33%" scope="row">Avatar width:</th>
				<td>
					<input name="avatar_size" type="text" value="<?php echo get_option('avatar_size'); ?>" /><br />
					In pixels.
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
	add_option("avatar_site", 'avatars', 'Site with avatars.', 'yes');
	$adres = get_option('siteurl')."/wp-content/plugins/4avatars/default.gif";
	add_option("avatar_default", $adres, 'URL to default avatar.', 'yes');
	add_option("avatar_size", '48', 'Width of avatar.', 'yes');
}


function foravatars_deactivate()
{
	// Zamiatamy...
	delete_option('avatar_site');
	delete_option('avatar_default');
	delete_option('avatar_size');
}

add_action('admin_menu', 'foravatars_menu');
add_action('activate_4avatars/4avatars.php', 'foravatars_activate');
add_action('deactivate_4avatars/4avatars.php', 'foravatars_deactivate');
?>

<?php
// +------------------------------------------------------------------------+
// | PHP Melody ( www.phpsugar.com )
// +------------------------------------------------------------------------+
// | PHP Melody IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// | than www.phpsugar.com or if you have received
// | this software from someone who is not a representative of
// | PHPSUGAR, you are involved in an illegal activity.
// | ---
// | In such case, please contact: support@phpsugar.com.
// +------------------------------------------------------------------------+
// | Developed by: PHPSUGAR (www.phpsugar.com) / support@phpsugar.com
// | Copyright: (c) 2004-2013 PHPSUGAR. All rights reserved.
// +------------------------------------------------------------------------+

$showm = '6';
$load_scrolltofixed = 1;
$_page_title = 'Edit user profile';
include('header.php');

$modframework->trigger_hook('admin_edituser_top');
$error = '';
$the_user = array();
$user_id = '';
$errors = array();

$user_id = (int) $_GET['uid'];

$action = (int) $_GET['action'];

if( empty($user_id) || $user_id === '' || !is_numeric($user_id) )
{
	$error = "The User ID in your URL is not valid or missing";
}
else
{
	$the_user = fetch_user_advanced($user_id);
	
	$the_user['comment_count'] = count_entries('pm_comments', 'user_id', $the_user['id']);
	/*
    * Security fix to prevent moderators changing admin passwords (by Trace)
    */
   if($the_user['power'] == U_ADMIN && is_moderator()){
      $error = "You cannot edit this user as he is higher ranked than you.";
      $the_user = null; $user_id = 0; unset($_POST['save']);
   }
}

if($action == 1 && is_array($the_user))	//	activate user account
{
	if($the_user['power'] == U_INACTIVE)
	{
		$sql = "UPDATE pm_users SET power = '".U_ACTIVE."' WHERE id='".$user_id."'";
		$result = @mysql_query($sql);
		if(!$result)
		{
			$info_msg = pm_alert_error('An error occured while updating your database.<br />MySQL returned: '. mysql_error());
		}
		else
		{
			$the_user['power'] = U_ACTIVE;
			
			if ($config['account_activation'] == AA_ADMIN)
			{
				require_once(ABSPATH ."include/class.phpmailer.php");
				
					//*** DEFINING E-MAIL VARS
					$mailsubject = sprintf($lang['mailer_subj7'], _SITENAME);
					
					$array_content[]=array("mail_username", $the_user['username']); 
					$array_content[]=array("mail_sitename", _SITENAME);
					$array_content[]=array("mail_loginurl", _URL.'/login.'. _FEXT);
					$array_content[]=array("mail_url", _URL);
					//*** END DEFINING E-MAIL VARS
				
				if(file_exists(ABSPATH .'email_template/'.$_language_email_dir.'/email_registration_approved.txt'))
				{
					$mail = send_a_mail($array_content, $the_user['email'], $mailsubject, ABSPATH .'email_template/'.$_language_email_dir.'/email_registration_approved.txt');
				}
				elseif(file_exists(ABSPATH .'/email_template/english/email_registration_approved.txt'))
				{
					$mail = send_a_mail($array_content, $the_user['email'], $mailsubject, ABSPATH .'email_template/english/email_registration_approved.txt');
				}
				elseif(file_exists(ABSPATH .'/email_template/email_registration_approved.txt'))
				{
					$mail = send_a_mail($array_content, $the_user['email'], $mailsubject, ABSPATH .'email_template/email_registration_approved.txt');
				}
				else
				{
					@log_error('Error: Email template "email_registration_approved.txt" not found!', 'Register Page', 1);
				}
				
				if($mail !== TRUE)
				{
					@log_error($mail, 'Register Page', 1);
					$info_msg = pm_alert_warning('The account has been activated however, the mail notification could not be sent.<br />Mailer error: '. $mail);
				}
				else
				{
					$info_msg = pm_alert_success('This account is now active. A notification mail has been sent to <em>'. $the_user['email'] .'</em>');
				}
			}
			else
			{
				$info_msg = pm_alert_success('This account is now active.');
			}
		}
	}
	else
	{
		$info_msg = pm_alert_success('This account is already active.');
	}
}
else if ($action == 9 && is_array($the_user))	//	delete all comments posted by this user
{
	if (is_moderator() && mod_cannot('manage_comments'))
	{
		echo '<div id="adminPrimary">';
		restricted_access();
		echo '</div>';
	}
	
	$sql = "DELETE FROM 
			pm_comments 
			WHERE user_id = '". $the_user['id'] ."'";
	
	$result = @mysql_query($sql);
	if ( ! $result)
	{
		$error = 'An error occurred while attempting to delete the user\'s comments.<br />MySQL returned: '.mysql_error();
	}
	else
	{
		@mysql_query("DELETE FROM pm_comments_reported WHERE user_id = '". $the_user['id'] ."'");
		$info_msg = pm_alert_success('All comments posted by this user were deleted.');
		$the_user['comment_count'] = count_entries('pm_comments', 'user_id', $user_id);
	}
}

if( isset($_POST['save']))
{

	$no_errors = 0;
	
	$post_username = trim($_POST['username']);
	$post_username = sanitize_user($post_username);

	if( check_username($post_username) == 3 && $post_username != $the_user['username'] )
	{
		$error = "Username is already in use";
		$no_errors++;
	}

	if( validate_email($_POST['email']) == 2 && $_POST['email'] != $the_user['email'] )
	{
		$error = "Email is already in use";
		$no_errors++;
	}

	if( $_POST['delete_avatar'] == 1 && $the_user['avatar'] != "default.gif" )
	{
		// delete avatar;
		if( unlink(ABSPATH."uploads/avatars/".$the_user['avatar']) === FALSE )
			$error = "Could not delete the user's avatar";
	}
	$modframework->trigger_hook('admin_edituser_validate');
	
	if( $no_errors == 0 )
	{	
		$sql = "UPDATE pm_users SET ";
		
		if( $_POST['new_pass'] != '' )
			$sql .= " password = '".md5($_POST['new_pass'])."', ";
		
		if( $_POST['delete_avatar'] == 1 )
			$sql .= " avatar = 'default.gif', ";
		
		$sql .= " username = '".secure_sql($post_username)."', name = '".secure_sql($_POST['name'])."', ";
		$sql .= " gender = '".$_POST['gender']."', country = '".$_POST['country']."', email = '".$_POST['email']."', ";
		$sql .= " about = '".secure_sql($_POST['aboutme'])."', ";
		$sql .= " website = '". secure_sql($_POST['website']) ."', facebook = '". secure_sql($_POST['facebook']) ."', ";
		$sql .= " twitter = '". secure_sql($_POST['twitter']) ."', lastfm = '". secure_sql($_POST['lastfm']) ."' "; 
	
		if (is_admin() && isset($_POST['user_power']))
		{
			$sql .= ", power = '".$_POST['user_power']."'";
		}
		$modframework->trigger_hook('admin_edituser_sqlinsert');
		
		$sql .= " WHERE id = ".$the_user['id']."";

		$result = @mysql_query($sql);
		if( !$result )
			$error = "There was a problem while updating this user. <br /> Mysql returned this error : ".mysql_error();
		$modframework->trigger_hook('admin_edituser_done');
			
		// Was the username changed? Update the pm_comments table with the new username too;
		if ($post_username != $the_user['username'])
		{
			$all_ids = '';

			// update pm_comments
			$sql = "SELECT id FROM pm_comments WHERE username = '".$the_user['username']."' AND user_id='".$the_user['id']."'";
			$result = mysql_query($sql);
			$total = mysql_num_rows($result);
			
			if($total > 0)
			{
				while($row = mysql_fetch_assoc($result))
				{
					$all_ids .= $row['id'] . ", ";
				}
				$all_ids = substr($all_ids, 0, -2);
				
				mysql_free_result($result);
				
				$sql = "UPDATE pm_comments SET username = '". secure_sql($post_username) ."' WHERE id IN(".$all_ids.")";
				$result = @mysql_query($sql);
			}
			
			unset($all_ids, $total, $result);
			$all_ids = '';
			
			// update pm_videos			
			$sql = "SELECT id FROM pm_videos WHERE submitted = '".$the_user['username']."'";
			$result = mysql_query($sql);
			$total = mysql_num_rows($result);
			
			if($total > 0)
			{
				while($row = mysql_fetch_assoc($result))
				{
					$all_ids .= $row['id'] . ", ";
				}
				
				$all_ids = substr($all_ids, 0, -2);
				
				mysql_free_result($result);
				
				$sql = "UPDATE pm_videos SET submitted = '". secure_sql($post_username) ."' WHERE id IN(".$all_ids.")";
				$result = @mysql_query($sql);
			}
		}
	}
}

load_countries_list();

?>
<div id="adminPrimary">
    <div class="content">
<h2>Edit User Profile: <a href="<?php echo _URL.'/profile.php?u='. $the_user['username'];?>" title="View public profile" target="_blank"><?php echo ucfirst($the_user['username']); ?></a></h2>

<?php echo $info_msg; ?>
	<?php

		if( !isset($_POST['save']) && $error != '' )
		{
			echo pm_alert_error($error);
		}
		else 
		{
			if( isset($_POST['save']) && $no_errors > 0 )
			{
				echo pm_alert_error($error);
			}
			else if ( isset($_POST['save']) && $no_errors == 0 )
			{
				echo pm_alert_success('The account was updated successfully.');
				echo '<a href="members.php" class="btn">&larr; Users</a>';
			}
			else
			{
	
	// check if banned.
	$sql = "SELECT COUNT(*) AS total, reason FROM pm_banlist WHERE user_id = '". $the_user['id'] ."'";
	if ($result = @mysql_query($sql))
	{
		$ban = mysql_fetch_assoc($result);
		mysql_free_result($result);
	}
	
	?>
	
	<form name="edit_profile_form" method="POST" action="<?php echo "edit_user_profile.php?uid=".$user_id; ?>" class="form-horizontal">
<!--	<table width="60%" border="0" cellspacing="1" cellpadding="3" align="center" style="text-align:center">-->
<?php
if ($ban['total'] > 0)
{
	$banlist_nonce = csrfguard_raw('_admin_banlist');
	?>
	<div class="alert alert-error">
		This account is banned.
		<?php if ($ban['reason'] != '') : ?>
		<strong>Reason:</strong> <?php echo $ban['reason'];?>
		<?php endif; ?>
        <strong><a href="banlist.php?a=delete&uid=<?php echo $the_user['id'];?>&_pmnonce=<?php echo $banlist_nonce['_pmnonce'];?>&_pmnonce_t=<?php echo $banlist_nonce['_pmnonce_t'];?>">Remove ban</a></strong>
	</div>
	<?php
}
if ($the_user['power'] == U_INACTIVE && $action != 1)
{
	$members_nonce = csrfguard_raw('_admin_members');
	?>
	<div class="alert alert-info">
		This account has not been activated.  
        <strong><a href="edit_user_profile.php?uid=<?php echo $the_user['id'];?>&action=1&_pmnonce=<?php echo $members_nonce['_pmnonce'];?>&_pmnonce_t=<?php echo $members_nonce['_pmnonce_t'];?>" title="Activate account">Activate now</a></strong>
	</div>
	<?php
}
?>
<style>
label input {
  line-height: 1em;
  padding: 0;
  margin: 0;
  margin-left: 4px;
  line-height: 0;
  top: -3px;
  position: relative;
  font-weight: normal;
}
</style>

<div class="row-fluid">
<div class="span8">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered pm-tables pm-tables-settings">
  <tr>
    <td width="15%">Avatar</td>
    <td width="85%">
      <span class="avatar_border"><img src="<?php echo _URL."/uploads/avatars/".$the_user['avatar']; ?>" border="0" alt="" class="img-polaroid" /></span>
      <?php if ($the_user['avatar'] != '' && $the_user['avatar'] != 'default.gif') : ?>
	  <label><input type="checkbox" class="checkbox" name="delete_avatar" value="1" /> Delete this avatar?</label>
	  <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>Name</td>
    <td><input name="name" type="text" value="<?php echo $the_user['name']; ?>" /></td>
  </tr>
  <tr>
    <td>Username</td>
    <td><input type="text" name="username" value="<?php echo $the_user['username']; ?>" /></td>
  </tr>
  <tr>
    <td>New password</td>
    <td>
      <input name="new_pass" type="password" maxlength="32" />
      <div class="help-block"><small>Leave blank if you don't want to change the password</small></div>    
    </td>
  </tr>
  <tr>
    <td>Email</td>
    <td><input type="text" name="email" value="<?php echo $the_user['email']; ?>" /></td>
  </tr>
  <tr>
    <td>User group</td>
    <td>
      <select name="user_power">
        <?php
      
      if( $the_user['power'] == U_INACTIVE)
      {
          ?>
        <option value="<?php echo U_INACTIVE; ?>" <?php if($the_user['power'] == U_INACTIVE) echo 'selected="selected"';?>>Inactive User</option>';
          <?php
      }
      
      if (is_admin())
      {
          ?>
        
        <option value="<?php echo U_ACTIVE;?>"  <?php if($the_user['power'] == U_ACTIVE) echo 'selected="selected"';?> >Regular User</option>
        <option value="<?php echo U_EDITOR;?>"  <?php if($the_user['power'] == U_EDITOR) echo 'selected="selected"';?> >Editor</option>
        <option value="<?php echo U_MODERATOR;?>"  <?php if($the_user['power'] == U_MODERATOR) echo 'selected="selected"';?> >Moderator</option>
        <option value="<?php echo U_ADMIN;?>"  <?php if($the_user['power'] == U_ADMIN) echo 'selected="selected"';?> >Administrator</option>
        
        <?php
      }
      else 
      {
          ?>
        
        <option value="<?php echo $the_user['power'];?>"  selected="selected">
          <?php
              switch ($the_user['power'])
              {
                  default:
                  case U_ACTIVE: 		echo 'Regular User';	break;
                  case U_EDITOR: 		echo 'Editor'; 			break;
                  case U_MODERATOR:	echo 'Moderator'; 		break;
                  case U_ADMIN:		echo 'Administrator';	break;
              } 
              ?>
          </option>
        
        <?php
      }
      ?>
        </select>
    </td>
  </tr>
  <tr>
    <td>Gender</td>
    <td>
      <label><input name="gender" type="radio" value="male" <?php if($the_user['gender'] == "male") echo "checked"; ?> /> Male</label>
      <label><input name="gender" type="radio" value="female" <?php if($the_user['gender'] == "female") echo "checked"; ?> /> Female</label>
    </td>
  </tr>
  <tr>
    <td>Country</td>
    <td>
      <select name="country" size="1" >
        <option value="-1">Select one</option>
        <?php
                $opt = '';
                foreach($_countries_list as $k => $v)
                {
                    $opt = "<option value=\"".$k."\"";
                    if( $the_user['country'] == $k )
                        $opt .= " selected ";
                    $opt .= ">".$v."</option>";
                    echo $opt;
                }
                ?>
        </select>
    </td>
  </tr>
  <tr>
    <td>About</td>
    <td>
      <textarea name="aboutme" rows="4"><?php echo $the_user['about']; ?></textarea>
    </td>
  </tr>
  <tr>
    <td>Site URL</td>
    <td>
      <input type="text" name="website" size="45" value="<?php echo $the_user['website']; ?>" />
    </td>
  </tr>
  <tr>
    <td>Facebook URL</td>
    <td>
      <input type="text" name="facebook" size="45" value="<?php echo $the_user['facebook']; ?>" />
    </td>
  </tr>
  <tr>
    <td>Twitter URL</td>
    <td>
      <input type="text" name="twitter" size="45" value="<?php echo $the_user['twitter']; ?>" />
    </td>
  </tr>
  <tr>
    <td>Last.fm URL</td>
    <td>
      <input type="text" name="lastfm" size="45" value="<?php echo $the_user['lastfm']; ?>" />
    </td>
  </tr>
    <?php 
  		$modframework->trigger_hook('admin_edituser_fieldsinject');
  	?>
  </table>
    <div class="clearfix"></div>

    <div id="stack-controls" class="list-controls">
    <div class="btn-toolbar">
        <div class="btn-group">
    	<button type="submit" name="save" value="Save" class="btn btn-small btn-success btn-strong">Save</button>
	    </div>
    </div>
    </div><!-- #list-controls -->
</div>
<div class="span4">  
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered pm-tables pm-tables-settings">
      <tr>
        <td>Comments</td>
        <td>
        	<?php echo $the_user['comment_count'];?> comment<?php echo ($the_user['comment_count'] == 1) ? '' : 's';?> 
            <?php if ($the_user['comment_count'] > 0) : ?>
				<a href="comments.php?keywords=<?php echo urlencode($the_user['username']);?>&search_type=username&submit=Search" class="btn btn-small">Read all</a>
	            <?php if (is_admin() || (is_moderator() && mod_can('manage_comments'))) : ?>
	            <a href="edit_user_profile.php?action=9&uid=<?php echo $user_id;?>" onclick="return confirm_delete_all();" class="btn btn-small">Delete all</a>
	            <?php endif; ?>
			<?php endif; ?>
        </td>
      </tr>
      <tr>
        <td>Registration date</td>
        <td><?php echo date('l, F j, Y g:i A', (int) $the_user['reg_date']);?></td>
      </tr>
	  <tr>
        <td>Registration IP</td>
        <td><?php echo '<a href="'. _URL .'/'. _ADMIN_FOLDER .'/members.php?keywords='. $the_user['reg_ip'] .'&search_type=ip&submit=Search" title="Search users by this IP">'. $the_user['reg_ip'] .'</a>';?></td>
      </tr>
      <tr>
        <td>Last seen</td>
        <td><?php echo date('l, F j, Y g:i A', (int) $the_user['last_signin']);?></td>
      </tr>
	  <tr>
        <td>Last logged IP</td>
        <td><?php echo ($the_user['last_signin_ip'] != '') ? '<a href="'. _URL .'/'. _ADMIN_FOLDER .'/members.php?keywords='. $the_user['last_signin_ip'] .'&search_type=ip&submit=Search" title="Search users by this IP">'. $the_user['last_signin_ip'] .'</a>' : 'No IP yet';?></td>
      </tr>
      <tr>
        <td>Followers</td>
        <td><?php echo $the_user['followers_count'];?></td>
      </tr>
      <tr>
        <td>Following</td>
        <td><?php echo $the_user['following_count'];?></td>
      </tr>
    </table>
</div>
</div><!-- .row-fluid -->
</form>


	<?php
			}
	 	} // end else
	?>
    </div><!-- .content -->
</div><!-- .primary -->
<?php
include('footer.php');
?>
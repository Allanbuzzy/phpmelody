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
$_page_title = 'Add new user';
include('header.php');

$inputs = array();

load_countries_list();

$errors = array();
if ($_POST['Submit'] != '')
{
	$required_fields = array('email' => 'Email',
							 'username' => 'Username', 
							 'pass' => 'Password', 
							 'confirm_pass' => 'Re-type password', 
							 'name' => 'Name'
							 );
	foreach ($_POST as $key => $value) 
	{
		$value = trim($value);
		if(array_key_exists(strtolower($key), $required_fields) && empty($value) )
		{
			$errors[$key] = '<em>'. $required_fields[$key]. '</em> is a required field.';
		}
	}
	
	if ($_POST['country'] == '-1' || $_POST['country'] == '')
	{
		$errors['country'] = 'Please select a country.';
	}
	
	foreach($_POST as $key => $val)
	{
		$val = trim($val);
		$val = specialchars($val, 1);
		$inputs[$key] = $val;
	}
	
	// password, email & username validation
	if (count($errors) == 0)
	{
		$email = trim($_POST['email']);
		$username =	trim($_POST['username']);
		$username = sanitize_user($username, 0); // Since v2.0
		$pass =	$_POST['pass'];
		$conf_pass = $_POST['confirm_pass'];
		
		if (strcmp($pass, $conf_pass) != 0) 
		{ 
			$errors['pass'] = 'Password and Confirmation Password don\'t match';
		}
		
		if ($var = validate_email($email)) 
		{
			if ($var == 1) 
			{
				$errors['email'] = 'Email address is not valid';
			}
			
			if ($var == 2)
			{
				$sql = "SELECT username FROM pm_users WHERE email LIKE '". str_replace("\'", "''", $email) ."'";
				$result = mysql_query($sql);
				$u = mysql_fetch_assoc($result);
				mysql_free_result($result);
				
				$errors['email'] = 'This email address is already in use by <a href="'. _URL."/profile."._FEXT.'?u='.$u['username'] .'"  target="_blank">'. $u['username'] .'</a>.';
			}
		}
		
		if ($var = check_username($username)) 
		{ 
			if ($var == 1)
			{
				$errors['username'] = 'Username should be at least 4 characters long.';
			}
			
			if ($var == 2)
			{
				$errors['username'] = 'Username contains invalid characters. It should contain letters and numbers only. You can enable "non-latin" usernames from <strong>Settings</strong> > <strong>User Settings</strong>.';
			}
			
			if ($var == 3)
			{
				$errors['username'] = 'This Username is already taken. View <a href="'. _URL."/profile."._FEXT.'?u='.$username .'" target="_blank">profile</a>.';
			}
		}
	}
	
	if (count($errors) == 0)
	{
		$aboutme = removeEvilTags($_POST['aboutme']);
		$aboutme = word_wrap_pass($aboutme);
		$aboutme = secure_sql($aboutme);
		$aboutme = specialchars($aboutme, 1);
		$aboutme = str_replace('\n', "<br />", $aboutme);
		
		$sql = "INSERT INTO pm_users (username, password, email, name, gender, country, reg_date, last_signin, reg_ip, power, about, website, facebook, twitter)
				VALUES ('". secure_sql($username) ."', 
						'". md5($pass) ."', 
						'". $email ."', 
						'". secure_sql( trim($_POST['name']) ) ."', 
						'". secure_sql($_POST['gender']) ."', 
						'". secure_sql($_POST['country']) ."', 
						'". time() ."', 
						'". time() ."', 
						'127.0.0.1', 
						'". secure_sql($_POST['power']) ."',
						'". $aboutme ."',
						'". secure_sql($_POST['website']) ."',
						'". secure_sql($_POST['facebook']) ."',
						'". secure_sql($_POST['twitter']) ."')";
		if ( ! $result = mysql_query($sql))
		{
			$errors[] = 'An MySQL error occurred while adding this user: '. mysql_error(); 
		}
		else
		{
			$user_id = mysql_insert_id();
			$success = 'User account created. <a href="'. _URL .'/'. _ADMIN_FOLDER .'/edit_user_profile.php?uid='. $user_id .'">Edit</a> or <a href="'. _URL."/profile."._FEXT.'?u='.$username .'">view profile</a>.'; 
		}
	}
	else
	{
		
	}
}
?>
<div id="adminPrimary">
    <div class="content">
<h2>Add New User</h2>

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
	
	<?php if ($success != '') : ?>
		<?php echo pm_alert_success($success);?>
		<hr />
		<a href="members.php" class="btn">&larr; Users</a> 
		<a href="add_user.php" class="btn">Add another user</a>
	
	<?php else: ?>
	
		<?php 
		if (count($errors) > 0)
		{
			echo pm_alert_error($errors);
		}
		?>
	<form name="edit_profile_form" method="POST" action="add_user.php" class="form-horizontal" onsubmit="return validateFormOnSubmit(this, 'Please fill in the required fields (highlighted)')">	

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered pm-tables pm-tables-settings">
  <tr>
    <td width="15%">Name</td>
    <td width="85%"><input name="name" type="text" id="must" value="<?php echo $inputs['name']; ?>" /></td>
    </tr>
  <tr>
    <td>Username</td>
    <td><input type="text" name="username" id="must" value="<?php echo $inputs['username']; ?>" /></td>
    </tr>
  <tr>
    <td>Password</td>
    <td><input name="pass" type="password" id="must" value="<?php echo $inputs['pass'];?>" maxlength="32" /></td>
    </tr>
  <tr>
    <td>Re-type Password</td>
    <td><input name="confirm_pass" type="password" id="must" value="<?php echo $inputs['confirm_pass'];?>" maxlength="32" /></td>
    </tr>
  <tr>
    <td>Email</td>
    <td><input type="text" name="email" id="must" value="<?php echo $inputs['email']; ?>" /></td>
    </tr>
  <tr>
    <td>User Group</td>
    <td>
    <select name="power">
		<option value="<?php echo U_ACTIVE;?>"  <?php if($inputs['power'] == U_ACTIVE) echo 'selected="selected"';?> >Regular User</option>
	<?php if(is_admin()) : ?>
		<option value="<?php echo U_EDITOR;?>"  <?php if($inputs['power'] == U_EDITOR) echo 'selected="selected"';?> >Editor</option>
		<option value="<?php echo U_MODERATOR;?>"  <?php if($inputs['power'] == U_MODERATOR) echo 'selected="selected"';?> >Moderator</option>
		<option value="<?php echo U_ADMIN;?>"  <?php if($inputs['power'] == U_ADMIN) echo 'selected="selected"';?> >Administrator</option>
	<?php endif; ?>
    </select>
    </td>
    </tr>
  <tr>
    <td>Gender</td>
    <td>
        <label><input name="gender" type="radio" value="male" <?php if($inputs['gender'] == "male" || $inputs['gender'] == '') echo "checked"; ?> /> Male</label>
        <label><input name="gender" type="radio" value="female" <?php if($inputs['gender'] == "female") echo "checked"; ?> /> Female</label>
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
                    if( $inputs['country'] == $k )
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
      <textarea name="aboutme" rows="4"><?php echo $inputs['aboutme']; ?></textarea>
    </td>
    </tr>
  <tr>
    <td>Site URL</td>
    <td>
      <input type="text" name="website" size="45" value="<?php echo $inputs['website']; ?>" />
    </td>
    </tr>
  <tr>
    <td>FaceBook URL</td>
    <td>
      <input type="text" name="facebook" size="45" value="<?php echo $inputs['facebook']; ?>" />
    </td>
    </tr>
  <tr>
    <td>Twitter URL</td>
    <td>
      <input type="text" name="twitter" size="45" value="<?php echo $inputs['twitter']; ?>" />
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
	    <button type="submit" name="Submit" value="Submit" class="btn btn-small btn-success btn-strong">Add user</button>
		</div>
    </div>
    </div><!-- #list-controls -->
	<?php endif; // form  ?>
</div><!-- .row-fluid -->
</form>

    </div><!-- .content -->
</div><!-- .primary -->
<?php
include('footer.php');
?>
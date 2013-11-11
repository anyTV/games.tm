<?php
session_start();
$email = $_POST['email'];
$password = $_POST['password'];
$game = $_POST['game'];
$user = array();
$user['email'] = $email;
$user['password'] = $password;

?>

<html>
<body onload="document.form1.submit();">

<h3>Please wait while we log you in</h3>

<form method="get" name="form1" action='/php/Controller.php?type=User&action=signIn&redirect=<?=$game?>&user=<?php echo json_encode($user); ?>'>

<input name="type" value="User" id="UserEmail" type="hidden">
<input name="action" value="signIn" id="UserEmail" type="hidden">
<input name="email" value="<?=$email;?>" id="UserEmail" type="hidden">
<input name="password" value="<?=$password;?>" id="UserPassword" type="hidden">
<input name="redirect" value="<?=$game;?>" id="UserPassword" type="hidden">
<input name="user" value='<?php echo json_encode($user); ?>' id="UserPassword" type="hidden">
<!--  -->
</form>

</body>
</html>
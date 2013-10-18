<?php
session_start();
$email = $_SESSION['user']['email'];
$password = $_SESSION['user']['password'];
?>

<html>
<body onload="document.form1.submit();">

<h3>Please wait while we log you in</h3>

<form method="post" name="form1" action="http://www.dashboard.tm/">

<input name="data[User][email]" value="<?=$email;?>" id="UserEmail" type="hidden">
<input name="data[User][password]" value="<?=$password;?>" id="UserPassword" type="hidden">

</form>

</body>
</html>
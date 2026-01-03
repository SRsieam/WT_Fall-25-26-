<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="../css/login.css">
</head>

<body>

<div class="form-box">
<h2>Login</h2>

<?php if (!empty($msg)) { ?>
<p class="msg"><?php echo $msg; ?></p>
<?php } ?>

<form method="post" action="../php/loginController.php" autocomplete="off">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

</div>

</body>
</html>

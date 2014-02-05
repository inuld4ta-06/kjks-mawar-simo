<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>KJKS MAWAR SIMO</title>
  <link rel="stylesheet" href="css/login/style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
    <!-- errors & messages --->
    <?php

    // show negative messages
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;    
        }
    }

    // show positive messages
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }

    ?>
    <!-- errors & messages --->

  <form method="post" action="index.php" class="login" name="loginform">
    <p>
      <label for="login">Username:</label>
      <input type="text" name="user_name" id="login_input_username" required >
    </p>

    <p>
      <label for="password">Password:</label>
      <input type="password" name="user_password" id="login_input_password" required >
    </p>

    <p class="login-submit">
        <button type="submit" class="login-button" name="login" value="Log in">Login</button>
    </p>

    <!--<p class="forgot-password"><a href="index.html">Forgot your password?</a></p>-->
  </form>
<!-- login form box -->
<!--
<form method="post" action="index.php" name="loginform">

    <label for="login_input_username">Username</label>
    <input id="login_input_username" class="login_input" type="text" name="user_name" required />

    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required />

    <input type="submit"  name="login" value="Log in" />

</form>
-->
<!--
  <section class="about">
    <p class="about-links">
      <a href="http://www.cssflow.com/snippets/dark-login-form" target="_parent">View Article</a>
      <a href="http://www.cssflow.com/snippets/dark-login-form.zip" target="_parent">Download</a>
    </p>
    <p class="about-author">
      &copy; 2012&ndash;2013 <a href="http://thibaut.me" target="_blank">Thibaut Courouble</a> -
      <a href="http://www.cssflow.com/mit-license" target="_blank">MIT License</a><br>
      Original PSD by <a href="http://365psd.com/day/2-234/" target="_blank">Rich McNabb</a>
  </section>
-->
</body>
</html>


<!--<a href="register.php">Register new account</a>-->
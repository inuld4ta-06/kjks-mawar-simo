<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	<!-- Force Latest IE rendering engine -->
        <title>KJKS MAWAR SIMO</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 

        <!-- Stylesheets -->
        <link rel="stylesheet" href="css/login/base.css">
        <link rel="stylesheet" href="css/login/skeleton.css">
        <link rel="stylesheet" href="css/login/layout.css">

    </head>
    <body>
        <div class="container">
            <div class="form-bg">
                <form method="post" action="index.php" name="loginform">
                    <h2>KJKS Mawar Simo - Login</h2>
                    <p><input type="text" placeholder="Username" name="user_name" required autofocus="" autocomplete="off"/></p>
                    <p><input type="password" placeholder="Password" name="user_password" required /></p>
                    <label for="remember"></label>
                    <span style="color: red;">
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
                    </span>
                    <button type="submit" name="login"></button>
                </form>
            </div>
        </div>
    </body>
</html>

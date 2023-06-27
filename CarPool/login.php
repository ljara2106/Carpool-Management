<?php
include("validate-captcha.php");
?> 


<!DOCTYPE html>
<html>

<style>
@media only screen and (max-width: 100px) {
    .g-recaptcha {
    transform:scale(0.77);
    transform-origin:0 0;
}
}
</style>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <center>

    <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" ></a>

    <br>

    <h1>Login</h1>

    <br>

    <?php if ($is_invalid): ?>
        <em><p style="color:tomato;">Invalid login, check credentials.</em></p>
    <?php endif; ?>


    <br>
    <br>


    <form action="login.php" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" size="30"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <br>

        <div class="g-recaptcha" data-sitekey="6LdtiwwUAAAAAHKlRpozGAjMEOQLt55sAVNaI12S"></div>
        <div>
        <p style="color:tomato;"> <?php echo $error_message; ?></p>
        </div>

        <br>
        <button>Log in</button>
    </form>
    </center>
</body>


<footer>
    <p><?php include "includes/footer.php";?></p>
</footer>

</html>


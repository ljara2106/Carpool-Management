<?php
include("validate-captcha.php");
?> 


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
 


</head>
<body>
    <center>

    <a href="index.php"><img src="/CarPool/img/txlogo.png" alt="Thanksgiving Elementary" ></a>
<br>
    <h1>Login</h1>
    <br>
    <?php if ($is_invalid): ?>
        <em><p style="color:tomato;">Invalid login, check credentials.</em></p>
    <?php endif; ?>


    <br>
    <br>


    <form action="login.php" method="post">
        <label for="email">email</label>
        <input type="email" name="email" id="email" size="30"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <br>

        <div class="g-recaptcha" data-sitekey="6LdtiwwUAAAAAHKlRpozGAjMEOQLt55sAVNaI12S"></div>
        <div>
            <?php echo $error_message; ?>
        </div>

        <br>
        <button>Log in</button>
    </form>
    </center>
</body>
</html>









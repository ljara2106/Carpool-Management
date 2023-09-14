<?php
include("validate-captcha.php");
?>


<!DOCTYPE html>
<html>

<style>
    @media only screen and (max-width: 100px) {
        .g-recaptcha {
            transform: scale(0.77);
            transform-origin: 0 0;
        }
    }

    .WarningMsg {
        font-size: 10px;
        /* example size, can be any size, in px, em, rem, % */
        margin: 20px, 10%, 0px, 10%;
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
    <div class="WarningMsg">
        <p>
            Warning!
            <br>
        <p>
            By continuing to use this system, you are representing yourself as an authorized user. Any activity on this system may be monitored and accessed by Thanksgiving Elementary School or other authorized officials at any time.
            This includes any data created or stored using this system. All such data is subject to the Data Practices Act.
        </p>
        <br>
        Use of this system without appropriate authority, or in excess of authority, may result in:
        <br>
        <br>
        * Disciplinary action and/or, Criminal sanctions and/or other appropriate action and/or,</br>
        * Civil and criminal penalties pursuant to Title 26 Sections 7213, 7213A and 7431 of the United States Code.
        <br>
        <br>

        Disclosure of this data or use of this data for a non-business purpose may violate the Data Practices Act and could result in an investigation and civil and criminal penalties.

        Any identified evidence of possible criminal activity will be provided to appropriate law enforcement agencies.
        </p>
    </div>
    <br>
    <br>

    <center>

        <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary"></a>

        <br>

        <h1>Login</h1>

        <br>

        <?php if ($is_invalid) : ?>
            <em>
                <p style="color:tomato;">Invalid login, check credentials.
            </em></p>
        <?php endif; ?>


        <br>
        <br>


        <form action="login.php" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" size="30" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

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
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>
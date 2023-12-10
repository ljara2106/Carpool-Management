<?php
include("validate-captcha.php");
?>
<!DOCTYPE html>
<html>

<style>
    body {
        background-color: #18202A;
        color: #FFFFFF; /* Set text color to white for better visibility on the dark background */
    }

    @media only screen and (max-width: 100px) {
        .g-recaptcha {
            transform: scale(0.77);
            transform-origin: 0 0;
        }
    }

    .WarningMsg {
        font-size: 10px;
        margin: 20px 10% 0 10%;
    }

    /* Add the following CSS for the login form container */
    form {
        background-color: #2C3E50; /* Set the background color for the form */
        padding: 20px; /* Add some padding to the form container */
        border-radius: 10px; /* Add border-radius for rounded corners */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Add box-shadow for a pop-out effect */
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    input {
        width: calc(100% - 16px);
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    @media only screen and (max-width: 600px) {
        input {
            width: 100%;
        }
    }

    @media only screen and (min-width: 601px) {
        input {
            width: 50%;
        }
    }

    button {
        background-color: #3498DB;
        color: #FFFFFF;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js"></script>
</head>

<body>
<noscript>
        <p style="text-align: center;">Please enable JavaScript in your browser before using this website.</p>
    </noscript>
    <div class="WarningMsg">
        <p>
            Warning!
            <br>
        <p>
            By continuing to use this system, you are representing yourself as an authorized user. Any activity on this system may be monitored and accessed by Thanksgiving Elementary School or other authorized officials at any time.
            This includes any data created or stored using this system. All such data is subject to the Data Practices Act.
        </p>
        Use of this system without appropriate authority, or in excess of authority, may result in:
        <br>
        <br>
        * Disciplinary action and/or,</br> 
        * Criminal sanctions and/or other appropriate action and/or,</br>
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
        <!--<h1>Login</h1>-->
        <br>
        <?php if ($is_invalid) : ?>
            <em>
                <p style="color:tomato;">Invalid login, check credentials.</p>
            </em>
        <?php endif; ?>
        <br>
        <br>
        <form action="login.php" method="post">
            <!-- Your form fields... -->
            <h1>Login</h1>
            <br>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" size="30" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <br>
            <div class="cf-turnstile" data-sitekey="0x4AAAAAAAK1eyBQ3lpTEMIr"></div>
            <div>
                <p style="color:tomato;"> <?php echo $error_message; ?></p>
            </div>
            <br>
            <button>Log in</button>
        </form>
    </center>

    <footer>
        <p><?php include "includes/footer.php"; ?></p>
    </footer>
</body>

</html>

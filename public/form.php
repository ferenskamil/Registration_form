<?php

session_start();

// previously entered values
$enteredLogin = $_SESSION['entered_login'] ?? '';
$enteredEmail = $_SESSION['entered_email'] ?? '';
$enteredPassword = $_SESSION['entered_password'] ?? '';
// $enteredRegulationsCheckbox = $_SESSION['entered_regulations_checkbox'] === 'on' ? 'checked' : '';
$enteredRegulationsCheckbox = isset($_SESSION['entered_regulations_checkbox']) ? 'checked' : '';

// errors
$loginError = $_SESSION['login_err'] ?? '';
$emailError = $_SESSION['email_err'] ?? '';
$passwordError = $_SESSION['pass_err'] ?? '';
$password2Error = $_SESSION['pass2_err'] ?? '';
$avatarError = $_SESSION['avatar_err'] ?? '';
$regulationsError = $_SESSION['regulations_err'] ?? '';
$recaptchaError = $_SESSION['recaptcha_error'] ?? '';
$registrationError = $_SESSION['registration_error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
                .error {
                        margin-top: 0;
                        font-size: 0.8rem;
                        color: red;
                }

                .submit-btn {
                        cursor: pointer;
                }
        </style>

        <!-- title -->
        <title>Registration form</title>
</head>

<body>
        <form action="../src/register.php" method="post" enctype="multipart/form-data" id="registration-form">
                <label for="login">Login</label><br>
                <input type="text" name="login" value="<?= $enteredLogin ?>"><br>
                <p class="error"><?= $loginError ?></p>
                <label for="email">Email</label><br>
                <input type="text" name="email" value="<?= $enteredEmail ?>"><br>
                <p class="error"><?= $emailError ?></p>
                <label for="password">Password</label><br>
                <input type="password" name="password" value="<?= $enteredPassword ?>"><br>
                <p class="error"><?= $passwordError ?></p>
                <label for="password2">Repeat password</label><br>
                <input type="password" name="password2"><br>
                <p class="error"><?= $password2Error ?></p>
                <label for="avatar">Do you want to upload your own custom avatar?</label><br>
                <input type="file" name="avatar"><br>
                <p class="error"><?= $avatarError ?></p>
                <label for="regulations">I agree regulations</label><br>
                <input type="checkbox" name="regulations" <?= $enteredRegulationsCheckbox ?>><br>
                <p class="error"><?= $regulationsError ?></p>
                <br>
                <input type="submit" value="Register" class="submit-btn g-recaptcha"
                        data-sitekey="6LcMCiEoAAAAAF9JaaqTiNqEDQwU1BMK7WFtZoxj"
                        data-callback='onSubmit'
                        data-action='submit'>
                <p class="error"><?= $recaptchaError ?></p>
                <p class="error"><?= $registrationError ?></p>
        </form>

        <!-- Script for reCAPTCHAv3 -->
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
                function onSubmit(token) {
                        document.getElementById("registration-form").submit();
                }
        </script>
</body>

</html>
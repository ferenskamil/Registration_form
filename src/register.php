<?php

session_start();
session_unset();

error_reporting(E_ALL);

/** Imports */
 require '../vendor/autoload.php';

 // Config
 use App\Classes\Config;

 // Validation
 use Symfony\Component\Validator\Constraints\Length;
 use Symfony\Component\Validator\Constraints\NotBlank;
 use Symfony\Component\Validator\Constraints\Email;
 use Symfony\Component\Validator\Constraints\Regex;
 use Symfony\Component\Validator\Constraints\IdenticalTo;
 use Symfony\Component\Validator\Constraints\Image;
 use Symfony\Component\Validator\Constraints\NotNull;
 use Symfony\Component\Validator\Validation;

 // Http-foundation
 use Symfony\Component\HttpFoundation\Request;

 // reCaptcha
 use ReCaptcha\ReCaptcha;

 // Mailer
 use App\Classes\WelcomeMail;
 use Symfony\Component\Mailer\Transport;
 use Symfony\Component\Mailer\Mailer;
 use Symfony\Component\Mime\Email as EmailMessage;

 // Registration in DB
 use App\Classes\User;

 // Helpers
 use function App\Helpers\createUniqueFilename;

/** Validation */
 if ($_POST) {
        /** Get data from post array with Http-foundation library*/
         $request = Request::createFromGlobals();

         // Assign data from POST array to varaiables
         $login = $_SESSION['entered_login'] = $request->request->get('login');
         $email = $_SESSION['entered_email'] = $request->request->get('email');
         $password1 = $_SESSION['entered_password'] = $request->request->get('password');
         $password2 = $request->request->get('password2');
         $avatar = $request->files->get("avatar");
         $regulationsCheckbox = $_SESSION['entered_regulations_checkbox'] =$request->request->get('regulations'); // on | NULL

         // Validator
         $validator = Validation::createValidator();

         // A flague, array to which errors will be added
         $errors = [];

        /** Login validation */
         $loginErrors = $validator->validate($login , [
                 new NotBlank(),
                 new Length(['min' => 5, 'max' => 15]),
                 new Regex("/^[a-zA-Z0-9_-]+$/" , "The login can only consist of letters numbers and characters: -_ "),
         ]);

         foreach ($loginErrors as $err) {
                 $errors[] = $err->getMessage();
                 $_SESSION['login_err'] = $err->getMessage();
                 break;
         }

        /** Email validation */
         $emailErrors = $validator->validate($email , [
                 new NotBlank(),
                 new Email(),
         ]);
         foreach ($emailErrors as $err) {
                 $errors[] = $err->getMessage();
                 $_SESSION['email_err'] = $err->getMessage();
                 break;
         }

        /** Password validation */
         $passwordErrors = $validator->validate($password1 , [
                 new NotBlank(),
                 new Length(['min' => 8, 'max' => 20]),
                 new Regex('/^(?=.*[A-Z])(?=.*\d)(?=.*[-_!@#$%^&*()+=?])/' , "Password must contain at least one digit, one big letter, one  special character"),
         ]);

         foreach ($passwordErrors as $err) {
                 $errors[] = $err->getMessage();
                 $_SESSION['pass_err'] = $err->getMessage();
                 break;
         }

        /** Check two passwords are the same */
         $password2Errors = $validator->validate($password1 , [
                new IdenticalTo([
                        'value' => $password2
                ], message: "Two passwords should be identical."),
         ]);
         foreach ($password2Errors as $err) {
                 $errors[] = $err->getMessage();
                 $_SESSION['pass2_err'] = $err->getMessage();
                 break;
         }

        /** File validation*/
         if (isset($avatar)) {

                $fileErrors = $validator->validate($avatar , [
                        new Image([
                                'extensions' => [
                                        'jpg' , 'jpeg' , 'png'
                                ],
                                 'maxSize' => '1M',
                        ])
                 ]);
                 foreach ($fileErrors as $err) {
                         $errors[] = $err->getMessage();
                         $_SESSION['avatar_err'] = $err->getMessage();
                         break;
                 }
         }
        /** Regulations validation */
         $regulationsErrors = $validator->validate($regulationsCheckbox , [
                new notNull(message: "Accept regualations."),
         ]);
         foreach ($regulationsErrors as $err) {
                $errors[] = $err->getMessage();
                $_SESSION['regulations_err'] = $err->getMessage();
                break;
         }

        /** reCaptchav3 */
         $secret = "6LcMCiEoAAAAAE95zqxgXW_8E_61wemis9i22C-u"; // Unique secret key from Google

         $recaptcha = new ReCaptcha($secret);
         $resp = $recaptcha->verify($_POST['g-recaptcha-response'], 'localhost');
         if ($resp->isSuccess()) {
         } else {
                $errors[] = $resp->getErrorCodes()[0];
                $_SESSION['recaptcha_error'] = $resp->getErrorCodes()[0];
         }

        /** Reaction for errors */
         if (!empty($errors)) {
                header('Location: ../public/form.php');
                exit();
         }
 }

/** Save user data in DB */
 /** Create an instance of User class */
  $user = new User();

 /** Check is the email unique */
  try {
        $user->checkEmailUnique($email);
  } catch (Exception $e) {
        $_SESSION['email_err'] = $e->getMessage();
        header('Location: ../public/form.php');
        exit();
  }

 /** Hash password  */
  $hashed_pass = password_hash($password1 , PASSWORD_DEFAULT);

 /** Try to save file in server */
  $avatarFilename = "default_avatar.png";
  if (isset($avatar)) {
        $avatarFilename = $avatar->getClientOriginalName();

        try {
                $extension = $avatar->guessClientExtension();
                $avatarFilename = createUniqueFilename('Avatar' , $avatarFilename , $extension);

                $avatarUploadPath = __DIR__ . '/../assets/imgs/avatars/';
                $avatar->move($avatarUploadPath , $avatarFilename);

        } catch (Throwable $th) {
                $_SESSION['avatar_err'] = "Upload unsuccessfull. Try again later.";
                header('Location: ../public/form.php');
                exit();
        }
  }

 /** Try to insert user in DB */
  try {
        $user->registerUser($login, $email, $hashed_pass , $avatarFilename);

  } catch(PDOException $e) {
        $_SESSION['registration_error'] = "Sorry. Registration unsuccessfull. Try again later.";
        header('Location: ../public/form.php');
        exit();
  }

/** Send welcome email */
 try {
        $mailTemplate = new WelcomeMail($email , $login , Config::MAIL_CONFIG);

        $sender = $mailTemplate->getSender();
        $recipient = $mailTemplate->getRecipient();
        $subject = $mailTemplate->getSubject();
        $altText = $mailTemplate->getAltText();
        $html = $mailTemplate->getHtml();
        $smtpTemplateString = $mailTemplate->getSmtpCredencials();

        $transport = Transport::fromDsn($smtpTemplateString);
        $mailer = new Mailer($transport);
        $welcomeMessage = (new EmailMessage())
            ->from('testowedev123@gmail.com')
            ->to($recipient)
        //     ->to('testowedev123@gmail.com')
            ->subject($subject)
            ->text($altText)
            ->html($html);

        $mailer->send($welcomeMessage);

 } catch (Exception $e) {
        echo "Sorry! You should be registered, but we could not send you a confirmation email. Please check your email address and try to log in.";
 }

/** Display message after successfull registration */
 echo "<h1>Successfull registration!</h1>";
<?php

namespace App\Classes;

class WelcomeMail {

        private $recipient;
        private $login;

        private $config;

        public function __construct(string $recipient , string $login , array $mailConfig ) {

                $this->recipient = $recipient;
                $this->login = $login;

                $this->config = $mailConfig;
        }

        public function getSender() : string {
                return $this->config['sender'];
        }

        public function getRecipient() : string {
                return $this->recipient;
        }

        public function getSubject() : string {
                return "You're already registerd!";
        }

        public function getAltText() : string {
                return "Welcome! \n
                        Hi {$this->login}! Thank you for registering on our website. We believe you will have a great time here. If you need help,      please contact us. \n
                        \n
                        Yours sincerely, \n
                        Page Staff, \n
                        Email: help@example.com";;
        }

        public function getHtml() : string {
                return <<<HTML
                       <h1>Welcome!</h1>
                       <p>Hi <b>{$this->login}</b>! Thank you for registering on our website. We believe you will have a great time here.
                       If you need help, please contact us.</p>

                       <p>Yours sincerely <br>
                       Page staff <br>
                       Email: help@example.com</p>
                HTML;
        }

        public function getSmtpCredencials() : string {
                $smtpLogin = $this->config['smtp_login'];
                $smtpServer = $this->config['smtp_server'];
                $smtpPass = $this->config['smtp_pass'];
                $smtpPort = $this->config['smtp_port'];

                return "smtp://{$smtpLogin}:{$smtpPass}@{$smtpServer}:{$smtpPort}";
        }
}

?>
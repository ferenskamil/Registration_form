<?php

namespace App\Classes;

class Config {

        const DB_CONFIG = [
                'db_host' => 'localhost',
                'db_name' => 'registration_form',
                'db_user' => 'root',
                'db_pass' => ''
        ];

        const MAIL_CONFIG = [
                'smtp_server' => 'smtp.gmail.com',
                'smtp_login' => 'testowedev123@gmail.com',
                'smtp_pass'  => 'rhayhozrhthmtckg',
                'smtp_port' => '465',
                'sender' => 'testowedev123@gmail.com',
        ];
}
<?php

namespace App\Classes;

use App\Classes\Database;
use Exception;
use PDOException;
use PDO;

class User {

        private $db;

        public function __construct() {

                $this->db = Database::getInstance()->getConnection();
        }

        public function registerUser(string $login , string $email , string $password , string $avatar) : void {

                try {
                        $registerQuery = $this->db->prepare("INSERT INTO users
                                        (login, email, password, avatar, registered_at)
                                VALUES (:login, :email, :password , :avatar, NOW())");
                        $registerQuery->bindValue(':login' , $login , PDO::PARAM_STR);
                        $registerQuery->bindValue(':email' , $email , PDO::PARAM_STR);
                        $registerQuery->bindValue(':password' , $password , PDO::PARAM_STR);
                        $registerQuery->bindValue(':avatar' , $avatar , PDO::PARAM_STR);
                        $registerQuery->execute();

                } catch(PDOException $e){
                        $e->getMessage();
                };
        }

        public function checkEmailUnique(string $email) : bool {

                $allEmails = [];

                try {
                        $getEmailsQuery = $this->db->prepare("SELECT email FROM users");
                        $getEmailsQuery->execute();
                        $allUsers = $getEmailsQuery->fetchAll(PDO::FETCH_ASSOC);

                        foreach($allUsers as $user) $allEmails[] = $user['email'];

                } catch(PDOException $e) {
                        $e->getMessage();
                        return false;
                }


                if (in_array($email ,  $allEmails)) {
                        throw new Exception("This email is already exist. Try another email.");
                        return false;
                }

                return true;
        }
}

?>
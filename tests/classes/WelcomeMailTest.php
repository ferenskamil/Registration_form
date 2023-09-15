<?php declare(strict_types=1);

require "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use App\Classes\WelcomeMail;
use App\Classes\Config;

final class WelcomeMailTest extends TestCase {

        public function testOutputTypes() : void {
                $mail = new WelcomeMail("John@gmail.com" , "John999" , Config::MAIL_CONFIG);

                $resultSender = $mail->getSender();
                $this->assertIsString($resultSender);

                $resultRecipient = $mail->getRecipient();
                $this->assertIsString($resultRecipient);

                $resultSubject = $mail->getSubject();
                $this->assertIsString($resultSubject);

                $resultAltText = $mail->getAltText();
                $this->assertIsString($resultAltText);

                $resultHtml = $mail->getHtml();
                $this->assertIsString($resultHtml);

                $resultSmtpCredencials = $mail->getSmtpCredencials();
                $this->assertIsString($resultSmtpCredencials);
        }

        public function testLoginInHtml() : void {
                $testLogin = "John999";

                $mail = new WelcomeMail("John@gmail.com" , $testLogin , Config::MAIL_CONFIG);

                $resultHtml = $mail->getHtml();

                $this->assertStringContainsString($testLogin , $resultHtml);
        }

        public function testCorrectSmtpCredencialsString() : void {
                $testConfig = [
                        'smtp_server' => 'aaaaaa',
                        'smtp_login' => 'bbbbbb',
                        'smtp_pass'  => 'cccccc',
                        'smtp_port' => '999',
                        'sender' => 'ddddddd',
                ];


                $mail = new WelcomeMail("test" , "test" , $testConfig);
                $resultSmtpCredencials = $mail->getSmtpCredencials();

                $expectedSmtpCredencials = "smtp://bbbbbb:cccccc@aaaaaa:999";
                $this->assertEquals($expectedSmtpCredencials , $resultSmtpCredencials);

        }
}
?>
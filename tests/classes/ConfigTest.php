<?php declare(strict_types=1);

require "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use App\Classes\Config;


final class ConfigTest extends TestCase {
        public function testDB_CONFIG(): void
        {
                $config = Config::DB_CONFIG;

                $keys = [];
                foreach ($config as $key => $value) {
                        $keys[] = $key;
                }

                $this->assertContains('db_host', $keys);
                $this->assertContains('db_name', $keys);
                $this->assertContains('db_user', $keys);
                $this->assertContains('db_pass', $keys);
        }

        public function testMAIL_CONFIG(): void
        {
                $config = Config::MAIL_CONFIG;

                $keys = [];
                foreach ($config as $key => $value) {
                        $keys[] = $key;
                }

                $this->assertContains('smtp_server', $keys);
                $this->assertContains('smtp_login', $keys);
                $this->assertContains('smtp_pass', $keys);
                $this->assertContains('smtp_port', $keys);
                $this->assertContains('sender', $keys);
                $this->assertContains('sender', $keys);
        }

}

?>
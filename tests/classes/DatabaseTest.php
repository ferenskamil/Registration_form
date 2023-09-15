<?php declare(strict_types=1);

require "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use App\Classes\Database;

final class DatabaseTest extends TestCase {

        public function testGetInstanceOutput() {
                $db = new Database();

                $resultInstance = $db->getInstance();
                $expectedInstance = Database::class;
                $this->assertInstanceOf($expectedInstance , $resultInstance);
        }

        public function testGetConnectionOutput() {
                $db = new Database();

                $resultInstance = $db->getConnection();
                $expectedInstance = PDO::class;
                $this->assertInstanceOf($expectedInstance , $resultInstance);
        }
}
?>
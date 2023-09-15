<?php declare(strict_types=1);

require "vendor/autoload.php";

require_once __DIR__ . "/../../src/helpers/createUniqueFilename.php";
use function App\Helpers\createUniqueFilename;

use PHPUnit\Framework\TestCase;

final class createUniqueFilenameTest extends TestCase {

        public function testOutputContainsCurrentTimeInSeconds() : void {
                $prefix = "test";
                $string = "Loremipsum";
                $extension = "php";

                $result = createUniqueFilename($prefix , $string, $extension);
                $searchedPart = strval(time());

                $this->assertStringContainsString($searchedPart , $result);
        }

        public function testOutputContainsPrefix() : void {
                $prefix = "test";
                $string = "Loremipsum";
                $extension = "php";

                $result = createUniqueFilename($prefix , $string, $extension);
                $searchedPart = $prefix;

                $this->assertStringContainsString($searchedPart , $result);
        }
}
?>
<?php
// If PHPUnit's base TestCase isn't available, define a simple one
if (!class_exists('\PHPUnit\Framework\TestCase')) {
    require_once 'PHPUnit/Autoload.php';
}

// Use PHPUnit's base TestCase if advanced_testcase isn't available
if (!class_exists('advanced_testcase')) {
    class advanced_testcase extends \PHPUnit\Framework\TestCase {
        protected function resetAfterTest() {
            // Do nothing in standalone mode
        }
        
        protected function setAdminUser() {
            global $USER;
            $USER = (object)['id' => 2, 'firstname' => 'Admin', 'lastname' => 'User'];
        }
    }
}

// Include the class we want to test
require_once(__DIR__ . '/../../../../../enrol/ipaymu/lib.php');

class enrol_ipaymu_plugin_test extends advanced_testcase {
    // moved to individual test classes
}

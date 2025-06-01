<?php
// filepath: /var/www/html/moodle/enrol/ipaymu/enrol/ipaymu/tests/enrol_ipaymu_enrol_user_test.php
// If PHPUnit's base TestCase isn't available, define a simple one
if (!class_exists('\\PHPUnit\\Framework\\TestCase')) {
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

// Include Moodle config and the class we want to test
require_once(__DIR__ . '/../../../../../config.php');
global $CFG;
require_once($CFG->dirroot . '/enrol/ipaymu/lib.php');

class enrol_ipaymu_enrol_user_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest();
    }

    public function test_enrol_after_payment() {
        global $DB;
        
        $this->setAdminUser();
        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $plugin = enrol_get_plugin('ipaymu');
        
        // Create enrollment instance
        $formdata = new stdClass();
        $formdata->cost = '100.00';
        $formdata->currency = 'IDR';
        $formdata->roleid = 5; // Student role
        $formdata->status = 0; // Enabled
        $formdata->enrolperiod = 86400; // 1 day
        
        $instanceid = $plugin->add_instance($course, (array)$formdata);
        $instance = $DB->get_record('enrol', ['id' => $instanceid]);
        
        // Before enrollment, user should not be enrolled
        $context = context_course::instance($course->id);
        $this->assertFalse(is_enrolled($context, $user));
        
        // Simulate enrollment after payment
        $plugin->enrol_user($instance, $user->id, $instance->roleid);
        
        // After successful payment and enrollment, user should be enrolled
        $this->assertTrue(is_enrolled($context, $user));
    }
}

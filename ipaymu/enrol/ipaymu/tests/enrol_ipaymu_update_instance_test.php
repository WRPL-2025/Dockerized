<?php
// filepath: /var/www/html/moodle/enrol/ipaymu/enrol/ipaymu/tests/enrol_ipaymu_update_instance_test.php
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

class enrol_ipaymu_update_instance_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest();
    }

    public function test_update_instance_cost() {
        global $DB;

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $plugin = enrol_get_plugin('ipaymu');

        // Create an initial instance
        $initial_formdata = new stdClass();
        $initial_formdata->cost = '100.00';
        $initial_formdata->currency = 'IDR';
        $initial_formdata->roleid = 5;
        $initial_formdata->status = 0;
        $instanceid = $plugin->add_instance($course, (array)$initial_formdata);
        $instance = $DB->get_record('enrol', ['id' => $instanceid]);
        $this->assertNotFalse($instance);

        // Data for update
        $updatedata = new stdClass();
        $updatedata->id = $instance->id;
        $updatedata->cost = '250.75'; // New cost
        // Other fields like currency, roleid, status can be updated too

        $result = $plugin->update_instance($instance, $updatedata);
        $this->assertTrue($result);

        $updated_instance = $DB->get_record('enrol', ['id' => $instanceid]);
        $this->assertNotFalse($updated_instance);
        $this->assertEquals(250.75, (float)$updated_instance->cost);
    }
}

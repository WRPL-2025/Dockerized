<?php
// filepath: /var/www/html/moodle/enrol/ipaymu/enrol/ipaymu/tests/enrol_ipaymu_add_instance_test.php

use PHPUnit\Framework\TestCase;

// If PHPUnit's base TestCase isn't available, define a simple one
if (!class_exists('\\PHPUnit\\Framework\\TestCase')) {
    require_once 'PHPUnit/Autoload.php';
}

// Use PHPUnit's base TestCase if advanced_testcase isn't available
if (!class_exists('advanced_testcase')) {
    class advanced_testcase extends TestCase {
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

class enrol_ipaymu_add_instance_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest();
    }

    public function test_add_instance_with_cost() {
        global $DB;

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $plugin = enrol_get_plugin('ipaymu');

        $fields = [
            'courseid' => $course->id,
            'type' => 'ipaymu',
            'name' => 'iPaymu Enrolment',
            'enrol_ipaymu_cost' => '150.50', // Cost as string, might be localized
            'enrol_ipaymu_currency' => 'IDR',
            'enrol_ipaymu_roleid' => 5, // Student role
            'status' => 0, // Enabled
        ];

        // Simulate form data structure for add_instance
        $formdata = new stdClass();
        $formdata->cost = $fields['enrol_ipaymu_cost'];
        $formdata->currency = $fields['enrol_ipaymu_currency'];
        $formdata->roleid = $fields['enrol_ipaymu_roleid'];
        $formdata->status = $fields['status'];
        // Other fields like enrolperiod, enrolstartdate, enrolenddate can be added if needed

        $instanceid = $plugin->add_instance($course, (array)$formdata);
        $this->assertNotEmpty($instanceid);

        $instance = $DB->get_record('enrol', ['id' => $instanceid]);
        $this->assertNotFalse($instance);
        $this->assertEquals(150.50, (float)$instance->cost);
        $this->assertEquals('IDR', $instance->currency);
    }
}

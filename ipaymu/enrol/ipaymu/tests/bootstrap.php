<?php
// Define a basic autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to full file path
    $class = str_replace('\\', '/', $class);
    $class = str_replace('enrol_ipaymu/', '', $class);
    
    // Define search paths
    $paths = [
        __DIR__ . '/../classes/',
        __DIR__ . '/../',
    ];
    
    // Check each location
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Mock essential Moodle functions
if (!function_exists('get_string')) {
    function get_string($identifier, $component = '', $a = null) {
        return "String: $identifier ($component)";
    }
}

if (!function_exists('enrol_get_plugin')) {
    function enrol_get_plugin($type) {
        if ($type === 'ipaymu') {
            require_once(__DIR__ . '/../../../lib.php');
            return new enrol_ipaymu_plugin();
        }
        return null;
    }
}

// Set up database mock
global $DB;
$DB = new class {
    private $records = [];
    
    public function get_record($table, $conditions, $fields = '*', $strictness = null) {
        return (object)['id' => 1, 'cost' => 100.00, 'currency' => 'IDR'];
    }
    
    public function insert_record($table, $dataobject, $returnid = true) {
        return 1;
    }
    
    public function update_record($table, $dataobject) {
        return true;
    }
};

// Mock other globals as needed
global $CFG;
$CFG = (object)['dirroot' => __DIR__ . '/..'];

// Define context_course class if it doesn't exist
if (!class_exists('context_course')) {
    class context_course {
        public static function instance($courseid) {
            return new self();
        }
        
        public function id() {
            return 1;
        }
    }
}

// Mock is_enrolled function
if (!function_exists('is_enrolled')) {
    function is_enrolled($context, $user) {
        return true;
    }
}

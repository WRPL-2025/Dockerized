[![ci](https://github.com/ipaymu/moodle-enrol_ipaymu/actions/workflows/ci.yml/badge.svg)](https://github.com/ipaymu/moodle-enrol_ipaymu/actions/workflows/ci.yml)
# Moodle Payment Gateway iPaymu

Welcome to the iPaymu plugin repository for Moodle. This plugin is a payment as enrolment in moodle. As direction from moodle documentation about plugin type enrol. iPaymu attend a plugin to help you receive payment through iPaymu.

## Steps you need to Integrate
1. Download and install the plugin.
2. If you haven't iPaymu account then you need to register.
3. Grab your iPaymu API key and merchant code.
4. Configure the Moodle enrolment with iPaymu payment.
5. Add 'iPaymu Payment' to the Moodle courses that you want

### Installation
After you download the plugin.
1. First, you need to login as admin to your moodle site.
2. Then, go to **Site administration** -> **Plugins** -> **Install plugins**
3. You'll see the choose file button or you can drag and drop the plugin zip file to the box. Choose or drop the zip file plugin.
4. Then, click **install plugin from ZIP file**.
5. Then, click **continue** after installation complete.

### Create iPaymu Account
> To create an account you may see it [here](https://my.iPaymu.com/).

### Configure iPaymu payment as enrolment method
1. For the configuration, go to **Site administration** -> **Plugins** -> **Enrolments** -> **Manage enrol plugins**.
2. You should found **iPaymu Payment** on the list. Make sure it is enable.
7. Input **API key**, **merchant code**, and **expiry period** of your desired value.
8. Then don't forget to set it in the right **environment**.
5. You can configure enrolment setting within **iPaymu Payment**.

>***Please note, if you set wrong environment the access would be denied on payment.*

### Add iPaymu payment
1. Go to course that you desired to add a payment.
2. On inside the course go to **participants**.
3. On the **participants** page, click the actions menu and select **Enrolment methods**.
4. Choose **iPaymu Payment** from the Add dropdown menu.
5. You can set cost for the course on the **Enrol cost** then click the button **Add method**.

## Details

iPaymu offers payment in Rupiah currencies that supported with virtual accounts, QRIS, paylater, e-wallet, retail outlets and credit card around Indonesia.
You might visit our website at [www.iPaymu.com](https://www.iPaymu.com/) for further information.




 ## How to do Unit Testing in PHP with PHPUnit:
 
  1. Install PHPUnit:
     Use Composer: `composer require --dev phpunit/phpunit`
 
  2. Create Test Classes:
     - For a class `MyClass`, create `MyClassTest.php` (e.g., in a `tests/` directory).
     - Test classes extend `\PHPUnit\Framework\TestCase`.
     Example:
       // tests/MyClassTest.php
       <?php
       use PHPUnit\Framework\TestCase;
       // require_once 'path/to/MyClass.php'; // If not using autoloader
 
       class MyClassTest extends TestCase {
           public function testMethodName() {
               // $obj = new MyClass();
               // $this->assertEquals(expected, $obj->someMethod());
               $this->assertTrue(true); // Placeholder for actual test logic
           }
       }
 
  3. Write Test Methods:
     - Public methods, names usually start with `test`.
     - Use assertions to check outcomes (e.g., `assertEquals`, `assertTrue`, `assertFalse`).
 
  4. Run Tests:
     From your project root: `./vendor/bin/phpunit`
 
  5. Configuration (Optional):
     Use `phpunit.xml` or `phpunit.xml.dist` for test suite configuration, bootstrapping, etc.
 
  Testing Moodle Plugins (like enrol_ipaymu):
  - Moodle has its own testing infrastructure. It's recommended to use it.
  - Mocking Moodle's global objects ($DB, $CFG, $USER) and API is often necessary.
  - For `enrol_ipaymu_plugin`, you'd create `enrol_ipaymu_plugin_test.php`.
    Methods like `get_currencies()` could be tested by instantiating the plugin
    (possibly with mocked dependencies) and asserting the returned array structure.
  - Refer to Moodle developer documentation for specific guidance on plugin testing.
 


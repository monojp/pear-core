--TEST--
PEAR_Config->setRegistry()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$config = new PEAR_Config($temp_path . DIRECTORY_SEPARATOR . 'nope', $temp_path . DIRECTORY_SEPARATOR . 'nope');
$phpunit->assertFalse($config->getRegistry(), 'initial user');
$phpunit->assertFalse($config->getRegistry('system'), 'initial system');

$reg = &new PEAR_Registry($temp_path);
$config->setRegistry($reg, 'system');
$phpunit->assertFalse($config->getRegistry(), 'after system user');
$phpunit->assertIsa('PEAR_Registry', $config->getRegistry('system'), 'after system system');

$reg1 = &new PEAR_Registry($temp_path . DIRECTORY_SEPARATOR . 'php');
$config->setRegistry($reg1, 'user');
$phpunit->assertIsa('PEAR_Registry', $config->getRegistry('user'), 'after user user');
$phpunit->assertIsa('PEAR_Registry', $config->getRegistry('system'), 'after user system');

$test1 = &$config->getRegistry();
$test2 = &$config->getRegistry('system');
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.registry',
    $test1->statedir, 'user statedir');
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . '.registry',
    $test2->statedir, 'system statedir');
echo 'tests done';
?>
--EXPECT--
tests done

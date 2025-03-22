#!/usr/bin/php
<?php
/**
 * OpenTHC CloudPRNT Test
 *
 * SPDX-License-Identifier: MIT
 */

require_once(dirname(__DIR__) . '/boot.php');

// $arg = \OpenTHC\Docopt::parse($doc, ?$argv=[]);
// Parse CLI
$doc = <<<DOC
OpenTHC CloudPRNT Test

Usage:
	test [options]

Options:
	--filter=<FILTER>   Some Filter for PHPUnit

DOC;

$arg = Docopt::handle($doc, [
	'help' => false,
	'optionsFirst' => true,
]);
$cli_args = $arg->args;
// var_dump($cli_args);


define('OPENTHC_TEST_OUTPUT_BASE', \OpenTHC\Test\Helper::output_path_init());


// Call Linter?
$tc = new \OpenTHC\Test\Facade\PHPLint([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
// $res = $tc->execute();
// var_dump($res);


// Call PHPCS?
// $tc = \OpenTHC\Test\PHPStyle::execute();


// PHPStan
$tc = new OpenTHC\Test\Facade\PHPStan([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
// $res = $tc->execute();
// var_dump($res);


// Psalm/Psalter?


// PHPUnit
// $cfg = [];
// $tc = new OpenTHC\Test\Facade\PHPUnit($cfg);
// $res = $tc->execute();
// var_dump($res);
$cfg = [];

// Pick Config File
$cfg_file_list = [];
$cfg_file_list[] = sprintf('%s/phpunit.xml', __DIR__);
$cfg_file_list[] = sprintf('%s/phpunit.xml.dist', __DIR__);
foreach ($cfg_file_list as $f) {
	if (is_file($f)) {
		$cfg['--configuration'] = $f;
		break;
	}
}
// Filter?
if ( ! empty($cli_args['--filter'])) {
	$cfg['--filter'] = $cli_args['--filter'];
}

$tc = new \OpenTHC\Test\Facade\PHPUnit($cfg);
$res = $tc->execute();
var_dump($res);
echo $res['data'];

// Done
\OpenTHC\Test\Helper::index_create($html);


// Output Information
\OpenTHC\Config::init(APP_ROOT);
$origin = \OpenTHC\Config::get('openthc/cps/origin');
$output = str_replace(sprintf('%s/webroot/', APP_ROOT), '', OPENTHC_TEST_OUTPUT_BASE);

echo "TEST COMPLETE\n  $origin/$output\n";

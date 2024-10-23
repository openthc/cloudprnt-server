<?php
/**
 * OpenTHC CloudPRNT Server
 *
 * SPDX-License-Identifier: MIT
 */

require_once(dirname(__DIR__) . '/boot.php');

// Routes?
// GET /printers or /devices -- get the current evice list
// POST /
// POST /mqtt if you want that option?
$path = $_SERVER['REQUEST_URI'];
$path = strtok($path, '?');

$verb = strtoupper($_SERVER['REQUEST_METHOD']);

$_SERVER['REQUEST_PATH'] = sprintf('%s:%s', $path, $verb);

switch ($_SERVER['REQUEST_PATH']) {
case '/:GET':

	$body = <<<TEXT
	OpenTHC Cloud Print Server

	This box expects to be defined as a Print Server
	for your Star CloudPRNT Enabled Printers

	https://{$_SERVER['SERVER_NAME']}

	More information at https://openthc.org/pos

	TEXT;

	_exit_text($body);

	break;

case '/:POST':
	_print_job_search();
	break;
case '/job:DELETE':
	_print_job_delete();
	break;
case '/job:GET':
	_print_job_select();
	break;
default:
	_exit_text('Invalid Request', 400);
}

/**
 *
 */
function _print_job_delete()
{
// cloudprnt.openthc.dev 73.119.224.227 - [22/Oct/2024:20:18:21 +0000] "DELETE /?mac=00%3A11%3A62%3A31%3Af0%3A57&code=200+OK HTTP/1.1" 200 - "-" "CloudPRNT/3.0 mC-Print3/3.6"

}

/**
 *
 */
function _print_job_search()
{
	// "CONTENT_LENGTH": "317",
	// "CONTENT_TYPE": "application/json",

	// "HTTP_ACCEPT": "*/*",
	// "HTTP_CONNECTION": "keep-alive",
	// "HTTP_HOST": "cloudprnt.openthc.dev",
	// "HTTP_USER_AGENT": "CloudPRNT/3.0 mC-Print3/3.6",
	// "HTTP_X_STAR_MAC": "00:11:62:31:f0:57",
	// "HTTP_X_STAR_SERIAL_NUMBER": "2629522080600547",

	$req_body = json_decode(file_get_contents('php://input'), true);

	$device = [];
	$device['id'] = $req_body['printerMAC'];
	$device['time'] = $_GET['t'];
	// Check for Device Somewhere?

	$device['status'] = $req_body['statusCode'];
	$device['status'] = rawurldecode($device['status']);

	// printingInProgress
	// barcodeReader
	// display

	// clientAction

	$log_data = json_encode($device, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	$log_file = sprintf('%s/var/dev-data.txt', APP_ROOT);
	file_put_contents($log_file, $log_data);


	$res_body = new \stdClass();
	$res_body->jobReady = false;
	// $res_body->mediaTypes = [ 'application/pdf', 'image/png', 'text/plain' ];
	// $res_body->jobToken = "<string token>",
	// $res_body->deleteMethod = "DELETE"|"GET",
	// $res_body->clientAction = [ {"request": "<request type>", "options": "<request parameters>"} ],
	// $res_body->claimBarcodeReader = [ "<device name>" ],
	// $res_body->claimKeyboard = [ "<device name>" ],
	// $res_body->display = [ { "name": "<device name>", "message": "<message markup>" } ],
	$res_body->jobGetUrl = sprintf('https://%s/job', $_SERVER['SERVER_NAME']);
	$res_body->jobConfirmationUrl = sprintf('https://%s/job', $_SERVER['SERVER_NAME']);

	// Got Some Text to Print
	// $res_body->jobReady = true;
	// $res_body->jobToken = '01JATZG4W1J8MTE9YSBG85QPZQ';
	// $res_body->mediaTypes = [ 'text/plain' ];


	// Got Some PDF to Print
	// Convert to PNG First
	// $res_body->jobReady = true;
	// $res_body->jobToken = '01JATZG4W1J8MTE9YSBG85QPZQ';
	// $res_body->mediaTypes = [ 'application/pdf' ]; // Fails

	// Got Some PNG to Print
	// $res_body->jobReady = true;
	// $res_body->jobToken = '01JATZG4W1J8MTE9YSBG85QPZQ';
	// $res_body->mediaTypes = [ 'image/png' ];

	$key_list = [
		'CONTEXT_DOCUMENT_ROOT',
		'CONTEXT_PREFIX',
		'GATEWAY_INTERFACE',
		'PATH',
		'PHP_SELF',
		'REDIRECT_HTTPS',
		'REDIRECT_QUERY_STRING',
		'REDIRECT_SCRIPT_URI',
		'REDIRECT_SCRIPT_URL',
		'REDIRECT_SSL_TLS_SNI',
		'REDIRECT_STATUS',
		'REDIRECT_UNIQUE_ID',
		'REDIRECT_URL',
		'SCRIPT_FILENAME',
		'SCRIPT_NAME',
		'SERVER_ADMIN',
		'SERVER_PROTOCOL',
		'SERVER_SIGNATURE',
		'SERVER_SOFTWARE',
		'UNIQUE_ID',
	];
	foreach ($key_list as $k) {
		unset($_SERVER[$k]);
	}
	ksort($_SERVER);
	ksort($_GET);

	$log_data = [];
	$log_data['_SERVER'] = $_SERVER;
	$log_data['_GET'] = $_GET;
	$log_data['_POST-JSON'] = $req_body;
	$log_data['_RESPONSE'] = $res_body;

	$log_data = json_encode($log_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	$log_file = sprintf('%s/var/req-data.txt', APP_ROOT);
	file_put_contents($log_file, $log_data);

	_exit_json($res_body);

}

// https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/protocol-reference/http-method-reference/server-polling-post/json-response.html
// {
//     "jobReady": true|false,
//     "mediaTypes": [ "<content media type>" ],
//     "jobToken": "<string token>",
//     "deleteMethod": "DELETE"|"GET",
//     "clientAction": [ {"request": "<request type>", "options": "<request parameters>"} ],
//     "claimBarcodeReader": [ "<device name" ],
//     "claimKeyboard": [ "<device name>" ],
//     "display": [ { "name": "<device name>", "message": "<message markup>" } ],
//     "jobGetUrl": "<alternative URL for job GET>",
//     "jobConfirmationUrl": "<alternative URL for job confirmation>"
// }


/**
 *
 */
function _print_job_select()
{
	if (empty($_GET['mac'])) {
		_exit_text('Invalid Request', 400);
	}
	if (empty($_GET['type'])) {
		_exit_text('Invalid Request', 400);
	}

	switch ($_GET['type']) {
	case 'image/png':
		break;
	case 'text/plain':
		break;
	}

	// Requesting Print Job

	$device = new stdClass();
	$device->id = $_GET['mac'];

	$source = new stdClass();
	$source->id = $_GET['token'];

	$output = new stdClass();
	$output->type = $_GET['type'];

	$log_data = [];
	$log_data['_SERVER'] = $_SERVER;
	$log_data['_GET'] = $_GET;
	$log_data['_POST'] = $req_body;

	$log_data = json_encode($log_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	$log_file = sprintf('%s/var/job-select.txt', APP_ROOT);
	file_put_contents($log_file, $log_data);

	header('content-type: image/png');

	readfile(sprintf('%s/OUTPUT.png', APP_ROOT));

	exit(0);

}

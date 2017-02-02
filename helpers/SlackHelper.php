<?php

use Phalcon\Http\Response;

class SlackHelper
{

	// Notify "forking" a curl process
	public static function notify ( $webhook, array $message ) {
		$cmd  = "curl -X POST '" . $webhook . "'";
		$cmd .= " -H 'Content-Type: application/json'";
		$cmd .= " -d '" . json_encode ( $message ) . "'";
		$cmd .= " &";

		exec($cmd, $output, $status);

  		if ($status != 0) {
  			throw new Exception("Slack notify failed");
  		}
	}

	public static function notifyRaw ( $webhook, $text ) {
		ob_start();
		echo('{"text": "'. $text .'"}');
		header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
		header("Content-Type: application/json");
		header('Content-Length: '.ob_get_length());
		ob_end_flush();
		ob_flush();
		flush();
	}

	public static function notifyNative ( array $message ) {
		$response = new Response();
		$response->setContentType('application/json', 'UTF-8');
		$response->setContent( json_encode ( $message ) );
		$response->send();
	}

}

<?php

require_once(__DIR__ . '/../config/Config.php');
require_once(__DIR__ . '/../helpers/SlackHelper.php');

abstract class Deploy
{
	public $environment = null;
	public $project     = null;
	public $branch      = null;
	public $path        = null;
	public $webhook     = null;
	public $message     = [];

	public function __construct( $environment, $project, $branch ) {
		$this->environment = $environment;
		$this->project     = $project;
		$this->branch      = $branch;
	}

	public static function usage() {
		return "Usage: /deploy _<project> <environment> <git-branch>_";
	}

	public static function isValidToken ( $token ) {
		return $token == Config::SLACK_TOKEN;
	}

	public static function getParams ( $params ) {
		return split( " ",	urldecode( $params ) );
	}

	protected function clearMessage(){
		$this->message = [];
	}

	protected function start(){
		SlackHelper::notifyRaw($this->webhook, "*Running the deploy ...*");
	}

	abstract public function run();

}

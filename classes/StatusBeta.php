<?php

require_once(__DIR__ . '/Deploy.php');
require_once(__DIR__ . '/../helpers/GitBuilder.php');

class StatusBeta extends Deploy
{
	public function __construct( $environment, $project) {
		parent::__construct( $environment, $project, null );
		$this->path = Config::getPath( $environment, $project );
	}

	public function run() {
 		if ( $this->webhook ) {
			$this->start('Running status ...');

	 		// Build commands
	 		$builder = new GitBuilder();
	 		$builder->path = $this->path;

			// Get repo status
	 		$builder->status()->run();

	 		if ( $builder->status != 0 ) {
	 			throw new Exception( implode("\n", $builder->output) );
	 		}

			// Notify results to slack
	 		$this->notifyBuilderOutput( 'GIT status', $builder );
 		} else {
			throw new Exception("Webhook not set");
 		}
	}

}

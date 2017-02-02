<?php

require_once(__DIR__ . '/Deploy.php');
require_once(__DIR__ . '/../config/Config.php');
require_once(__DIR__ . '/../helpers/SlackHelper.php');
require_once(__DIR__ . '/../helpers/GitBuilder.php');

class DeployBeta extends Deploy
{
	public function __construct( $environment, $project, $branch ) {
		parent::__construct( $environment, $project, $branch );
		$this->path = Config::getPath( $environment, $project );
	}

	public function run() {
 		if ( $this->webhook ) {
			$this->start();

	 		// Build commands
	 		$builder = new GitBuilder();
	 		$builder->path = $this->path;
	 		$builder->branch = $this->branch;

			// Get repo status
	 		$builder->status()
	 			->outputMustHave(
	 				array('working directory clean','nothing added to commit'), 'Directory not clean')
	 			->run();

	 		if ( $builder->status != 0 ) {
	 			throw new Exception( implode("\n", $builder->output) );
	 		}

			// Deploy using git
	 		$builder->reset()
	 			->deploy()
	 			->runAs(Config::BETA_USER);

 	 		if ( $builder->status != 0 ) {
	 			throw new Exception( implode("\n", $builder->output) );
	 		}

			// Notify results to slack
			$this->clearMessage();
			$this->message['attachments'] = array (
	 			array (
	 				'title' => 'GIT results for branch ' .$this->branch,
	 				'text' => implode ( " ", $builder->output ),
					'color' => 'good',
					'author_name' => Config::SLACK_USER_NAME,
	 				'author_icon' => Config::SLACK_USER_ICON
           		)
           	);

			SlackHelper::notify($this->webhook, $this->message);
 		} else {
			throw new Exception("Webhook not set");
 		}
	}

}

<?php

class Config {
	const SLACK_TOKEN     = '<token>';
	const SLACK_USER_NAME = '<username>';
	const SLACK_USER_ICON = '<url-to-user-icon>';

	//--------------------------------------------------
	// /etc/sudoers
	//
	// # Allow run commands as beta user
	// www-data   ALL=(<evironment>) NOPASSWD:ALL

	const BETA_ENVIRONMENT = '<evironment>';
	const BETA_USER        = '<server-deploy-user>';

	private static $DATA = array (
		'beta'  => array (
			'projects' => array( '<my_project>' ),
			'paths'    => array( '<my_project>' => '<project-server-path>' )
		)
	);

	public static function hasEnvironment( $environment ) {
		return array_key_exists( $environment, self::$DATA );
	}

	public static function hasProject( $environment, $project ) {
		return in_array( $project, self::$DATA[ $environment ][ 'projects' ] );
	}

	public static function getPath ( $environment, $project ) {
		return self::$DATA[ $environment ][ 'paths' ][ $project ];
	}

}

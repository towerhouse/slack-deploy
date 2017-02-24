<?php

require_once(__DIR__ . '/../config/Config.php');
require_once(__DIR__ . '/Deploy.php');
require_once(__DIR__ . '/DeployBeta.php');
require_once(__DIR__ . '/StatusBeta.php');

abstract class DeployFactory
{
	public static function create ( $environment, $project, $branch ) {
		if ( is_null( $project ) || empty( $project ) ) {
			throw new Exception( "_project_ can not be null\n" . Deploy::usage() );
		} else if ( is_null( $environment ) || empty( $environment ) ) {
			throw new Exception( "_environment_ can not be null\n" . Deploy::usage() );
		} else if ( !Config::hasEnvironment( $environment ) ) {
			throw new Exception( "Environment _{$environment}_ does not exist\n" . Deploy::usage() );
		} else {
			switch ( $environment ) {
				case Config::BETA_ENVIRONMENT:
					 if ( is_null( $branch ) || empty( $branch ) ) {
				 		return new StatusBeta($environment, $project);
					 } else if ( Config::hasProject( $environment, $project ) ) {
						return new DeployBeta( $environment, $project, $branch );
					}
				default:
					throw new Exception( "Project _{$project}_ does not exist\n" . Deploy::usage() );
			}
		}
	}

}

<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Http\Response;

$loader = new Loader();
$loader->registerClasses([
	'Deploy' => 'classes/Deploy.php',
	'DeployFactory' => 'classes/DeployFactory.php'
]);
$loader->register();

$app = new Micro();

//-----------------------------------------------------------------------------
// Routes here

$app->post(
    "/deploy",
    function () use ($app)
    {
		// ------------------------------------------------
		// Test from console using curl:
		// curl -i -X POST <slack-endpoint> -F 'token=<slack-token>' -F 'text=<project>%20<environment>%20<branch>' -F 'response_url=google.com'

		try {
 			if ( Deploy::isValidToken( $app->request->getPost('token') ) ) {
 				list($project, $environment,  $branch) = Deploy::getParams(
					$app->request->getPost('text')
 				);

				$deploy = DeployFactory::create( $environment, $project, $branch );
				$deploy->webhook = $app->request->getPost('response_url');
				$deploy->run();
			}
		} catch( \Exception $e ) {
			$message = array(
				'attachments' => array(
					array(
						'title'  => 'Results',
		 				'text'   => $e->getMessage(),
						'color'  => 'danger',
						"mrkdwn_in"   => ["text"],
						'author_name' => Config::SLACK_USER_NAME,
		 				'author_icon' => Config::SLACK_USER_ICON
					)
				)
			);

			if ( isset( $deploy ) && !is_null( $deploy->webhook ) ) {
				SlackHelper::notify( $deploy->webhook, $message );
			} else {
				$message['text'] = '*Error*';
				SlackHelper::notifyNative( $message );
			}
		}
	}
);


$app->handle();

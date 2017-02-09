# Slack Deploy

[![towerhousestudio](http://towerhousestudio.com/wp-content/uploads/2016/04/nuevo-logo-towerhouse2-1s-300x296.png)](http://towerhousestudio.com)

This is an internal tool that we did for our team to deploy code in our beta servers using slack and git.

## Dependencies

This project is written in PHP over a LAMP stack and is using [Phalcon PHP](https://phalconphp.com/) over to manage requests and responses.

## Instalation

### Server Configuration

In my case the first thing I did was creating a subdomain for slack commands, so  you can create a: *http:://slack.mybetaserver.com* as the endpoint for slack commands.

Next I was using apache as the web server, so I created a new site. Here is my *slack.conf*

```html
<VirtualHost *:80>
    ServerName slack.mybetaserver.com
    DocumentRoot /var/www/slack
    <Directory /var/www/slack>
        Options FollowSymLinks
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>
    ErrorLog /var/log/apache2/error_slack.log
    LogLevel warn
</VirtualHost>
```

Finally you must [install Phalcon PHP](https://phalconphp.com/en/download) in your server.

### Slack Configuration

First we need to enable slash integration. You can find the slack docs for custom integrations [here](https://api.slack.com/custom-integrations).

[![towerhousestudio](http://slack.towerhousestudio.com/image/slack_custom_integrations.png)](https://github.com/towerhouse/slack-deploy)

So we want to create a new slash command:

[![towerhousestudio](http://slack.towerhousestudio.com/image/slash_command_config.png)](https://github.com/towerhouse/slack-deploy)

To with slack, you have to configure the command:

[![towerhousestudio](http://slack.towerhousestudio.com/image/slash_command.png)](https://github.com/towerhouse/slack-deploy)

### App Configuration

Finally you must configure the app. To do that you must edit _config/Config.php_ and set:
* Your slash token for the command
* Username and Icon to show in the output of slack command
* Enviroment
* User to run the git commands
* Structure of each enviroment, including project and path of each project

```php
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
		'<evironment>'  => array (
			'projects' => array( '<my_project>' ),
			'paths'    => array( '<my_project>' => '<project-server-path>' )
		)
	);
```

### Usage

Now that everything is installed you run in slack the command:

_/deploy <project> <enviroment> <git-branch>_

[![towerhousestudio](http://slack.towerhousestudio.com/image/run_command_help.png)](https://github.com/towerhouse/slack-deploy)


[![towerhousestudio](http://slack.towerhousestudio.com/image/run_command_deploy.png)](https://github.com/towerhouse/slack-deploy)

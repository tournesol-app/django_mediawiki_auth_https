## Authenticate mediawiki users with a Django https request

This repository implements a custom authenticator for MediaWiki that tries to log in with Django credentials via an https request.

See the wiki page [Contribute to Tournesol](https://wiki.tournesol.app/index.php/Contribute_to_Tournesol) for details.

Tested with MediaWiki 1.35.1 and Django 3.0.7. Requires `php-curl`.

### File structure:

* `https_django_auth.php` -- function to authenticate
* `demo_auth.php` -- command-line interface for the function

   Example:
   - `python manage.py changepassword sergei`, enter `eer5ohrees7Eic3`
   - `php demo_auth.php --username sergei --password eer5ohrees7Eic3 --url http://127.0.0.1:8000/api/v2/login_signup/login/`

     Will print "Login successful" and exit with code 0
   - `php demo_auth.php --username sergei --password eer5ohrees7Eic3__ --url http://127.0.0.1:8000/api/v2/login_signup/login/`

     Will print "Login failed: Wrong password" and exit with code 1
* `extention.json` MediaWiki extension config
* `includes` MediaWiki extension code
* `i18n` MediaWiki extension messages

### Installation
1. Install the PluggableAuth plugin: https://www.mediawiki.org/wiki/Extension:PluggableAuth
2. Link this directory to $mediawiki/extensions/AuthHttpsDjango. Make sure the user has the permissions
3. Edit `$mediawiki/LocalSettings.php`:

	```
	// Configuration variables
	$wgAuthHttpsDjangoConfig = array();

	$wgAuthHttpsDjangoConfig['DjangoLoginURL'] = "https://tournesol.app/api/v2/login_signup/login/";

	// enable Django Tournesol Auth
	wfLoadExtension( 'AuthHttpsDjango' );
	wfLoadExtension( 'PluggableAuth' );

	// disabling registration
	$wgGroupPermissions['*']['createaccount'] = true;
	$wgGroupPermissions['*']['autocreateaccount'] = true;

	// can view wiki anonymously
	$wgPluggableAuth_EnableAutoLogin = false;

	// disable built-in auth
	$wgPluggableAuth_EnableLocalLogin = false;

	// disable built-in user attributes
	$wgPluggableAuth_EnableLocalProperties = false;
	```

4. To debug, enable in LocalSettings.php (don't forget to disable in production) and look in /var/log/mediawiki/

	```
	// ENABLE DEBUG
	$wgMainCacheType = CACHE_NONE;
	$wgCacheDirectory = false;
	$wgDebugLogFile = "/var/log/mediawiki/debug-{$wgDBname}.log";

	error_reporting( -1 );
	$wgShowExceptionDetails = true;
	ini_set( 'display_errors', 1 );
	```

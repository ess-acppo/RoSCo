- version info: https://dev-rosco.oztaxa.com/index.php/administrate/setup/ConfigurationCheck/DoCheck
- version info (2018-10-26)
  ```
  Application version:	1.7.6
  Schema revision:	153
  Release type:	RELEASE
  System GUID:	319db01d-04f4-4219-b50b-c8c9539c7966
  Last change log ID:	13211
  ```
  
- logging/debug: /var/www/providence/setup.php
  ```php
  # NOTE: mbohun changed false -> true
  if (!defined('__CA_ENABLE_DEBUG_OUTPUT__')) {
        define('__CA_ENABLE_DEBUG_OUTPUT__', true);
  }
  ```
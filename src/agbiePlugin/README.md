## agbiePlugin for https://collectiveaccess.org/ (1.7.6)
#### intro

```
ubuntu@ip-192-168-1-229:~$ php --version
PHP 7.0.32-0ubuntu0.16.04.1 (cli) ( NTS )
Copyright (c) 1997-2017 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2017 Zend Technologies
    with Zend OPcache v7.0.32-0ubuntu0.16.04.1, Copyright (c) 1999-2017, by Zend Technologies
```    
#### plugin setup/installation
1. Prerequisite
   - The agbiePlugin uses [PHP cURL lib]( https://secure.php.net/manual/en/book.curl.php)
     ```
     sudo apt-get install php-curl
     ```
2. Add new plugin to: `/var/www/providence/app/plugins/`
   - mkdir /var/www/providence/app/plugins/agbie
   - /var/www/providence/app/plugins/agbie/[agbiePlugin.php](https://gist.github.com/mbohun/33cd369e5a1033a31fc65613f79f3e1d#file-agbieplugin-php)
   - /var/www/providence/app/plugins/agbie/conf/[agbie.conf](https://gist.github.com/mbohun/33cd369e5a1033a31fc65613f79f3e1d#file-agbie-conf)
   - sudo chown -R www-data:www-data /var/www/providence/app/plugins/agbie
   - Login and go to the https://dev-rosco.oztaxa.com/index.php/administrate/setup/ConfigurationCheck/DoCheck page, scroll to the bottom of the page to the "Application Plugins" section and check if the agbie plugin is being correctly show in the table of plugins, as shown in the screenshot bellow:
3. Configuration:
   - agbiePlugin.php config options are in /var/www/providence/app/plugins/agbie/conf/[agbie.conf](https://gist.github.com/mbohun/33cd369e5a1033a31fc65613f79f3e1d#file-agbie-conf)
   - currently available options are:
     - `enabled` = 1
     - `agbie_url_rest_api_search` = "https://ag-bie.oztaxa.com/ws/search.json"
4. Logging / monitoring:
   - this implementation of agbiePlugin.php logs in `/var/www/providence/app/log`:
     ```
     /var/www/providence/app/log/log_2018-11-28.txt
     ```

#### plugin functionality/implementation
1. The user creates a new object in the CollectiveAccess' web GUI:
   - enters new object identifier (`idno`), for example: "Drosera indica"
   - and presses the Save button 
2. The installed custom PHP hook [hookSaveItem](https://docs.collectiveaccess.org/wiki/Application_plugins#Editing_.28Providence_editors.29):
   - extracts the new object's identifier (`idno`), ("Drosera indica" in our example)
   - performs a request to the ag-bie's REST API [/ws/search](https://uat-ag-bie.oztaxa.com/ws/search?q=Drosera%20indica) endpoint;
   - from the returned JSON structure parses/extracts values stored in the fields described int the [above CSV table (ag-bie column)](https://gist.github.com/mbohun/33cd369e5a1033a31fc65613f79f3e1d#file-mapping_fields-csv)

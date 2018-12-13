## agbiePlugin for https://collectiveaccess.org/ (1.7.6)
#### Intro

```
ubuntu@ip-192-168-1-229:~$ php --version
PHP 7.0.32-0ubuntu0.16.04.1 (cli) ( NTS )
Copyright (c) 1997-2017 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2017 Zend Technologies
    with Zend OPcache v7.0.32-0ubuntu0.16.04.1, Copyright (c) 1999-2017, by Zend Technologies
```    
#### Plugin setup/installation
##### Summary
Simply clone this git repo, go to this `src/agbiePlugin` subdir and run the [install.sh](./install.sh) script as show in this example bellow (the install.sh script executes the step-s 1 and 2 bellow):
```BASH
~/src$ git clone https://github.com/ess-acppo/RoSCo.git RoSCo.git
~/src$ cd RoSCo.git/src/agbiePlugin/
~/src/RoSCo.git/src/agbiePlugin$ sudo ./install.sh
```

#### Details
1. Prerequisite
   - The agbiePlugin uses [PHP cURL lib]( https://secure.php.net/manual/en/book.curl.php)
     ```
     sudo apt-get install php-curl
     ```
2. Add new plugin to: `/var/www/providence/app/plugins/`
   ```BASH
   cp agbie /var/www/providence/app/plugins
   chown -R www-data:www-data /var/www/providence/app/plugins/agbie
   ```
3. Verify the plugin installation:   
   - Login and go to the https://dev-rosco.oztaxa.com/index.php/administrate/setup/ConfigurationCheck/DoCheck page, scroll to the bottom of the page to the "Application Plugins" section and check if the agbie plugin is being correctly show in the table of plugins, as shown in the screenshot bellow:
     ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/agbiePlugin_install_verification.png "Application Plugins")
4. Configuration:
   - agbiePlugin.php config options are in /var/www/providence/app/plugins/[agbie/conf/agbie.conf](./agbie/conf/agbie.conf)
   - currently available options are:
     - `enabled` = 1
     - `agbie_url_rest_api_search` = "https://uat-ag-bie.oztaxa.com/ws/search.json"
     - `overwrite_mode = 0`
5. Logging / monitoring:
   - this implementation of agbiePlugin.php logs in `/var/www/providence/app/log`:
     ```
     /var/www/providence/app/log/log_2018-11-28.txt
     ```
6. Uninstall
   ```
   sudo rm -rf /var/www/providence/app/plugins/agbie
   ```

### Usage
1. Login to your RoSCo instance
2. From the menu in the right upper corner select `New` -> `Object` -> `Botany (Plants)`
3. Fill in the (required) information:
   1. "Accession Number"
   2. "Preferred labels" (this is the species name, for example: _Bactrocera tryoni_)
   3. ...and press Save (that will trigger the agbiePlugin's hookSaveItem() method, the method extracts the species name you entered in the "Preferred labels", and uses the species name to perform a REST API request to agbie, and using the received agbie values the plugin populates the taxonomy fields (kingdom, genus, order, author, etc.)
4. Go/switch to the "TAXON" tab and you should see the fields populated with taxonomic info received from agbie

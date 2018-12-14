## agbiePlugin for https://collectiveaccess.org/ (1.7.6)

![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/RoSCo_agbiePlugin.png "agbiePlugin architecture")

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
     ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-06_agbiePlugin_install_verification.png "Application Plugins")
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
   ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-00_create_new_object.png "RoSCo create new Object...")
3. Fill in the (required) information:
   1. "Accession Number"
   2. "Preferred labels" (this is the species name, for example: _Bactrocera tryoni_)
   ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-01_create_new_object_save.png "RoSCo create new Object...")
   3. ...and press Save (that will trigger the agbiePlugin's hookSaveItem() method, the method extracts the species name you entered in the "Preferred labels", and uses the species name to perform a REST API request to agbie, and using the received agbie values the plugin populates the taxonomy fields (kingdom, genus, order, author, etc.)   
   ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-02_create_new_object_saved.png "RoSCo create new Object...")
   4. in the log file you should see something like:
   ```
   2018-12-13 15:58:48 - INFO --> agbiePlugin, using ag-bie REST API search endpoint: https://uat-ag-bie.oztaxa.com/ws/search.json
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem START (overwrite_mode=0)
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem: id=49; "Bactrocera tryoni"
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem species name: "Bactrocera tryoni" contains 2 strings.
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem requesting: https://uat-ag-bie.oztaxa.com/ws/search.json?q=%22Bactrocera%20tryoni%22&fq=rank:(species%20OR%20subspecies)
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem curl request returned: curl_errno=0; curl_error=; HTTP_CODE: 200
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem curl request to the agbie REST API returned: {"searchResults":{"totalRecords":1,"facetResults":[],"results":[{"id":"4147e33f-ee6d-433c-93c5-f6e6c520a04f","guid":"55200","linkIdentifier":null,"idxtype":"TAXON","name":"Bactrocera (Bactrocera) tryoni","kingdom":"Animalia","nomenclaturalCode":"ICZN","scientificName":"Bactrocera (Bactrocera) tryoni","scientificNameAuthorship":"(Froggatt, 1897)","author":"(Froggatt, 1897)","nameComplete":"Bactrocera (Bactrocera) tryoni (Froggatt, 1897)","nameFormatted":"<span class=\"scientific-name rank-species\"><span class=\"name\">Bactrocera (Bactrocera) tryoni<\u002fspan> <span class=\"author\">(Froggatt, 1897)<\u002fspan><\u002fspan>","taxonomicStatus":"accepted","nomenclaturalStatus":null,"parentGuid":"103497","rank":"species","rankID":7000,"commonName":"Queensland Fruit Fly, Fruit Fly","commonNameSingle":"Queensland Fruit Fly","occurrenceCount":null,"conservationStatus":null,"infoSourceName":"NAQS","infoSourceURL":"https://collections.ala.org.au/public/show/naqs","subkingdom":"Metazoa","subgenusGuid":"103497","subfamily":"Dacinae","tribeGuid":"112569","suborder":"Brachycera","infraorderGuid":"101303","subkingdomGuid":"106786","infraorder":"Cyclorrhapha (Division)","genus":"Bactrocera","subgenus":"Bactrocera (Bactrocera)","genusGuid":"89111","classGuid":"12","superfamilyGuid":"101280","superclassGuid":"100975","tribe":"Dacini","superclass":"Hexapoda","kingdomGuid":"1","orderGuid":"52944","subfamilyGuid":"103487","order":"Diptera","class":"Insecta","phylum":"Arthropoda","suborderGuid":"101272","family":"Tephritidae","familyGuid":"54820","phylumGuid":"6","superfamily":"Tephritoidea","species":"Bactrocera (Bactrocera) tryoni","speciesGuid":"55200","highlight":"Bactrocera (<b>Bactrocera<\u002fb>) <b>tryoni<\u002fb><br>Bactrocera (<b>Bactrocera<\u002fb>) <b>tryoni<\u002fb> (Froggatt, 1897)"}],"queryTitle":"\"Bactrocera tryoni\""}}
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem json_decode returned: 0; (JSON_ERROR_NONE=0)
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem received: .searchResults.totalRecords=1
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem; g_ui_locale_id=1
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: author="(Froggatt, 1897)" => r_author=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: class="Insecta" => r_class=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: commonNameSingle="Queensland Fruit Fly" => r_common_name=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: family="Tephritidae" => r_family=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: genus="Bactrocera" => r_genus=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: kingdom="Animalia" => r_kingdom=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: order="Diptera" => r_order=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: phylum="Arthropoda" => r_phylum=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: species="Bactrocera (Bactrocera) tryoni" => r_species=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: subfamily="Dacinae" => r_subfamily=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: subgenus="Bactrocera (Bactrocera)" => r_subgenus=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: suborder="Brachycera" => r_suborder=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: subspecies="" => r_subspecies=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy:   subspecies is EMPTY or null => SKIPPING...
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: superfamily="Tephritoidea" => r_superfamily=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem copy: tribe="Dacini" => r_tribe=""
   2018-12-13 15:58:48 - INFO --> agbiePlugin hookSaveItem END
   2018-12-13 15:58:49 - INFO --> agbiePlugin, using ag-bie REST API search endpoint: https://uat-ag-bie.oztaxa.com/ws/search.json   
   ```
4. Go/switch to the "TAXON" tab and you should see the fields populated with taxonomic info received from agbie
   ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-03_new_object_agbie_values.png "RoSCo new Object agbie values...")
5. You can add add or modify any of the fields, and press Save again
   ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-04_object_edit_values.png "RoSCo new Object agbie values...")
6. ...this will again trigger the agbiePlugin's hookSaveItem() method, but the plugin should now recognize/detect existing values (added or changed) and NOT overwrite any of those (the information/values filled in by a human user do have higher priority, and hence won't be overwritten by the plugin; this behaviour is controlled by the `overwrite_mode` config option/property.
   ![Alt text](https://raw.githubusercontent.com/ess-acppo/RoSCo/master/src/agbiePlugin/doc/screenshots/RoSCo-05_object_edited_values_saved.png "RoSCo new Object agbie values...")
   ...and in the log you should see something like:
   ```
   2018-12-13 15:59:49 - INFO --> agbiePlugin, using ag-bie REST API search endpoint: https://uat-ag-bie.oztaxa.com/ws/search.json
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem START (overwrite_mode=0)
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem: id=49; "Bactrocera tryoni"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem species name: "Bactrocera tryoni" contains 2 strings.
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem requesting: https://uat-ag-bie.oztaxa.com/ws/search.json?q=%22Bactrocera%20tryoni%22&fq=rank:(species%20OR%20subspecies)
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem curl request returned: curl_errno=0; curl_error=; HTTP_CODE: 200
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem curl request to the agbie REST API returned: {"searchResults":{"totalRecords":1,"facetResults":[],"results":[{"id":"4147e33f-ee6d-433c-93c5-f6e6c520a04f","guid":"55200","linkIdentifier":null,"idxtype":"TAXON","name":"Bactrocera (Bactrocera) tryoni","kingdom":"Animalia","nomenclaturalCode":"ICZN","scientificName":"Bactrocera (Bactrocera) tryoni","scientificNameAuthorship":"(Froggatt, 1897)","author":"(Froggatt, 1897)","nameComplete":"Bactrocera (Bactrocera) tryoni (Froggatt, 1897)","nameFormatted":"<span class=\"scientific-name rank-species\"><span class=\"name\">Bactrocera (Bactrocera) tryoni<\u002fspan> <span class=\"author\">(Froggatt, 1897)<\u002fspan><\u002fspan>","taxonomicStatus":"accepted","nomenclaturalStatus":null,"parentGuid":"103497","rank":"species","rankID":7000,"commonName":"Queensland Fruit Fly, Fruit Fly","commonNameSingle":"Queensland Fruit Fly","occurrenceCount":null,"conservationStatus":null,"infoSourceName":"NAQS","infoSourceURL":"https://collections.ala.org.au/public/show/naqs","subkingdom":"Metazoa","subgenusGuid":"103497","subfamily":"Dacinae","tribeGuid":"112569","suborder":"Brachycera","infraorderGuid":"101303","subkingdomGuid":"106786","infraorder":"Cyclorrhapha (Division)","genus":"Bactrocera","subgenus":"Bactrocera (Bactrocera)","genusGuid":"89111","classGuid":"12","superfamilyGuid":"101280","superclassGuid":"100975","tribe":"Dacini","superclass":"Hexapoda","kingdomGuid":"1","orderGuid":"52944","subfamilyGuid":"103487","order":"Diptera","class":"Insecta","phylum":"Arthropoda","suborderGuid":"101272","family":"Tephritidae","familyGuid":"54820","phylumGuid":"6","superfamily":"Tephritoidea","species":"Bactrocera (Bactrocera) tryoni","speciesGuid":"55200","highlight":"Bactrocera (<b>Bactrocera<\u002fb>) <b>tryoni<\u002fb><br>Bactrocera (<b>Bactrocera<\u002fb>) <b>tryoni<\u002fb> (Froggatt, 1897)"}],"queryTitle":"\"Bactrocera tryoni\""}}
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem json_decode returned: 0; (JSON_ERROR_NONE=0)
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem received: .searchResults.totalRecords=1
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem; g_ui_locale_id=1
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: author="(Froggatt, 1897)" => r_author="(Froggatt, 1897)"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_author is already set to "(Froggatt, 1897)" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: class="Insecta" => r_class="Insecta"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_class is already set to "Insecta" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: commonNameSingle="Queensland Fruit Fly" => r_common_name="Queensland Fruit Fly"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_common_name is already set to "Queensland Fruit Fly" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: family="Tephritidae" => r_family="Tephritidae"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_family is already set to "Tephritidae" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: genus="Bactrocera" => r_genus="Bactrocera"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_genus is already set to "Bactrocera" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: kingdom="Animalia" => r_kingdom="Animalia"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_kingdom is already set to "Animalia" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: order="Diptera" => r_order="Diptera"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_order is already set to "Diptera" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: phylum="Arthropoda" => r_phylum="Arthropoda"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_phylum is already set to "Arthropoda" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: species="Bactrocera (Bactrocera) tryoni" => r_species="Bactrocera tryoni"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_species is already set to "Bactrocera tryoni" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: subfamily="Dacinae" => r_subfamily="Dacinae"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_subfamily is already set to "Dacinae" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: subgenus="Bactrocera (Bactrocera)" => r_subgenus="Bactrocera"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_subgenus is already set to "Bactrocera" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: suborder="Brachycera" => r_suborder="Brachycera"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_suborder is already set to "Brachycera" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: subspecies="" => r_subspecies=""
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   subspecies is EMPTY or null => SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: superfamily="Tephritoidea" => r_superfamily="Tephritoidea"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_superfamily is already set to "Tephritoidea" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy: tribe="Dacini" => r_tribe="Dacini"
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem copy:   r_tribe is already set to "Dacini" won't overwrite, SKIPPING...
   2018-12-13 15:59:49 - INFO --> agbiePlugin hookSaveItem END
   2018-12-13 15:59:53 - INFO --> agbiePlugin, using ag-bie REST API search endpoint: https://uat-ag-bie.oztaxa.com/ws/search.json
    ```
    

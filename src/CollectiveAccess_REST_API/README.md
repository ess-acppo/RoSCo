## RECENT NOTES (2018-12-14)

#### [ROSCO_env_test.sh](./bin/ROSCO_env_test.sh)
```
mbohun@linux-cr70:~/src/RoSCo.git/src/CollectiveAccess_REST_API/bin> export ROSCO_HOST='dev-rosco.oztaxa.com';
mbohun@linux-cr70:~/src/RoSCo.git/src/CollectiveAccess_REST_API/bin> export ROSCO_USERNAME=test
mbohun@linux-cr70:~/src/RoSCo.git/src/CollectiveAccess_REST_API/bin> export ROSCO_PASSWORD=123
```
```
mbohun@linux-cr70:~/src/RoSCo.git/src/CollectiveAccess_REST_API/bin> ./ROSCO_env_test.sh
ROSCO_AUTH_TOKEN:b652f88633e758e1751e60c269c723e87c88a4099347f030d002e70181b3a9c6

TEST: version:

TEST: rosco_total=5

TEST: list all objects (to show the fields)...

TEST: getting/showing:  https://dev-rosco.oztaxa.com/service.php/item/ca_objects/id/46

TEST: getting/showing:  https://dev-rosco.oztaxa.com/service.php/item/ca_objects/id/47

TEST: getting/showing:  https://dev-rosco.oztaxa.com/service.php/item/ca_objects/id/48

TEST: getting/showing:  https://dev-rosco.oztaxa.com/service.php/item/ca_objects/id/49

TEST: getting/showing:  https://dev-rosco.oztaxa.com/service.php/item/ca_objects/id/50
```
```
mbohun@linux-cr70:~/src/RoSCo.git/src/CollectiveAccess_REST_API/bin> ls -lahF *.json
-rw-r--r-- 1 mbohun users 3.5K Dec 14 14:51 46.json
-rw-r--r-- 1 mbohun users 3.5K Dec 14 14:51 47.json
-rw-r--r-- 1 mbohun users 3.8K Dec 14 14:51 48.json
-rw-r--r-- 1 mbohun users 4.2K Dec 14 14:51 49.json
-rw-r--r-- 1 mbohun users 3.8K Dec 14 14:51 50.json
```

example 50.json:
```JSON
{
  "ok": true,
  "intrinsic_fields": {
    "object_id": "50",
    "type_id": "23",
    "idno": "1972100402",
    "is_deaccessioned": "0",
    "extent": "0",
    "access": "0",
    "status": "0",
    "deleted": "0",
    "rank": "50",
    "acl_inherit_from_ca_collections": "0",
    "acl_inherit_from_parent": "0",
    "access_inherit_from_parent": "0",
    "view_count": "0"
  },
  "preferred_labels": [
    {
      "locale": "en_AU",
      "name": "Bactrocera tryoni"
    }
  ],
  "attributes": {
    "r_phylum": [
      {
        "locale": "none",
        "r_phylum": "Arthropoda"
      }
    ],
    "r_kingdom": [
      {
        "locale": "none",
        "r_kingdom": "Animalia"
      }
    ],
    "r_collector_name": [
      {
        "locale": "en_AU"
      }
    ],
    "r_collection_method": [
      {
        "locale": "none"
      }
    ],
    "r_collection_date": [
      {
        "locale": "none"
      }
    ],
    "r_class": [
      {
        "locale": "none",
        "r_class": "Insecta"
      }
    ],
    "r_order": [
      {
        "locale": "none",
        "r_order": "Diptera"
      }
    ],
    "r_suborder": [
      {
        "locale": "none",
        "r_suborder": "Brachycera"
      }
    ],
    "r_superfamily": [
      {
        "locale": "none",
        "r_superfamily": "Tephritoidea"
      }
    ],
    "r_family": [
      {
        "locale": "none",
        "r_family": "Tephritidae"
      }
    ],
    "r_subfamily": [
      {
        "locale": "none",
        "r_subfamily": "Dacinae"
      }
    ],
    "r_tribe": [
      {
        "locale": "none",
        "r_tribe": "Dacini"
      }
    ],
    "r_genus": [
      {
        "locale": "none",
        "r_genus": "Bactrocera"
      }
    ],
    "r_subgenus": [
      {
        "locale": "none",
        "r_subgenus": "Bactrocera (Bactrocera)"
      }
    ],
    "r_species": [
      {
        "locale": "none",
        "r_species": "Bactrocera (Bactrocera) tryoni"
      }
    ],
    "r_author": [
      {
        "locale": "none",
        "r_author": "(Froggatt, 1897)"
      }
    ],
    "r_common_name": [
      {
        "locale": "none",
        "r_common_name": "Queensland Fruit Fly"
      }
    ],
    "r_speciman_record_entered_by": [
      {
        "locale": "en_AU"
      }
    ],
    "r_acquisition_details": [
      {
        "locale": "en_AU"
      }
    ],
    "r_interception_number": [
      {
        "locale": "en_AU"
      }
    ],
    "r_survey_number": [
      {
        "locale": "en_AU"
      }
    ],
    "r_vial_number_type": [
      {
        "locale": "none"
      }
    ],
    "r_quarantine_entry": [
      {
        "locale": "none"
      }
    ],
    "r_collector_number": [
      {
        "locale": "en_AU"
      }
    ],
    "r_other_number": [
      {
        "locale": "en_AU"
      }
    ],
    "r_gps_coordinates_latitude": [
      {
        "locale": "en_AU"
      }
    ],
    "r_gps_coordinates_longitude": [
      {
        "locale": "en_AU"
      }
    ],
    "r_gda_version": [
      {
        "locale": "en_AU"
      }
    ],
    "r_commodity_goods": [
      {
        "locale": "en_AU"
      }
    ],
    "r_host_scientific_name": [
      {
        "locale": "en_AU"
      }
    ],
    "r_host_common_name": [
      {
        "locale": "en_AU"
      }
    ],
    "r_host_abundance": [
      {
        "locale": "en_AU"
      }
    ],
    "r_symptoms": [
      {
        "locale": "en_AU"
      }
    ],
    "r_number_of_specimens": [
      {
        "locale": "en_AU"
      }
    ],
    "r_port_of_loading": [
      {
        "locale": "en_AU"
      }
    ],
    "r_abundance": [
      {
        "locale": "en_AU"
      }
    ],
    "r_convey_number_voyage": [
      {
        "locale": "en_AU"
      }
    ],
    "r_convey_number_flight_num": [
      {
        "locale": "en_AU"
      }
    ],
    "r_country_of_origin": [
      {
        "locale": "en_AU"
      }
    ],
    "r_country_point_of_entry": [
      {
        "locale": "none"
      }
    ]
  }
}

```

## OLDER NOTES (June/Julty 2018)

### Web Service API (reading & writing data)

#### auth
```BASH
mbohun@linux-khr1:~> curl -s -X GET "http://${username}:${pass}@${host}/service.php/auth/login" | jq
```
```JSON
{
  "ok": true,
  "authToken": "f317dc808b5ab13dae8eba4f565d030fc8cd79b8497285dbabaf9abe2e761c27"
}
```
```BASH
authToken=`curl -s -X GET "http://${username}:${pass}@${host}/service.php/auth/login" | jq -r '.authToken'`
echo "authToken: ${authToken}"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" \
     > /tmp/record.json
```

#### search
```
curl -s "http://${host}/service.php/find/ca_objects?authToken=${authToken}&q=*" | jq '.total'
11

curl -s "http://${host}/service.php/find/ca_objects?authToken=${authToken}&q=*" | jq '.results|length'
11

```
```BASH
curl -s \
     "http://${host}/service.php/find/ca_objects?authToken=${authToken}&q=*" \
     | jq '.results[0]'
```
```JSON
{
  "object_id": "1",
  "id": "1",
  "idno": "Dolichovespula",
  "display_label": "Dolichovespula"
}
```

~TODO: Although we were following the [official docs](https://docs.collectiveaccess.org/wiki/Web_Service_API#Editing_records) and the operations seemingly succeeded, the actual DB fields were not updated:~ **FIXED 2018-06-25**

1. get an existing record you previously created through the web GUI
   ```BASH
   curl -s \
        -X GET \
        "http://${host}/service.php/item/ca_objects/id/25?authToken=${authToken}&pretty=1&format=edit" \
        > /tmp/test_rec_id-25.json
   ```
2. edit/update the fields
   - either use **`"remove_all_attributes": true`**
   - or use **`"remove_attributes": []`** to list **ALL** the fields you want to update/overwrite (instead of duplicate-add-new-value):
     ```JSON
     remove_attributes: [
         "r_author",
         "r_kingdom",
         "r_family",
         "r_genus"
     ]
     ```
   editing JSON [jq setpath()](https://stedolan.github.io/jq/manual/#Builtinoperatorsandfunctions)
   ```
   
   ```
3. write the updated record
   ```BASH
   curl -s \
        -X PUT \
        -d @/tmp/test_rec_id-25_edited.json \
        "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" \
	| jq
   ```
   ```JSON
   
   ```
   **NOTE:** error handling / return value JSON processing /BUG
   ```BASH
   curl -s \
        -X PUT \
	-d @item_request.json \
	"http://${host}/service.php/item/ca_entities?authToken=${authToken}" \
	| jq
   ```
   ```JSON
   {
       "ok": false,
       "errors": [
           "Type must be specified"
       ]
   }
   ```
4. repeat the step 1. above to get and verify the updated record  
   ```
   ```

### References
- https://docs.collectiveaccess.org/wiki/Main_Page
- https://docs.collectiveaccess.org/wiki/Application_plugins
- https://docs.collectiveaccess.org/wiki/API:Getting_Data
- https://docs.collectiveaccess.org/wiki/Web_Service_API#Editing_records (**`"remove_all_attributes": true`**)
- [php wrapper](https://github.com/stefankeidel/ca-service-wrapper)
- https://github.com/CollectiveAccessProject/collectiveaccess-php
- https://docstore.mik.ua/orelly/webprog/pcook/ch18_19.htm

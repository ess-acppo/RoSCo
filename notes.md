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
### Application plugins (for writing/creating data)

```php
public function hookEditItem($pa_params) {
	$item_id = $pa_params['id'];  // The parameter passed to EditItem is a key'ed array of values (see below for details)
	$table_num = $pa_params['table_num'];

	// ... more code here ...
}
```

Uset **get() & set()** to read & write the fields
```php
	$t_object->get('ca_objects.description');
	$t_object->set('idno', 'my_new_idno');
```

### Resources/Notes/Extensions/Libs
- PHP decode JSON received from ag-bie REST API: https://secure.php.net/manual/en/function.json-decode.php
- not sure if this will be used/required with ROSCO; source: https://stackoverflow.com/questions/9589337/rest-client-for-collectiveaccess-api
  ```ruby
  require 'httpclient'
  clnt = HTTPClient.new
  clnt.set_cookie_store('/home/user/cookie.dat')
  puts clnt.get('http://localhost/service.php/iteminfo/ItemInfo/rest?method=auth&username=administrator&password=12345').body
  puts clnt.get('http://localhost/service.php/iteminfo/ItemInfo/rest?method=getLabels&type=ca_objects&item_id=4&mode=all').body
  ```

### References
- https://docs.collectiveaccess.org/wiki/Main_Page
- https://docs.collectiveaccess.org/wiki/Application_plugins
- https://docs.collectiveaccess.org/wiki/API:Getting_Data
- https://docs.collectiveaccess.org/wiki/Web_Service_API#Editing_records (**`"remove_all_attributes": true`**)
- [php wrapper](https://github.com/stefankeidel/ca-service-wrapper)
- https://github.com/CollectiveAccessProject/collectiveaccess-php
- https://docstore.mik.ua/orelly/webprog/pcook/ch18_19.htm
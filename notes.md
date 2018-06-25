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
mbohun@linux-khr1:~> curl -s "http://${username}:${pass}@uat-rosco.oztaxa.com/service.php/find/ca_objects?q=*" | jq '.total'
11

mbohun@linux-khr1:~> curl -s "http://${username}:${pass}@uat-rosco.oztaxa.com/service.php/find/ca_objects?q=*" | jq '.results|length'
11

mbohun@linux-khr1:~> curl -s "http://${username}:${pass}@uat-rosco.oztaxa.com/service.php/find/ca_objects?q=*" | jq '.results[0]'
{
  "object_id": "1",
  "id": "1",
  "idno": "Dolichovespula",
  "display_label": "Dolichovespula"
}
```

~TODO: Although we were following the [official docs](https://docs.collectiveaccess.org/wiki/Web_Service_API#Editing_records) and the operations seemingly succeeded, the actual DB fields were not updated:~ **FIXED 2018-06-25**

1. get an existing record you previously created through the web GUI
   ```
   curl -s \
        -X GET \
        "http://${username}:${pass}@uat-rosco.oztaxa.com/service.php/item/ca_objects/id/25?pretty=1&format=edit" \
        > rosco_test_rec_id-25.json
   ```
2. edit/update the fields
   ```
   ```
   ```
   ```
3. write the updated record
   ```
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

### References
- https://docs.collectiveaccess.org/wiki/Main_Page
- https://docs.collectiveaccess.org/wiki/Application_plugins
- https://docs.collectiveaccess.org/wiki/API:Getting_Data
- [php wrapper](https://github.com/stefankeidel/ca-service-wrapper)
- https://github.com/CollectiveAccessProject/collectiveaccess-php
- https://docstore.mik.ua/orelly/webprog/pcook/ch18_19.htm
### Web Service API (reading data)
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
- https://docstore.mik.ua/orelly/webprog/pcook/ch18_19.htm
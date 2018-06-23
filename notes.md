
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


### References
- https://docs.collectiveaccess.org/wiki/Main_Page
- https://docs.collectiveaccess.org/wiki/Application_plugins
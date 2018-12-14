#!/bin/bash -x

# TODO: mktemp -d

#host=sit-rosco.oztaxa.com
#username=chandra
#pass=chandra
#record_id=25
echo "host: ${host}"
echo "username: ${username}"
echo "record_id: ${record_id}"
echo "payload: ${payload}" 
echo "http_method: ${http_method}"

authToken=`curl -s -X GET "http://${username}:${pass}@${host}/service.php/auth/login" | jq -r '.authToken'`
echo "authToken: ${authToken}"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" | jq '.' > /tmp/get_result_before.json

edit_result="`curl -s -X ${http_method} --header 'Accept: application/json' --header 'Content-Type: application/json'  -d @${payload} "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}"`"
echo "edit_result: ${edit_result}"

echo "8>< ------------ ><8"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" | jq '.' > /tmp/get_result_after.json

diff /tmp/get_result_before.json /tmp/get_result_after.json


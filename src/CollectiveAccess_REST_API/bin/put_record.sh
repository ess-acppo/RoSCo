#!/bin/bash

# TODO: mktemp -d

#host=sit-rosco.oztaxa.com
#username=chandra
#pass=chandra
#record_id=25
echo "host: ${host}"
echo "username: ${username}"
echo "record_id: ${record_id}"
echo "payload: ${payload}" 

authToken=`curl -s -X GET "http://${username}:${pass}@${host}/service.php/auth/login" | jq -r '.authToken'`
echo "authToken: ${authToken}"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" | jq '.' > /tmp/get_result_before.json

curl -s \
     -X PUT \
     -d @${payload} \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" | jq

echo "8>< ------------ ><8"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" | jq '.' > /tmp/get_result_after.json

diff /tmp/get_result_before.json /tmp/get_result_after.json


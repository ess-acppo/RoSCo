#!/bin/bash

# TODO: mktemp -d

#host=sit-rosco.oztaxa.com
#username=chandra
#pass=chandra
#record_id=25
echo "host: ${host}"
echo "username: ${username}"
echo "record_id: ${record_id}"

authToken=`curl -s -X GET "http://${username}:${pass}@${host}/service.php/auth/login" | jq -r '.authToken'`
echo "authToken: ${authToken}"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}&pretty=1&format=edit" \
     > /tmp/record.json

# NOTE: dangerous
cat /tmp/record.json | sed -e 's/Martin/EDITED Martin/g' > /tmp/record_edited.json

curl -s \
     -X PUT \
     -d @/tmp/record_edited.json \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}" | jq

echo "8>< ------------ ><8"

curl -s \
     -X GET \
     "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}"

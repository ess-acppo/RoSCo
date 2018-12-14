#!/usr/bin/env bash

# NOTE: either:
#       a) un-comment and set these variables, or
#       b) simply set and export these variables before running the script
#
# export ROSCO_HOST='dev-rosco.oztaxa.com'
# export ROSCO_USERNAME=yourusername
# export ROSCO_PASSWORD=yourpassword

export ROSCO_AUTH_TOKEN=`curl -s -X GET "https://${ROSCO_USERNAME}:${ROSCO_PASSWORD}@${ROSCO_HOST}/service.php/auth/login" | jq -r '.authToken'`
echo "ROSCO_AUTH_TOKEN:${ROSCO_AUTH_TOKEN}"
echo

rosco_version=`curl -s "https://${ROSCO_HOST}/service.php/version?authToken=${ROSCO_AUTH_TOKEN}"`
echo "TEST: version:${rosco_version}"
echo

# NOTE: List / Get the number of ALL records/objects
rosco_total=`curl -s "https://${ROSCO_HOST}/service.php/find/ca_objects?authToken=${ROSCO_AUTH_TOKEN}&q=*" | jq '.total'`
echo "TEST: rosco_total=${rosco_total}"
echo

# TODO: handle if rosco_total = 0; although unlikely to happen with ROSCO at least in the discussed
#
echo "TEST: list all objects (to show the fields)..."
test_object_ids=`curl -s "https://${ROSCO_HOST}/service.php/find/ca_objects?authToken=${ROSCO_AUTH_TOKEN}&q=*" | jq -r '.results[]|.id'`
echo

# NOTE: This test intentionally uses .results[0] (as opposed to asking for a specific field in case there was a change in field names)
#echo "TEST: get the first object/record: .results[0]; this is to print the actual JSON structure (to see/verify the field names):"
#curl -s "https://${ROSCO_HOST}/service.php/find/ca_objects?authToken=${ROSCO_AUTH_TOKEN}&q=*" | jq '.results[0]'
#echo

# NOTE: 'jq -r' because you want the id *WITHOUT* the enclosing double-quotes; for example: "42" => 42
#echo "TEST: grab the .result[0].id to get/display the full JSON structure, all fields as used by the REST API / scripts / etc."
#test_object_id=`curl -s "https://${ROSCO_HOST}/service.php/find/ca_objects?authToken=${ROSCO_AUTH_TOKEN}&q=*" | jq -r '.results[0].id'`

# NOTE:
for test_object_id in ${test_object_ids}
do
    echo "TEST: getting/showing:  https://${ROSCO_HOST}/service.php/item/ca_objects/id/${test_object_id}"
    test_object_id_json=`curl -s "https://${ROSCO_HOST}/service.php/item/ca_objects/id/${test_object_id}?authToken=${ROSCO_AUTH_TOKEN}&pretty=1&format=edit&lang=en_AU"`
    echo "${test_object_id_json}" > "${test_object_id}.json"
    echo
done

# WARNING: Verify/confirm/clarify the differencies between the diff REST API points (ca_entities vs ca_objects):
#          a) /service.php/item/ca_entities
#             - use this if you are starting with an empty DB (that is most likely NEVER the case with ROSCO)
#          b) /service.php/item/ca_objects
#             - use this if you do have at least one object/record in the DB
#

# Create a test record

# Get / Retrieve test record

# Delete the test record

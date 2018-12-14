#!/bin/bash

function get_record {

    # TODO: - add support for mktemp -d
    #       - add support for token caching
    #       - add support for 
    #
    local host=${1}
    local username=${2}
    local pass=${3}
    local record_id=${4}

    echo "host: ${host}"
    echo "username: ${username}"
    echo "record_id: ${record_id}"

    local authToken=`curl -s -X GET "http://${username}:${pass}@${host}/service.php/auth/login" | jq -r '.authToken'`
    echo "authToken: ${authToken}"

    local record_file="/tmp/get_record-${record_id}.json"
    
    curl -s \
         -X GET \
         "http://${host}/service.php/item/ca_objects/id/${record_id}?authToken=${authToken}&pretty=1&format=edit" > ${record_file}

    echo "record_file: ${record_file}"
}

if [ ${#@} -lt 4 ]; then
    echo "USAGE: $0 [host] [username] [password] [record_id]"
    echo "       EXAMPLE: ${0} 127.0.0.1 myusername secretpass 25"
    echo
    exit 1;
fi

get_record ${1} ${2} ${3} ${4}

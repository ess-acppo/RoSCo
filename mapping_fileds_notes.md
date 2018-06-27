### mapping fields (from ag-bie to collectiveaccess / rosco)

1. The user creates a new object in the CollectiveAccess' web GUI:
   - enters new object identifier (`idno`), for example: "Drosera indica"
   - and presses the Save button 
2. The installed custom PHP hook [hookSaveItem or hookBeforeSaveItem](https://docs.collectiveaccess.org/wiki/Application_plugins#Editing_.28Providence_editors.29):
   - extracts the new object's identifier (`idno`), ("Drosera indica" in our example)
   - performs a request to the ag-bie's REST API [/ws/search](https://uat-ag-bie.oztaxa.com/ws/search?q=Drosera%20indica) endpoint
   - parses/extracts from the returned JSON structure values stored in the fields described int the [above CSV table (ag-bie column)](https://gist.github.com/mbohun/33cd369e5a1033a31fc65613f79f3e1d#file-mapping_fields-csv)  
   *example:*
   ```php
   public function hookEditItem($pa_params) {
           $obj_idno = $pa_params['idno'];

   }
   ```
3. **`TODO:`**

| in the URL string                                       | --data-urlencode |
|:--------------------------------------------------------|:-----------------|
| curl -s "https://ag-bie.oztaxa.com/ws/search?q=${idno}" | curl -s --data-urlencode "q=${idno}" 'https://ag-bie.oztaxa.com/ws/search' |

ag-bie JSON result/payload

```BASH
idno="Drosera indica";
curl -s \
     --data-urlencode "q=${idno}" \
     'https://ag-bie.oztaxa.com/ws/search' \
     | jq -S '.searchResults.results[0]'
```
```JSON
{
  "author": "(Zeller, 1839)",
  "class": "Insecta",
  "classGuid": "12",
  "commonName": "Carob Moth, Locust Bean Moth",
  "commonNameSingle": "Carob Moth",
  "conservationStatus": null,
  "family": "Pyralidae",
  "familyGuid": "52113",
  "genus": "Ectomyelois",
  "genusGuid": "59942",
  "guid": "78388",
  "id": "15be6566-e278-484e-bcd6-8e6e18ab7529",
  "idxtype": "TAXON",
  "infoSourceName": "NAQS",
  "infoSourceURL": "https://collections.ala.org.au/public/show/naqs",
  "infraorder": "Heteroneura",
  "infraorderGuid": "101130",
  "kingdom": "Animalia",
  "kingdomGuid": "1",
  "linkIdentifier": null,
  "name": "Ectomyelois ceratoniae",
  "nameComplete": "Ectomyelois ceratoniae (Zeller, 1839)",
  "nameFormatted": "<span class=\"scientific-name rank-species\"><span class=\"name\">Ectomyelois ceratoniae</span> <span class=\"author\">(Zeller, 1839)</span></span>",
  "nomenclaturalCode": "ICZN",
  "nomenclaturalStatus": null,
  "occurrenceCount": null,
  "order": "Lepidoptera",
  "orderGuid": "52112",
  "parentGuid": "59942",
  "phylum": "Arthropoda",
  "phylumGuid": "6",
  "rank": "species",
  "rankID": 7000,
  "scientificName": "Ectomyelois ceratoniae",
  "scientificNameAuthorship": "(Zeller, 1839)",
  "species": "Ectomyelois ceratoniae",
  "speciesGuid": "78388",
  "subfamily": "Phycitinae",
  "subfamilyGuid": "104055",
  "subkingdom": "Metazoa",
  "subkingdomGuid": "106786",
  "suborder": "Glossata",
  "suborderGuid": "101129",
  "superclass": "Hexapoda",
  "superclassGuid": "100975",
  "superfamily": "Pyraloidea",
  "superfamilyGuid": "101141",
  "taxonomicStatus": "accepted"
}
```

```BASH
curl -s \
    'https://ag-bie.oztaxa.com/ws/search?q=Drosera%20indica' \
    | jq '.searchResults.results[0] | { "attributes": { "r_kingdom": [ { "locale": "en_US", "r_kingdom": .kingdom } ] } }'
```
```JSON
{
  "attributes": {
    "r_kingdom": [
      {
        "locale": "en_US",
        "r_kingdom": "Plantae"
      }
    ]
  }
}
```

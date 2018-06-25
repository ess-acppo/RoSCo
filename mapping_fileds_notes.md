### mapping fields (from ag-bie to collectiveaccess / rosco)

ag-bie JSON result/payload
```BASH
curl -s 'https://ag-bie.oztaxa.com/ws/search?q=Dolichovespula' | jq -S '.searchResults.results[0]'
```
```JSON
{
  "author": "Rohwer, 1916",
  "class": "Insecta",
  "classGuid": "12",
  "commonName": "",
  "commonNameSingle": "",
  "conservationStatus": null,
  "family": "Vespidae",
  "familyGuid": "80761",
  "genus": "Dolichovespula",
  "genusGuid": "79566",
  "guid": "79566",
  "highlight": "<b>Dolichovespula</b> Rohwer, 1916<br><b>Dolichovespula</b>",
  "id": "c17be645-bfe0-4f69-abf9-e8b60fc4ebd1",
  "idxtype": "TAXON",
  "infoSourceName": "NAQS",
  "infoSourceURL": "https://collections.ala.org.au/public/show/naqs",
  "kingdom": "Animalia",
  "kingdomGuid": "1",
  "linkIdentifier": "Dolichovespula",
  "name": "Dolichovespula",
  "nameComplete": "Dolichovespula Rohwer, 1916",
  "nameFormatted": "<span class=\"scientific-name rank-genus\"><span class=\"name\">Dolichovespula</span> <span class=\"author\">Rohwer, 1916</span></span>",
  "nomenclaturalCode": "ICZN",
  "nomenclaturalStatus": null,
  "occurrenceCount": null,
  "order": "Hymenoptera",
  "orderGuid": "52196",
  "parentGuid": "103289",
  "phylum": "Arthropoda",
  "phylumGuid": "6",
  "rank": "genus",
  "rankID": 6000,
  "scientificName": "Dolichovespula",
  "scientificNameAuthorship": "Rohwer, 1916",
  "subfamily": "Vespinae",
  "subfamilyGuid": "103289",
  "subkingdom": "Metazoa",
  "subkingdomGuid": "106786",
  "suborder": "Apocrita",
  "suborderGuid": "101196",
  "superclass": "Hexapoda",
  "superclassGuid": "100975",
  "superfamily": "Vespoidea",
  "superfamilyGuid": "101201",
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

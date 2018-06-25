### mapping fields (from ag-bie to collectiveaccess / rosco)

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

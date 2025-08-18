# com_vmmapicon

This repository provides a Joomla component (`com_vmmapicon`) and a YOOtheme Pro plugin
that expose remote API data as GraphQL sources in the YOOtheme builder.

## Single API Query with Arguments

You can now query a single API endpoint and pass any of the API field names as input
arguments. For example:

```graphql
query {
  api(externalId: "12345", city: "Berlin") {
    id
    title
    street
    city
    descriptionNote
  }
}
```

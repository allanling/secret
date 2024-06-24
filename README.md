## Version-controlled key-value store API

-   Base url => `http://secret.ap-southeast-1.elasticbeanstalk.com/api/`
-   I have made the assumption that value will always be in JSON format and when retrieved, it will output as JSON object.
-   CI done through Github Action and CD done through AWS CodePipeline.
-   You can check build status with tests in github action.

---

<details>
 <summary><code>POST</code> <code><b>/object</b></code> <code>(Store new key/value pair)</code></summary>

##### Parameters

> | name | type     | data type     | description                   |
> | ---- | -------- | ------------- | ----------------------------- |
> | None | required | object (JSON) | A key(string) and value(JSON) |

##### Responses

```json
{
    "data": {
        "message": "Successfully created store."
    }
}
```

##### Example cURL

> ```javascript
>  curl -X POST -H "Content-Type: application/json" --data '{"mykey": "{\"foo\": 123}"}' http://secret.ap-southeast-1.elasticbeanstalk.com/api/object/
> ```

</details>

<details>
 <summary><code>GET</code> <code><b>/object/{mykey}</b></code> <code>(Get latest value of a key)</code></summary>

##### Responses

```json
{
    "data": {
        "key": "assumenda",
        "value": {
            "odio": "sed"
        },
        "value_created_at": "2024-06-21T19:35:14.000000Z"
    }
}
```

##### Example cURL

> ```javascript
>  curl -X GET -H "Content-Type: application/json" http://secret.ap-southeast-1.elasticbeanstalk.com/api/object/mykey
> ```

</details>

<details>
 <summary><code>GET</code> <code><b>/object/{mykey}?timestamp={timestamp}</b></code> <code>(Get value of a key according to the timestamp)</code></summary>

##### Parameters

> | name        | type     | data type | description       |
> | ----------- | -------- | --------- | ----------------- |
> | `timestamp` | optional | numeric   | timestamp in UNIX |

##### Responses

```json
{
    "data": {
        "key": "assumenda",
        "value": {
            "odio": "sed"
        },
        "value_created_at": "2024-06-21T19:35:14.000000Z"
    }
}
```

##### Example cURL

> ```javascript
>  curl -X GET -H "Content-Type: application/json" http://secret.ap-southeast-1.elasticbeanstalk.com/api/object/mykey?timestamp=1719160418
> ```

</details>

<details>
 <summary><code>GET</code> <code><b>/object/get_all_records</b></code> <code>(Get all keys and their values currently stored in the DB)</code></summary>

##### Parameters

> | name   | type     | data type | description                                                             |
> | ------ | -------- | --------- | ----------------------------------------------------------------------- |
> | `page` | optional | numeric   | Only 10 max items will be listed, use `page` to change the current page |

##### Responses

```json
{
    "data": [
        {
            "key": "assumenda",
            "values": [
                {
                    "value": {
                        "enim": "eos"
                    },
                    "created_at": "2024-06-22T06:01:08.000000Z"
                },
                {
                    "value": {
                        "odio": "sed"
                    },
                    "created_at": "2024-06-21T19:35:14.000000Z"
                }
            ]
        },
        {
            "key": "mykey",
            "values": [
                {
                    "value": {
                        "foo": 123
                    },
                    "created_at": "2024-06-23T16:33:38.000000Z"
                }
            ]
        }
    ],
    "links": {
        "first": "http://secret.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records?page=1",
        "last": null,
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "path": "http://secret.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records",
        "per_page": 10,
        "to": 2
    }
}
```

##### Example cURL

> ```javascript
>  curl -X GET -H "Content-Type: application/json" http://secret.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records
> ```

</details>

### Sonarcloud Quality Check

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=allanling_secret&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=allanling_secret)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=allanling_secret&metric=coverage)](https://sonarcloud.io/summary/new_code?id=allanling_secret)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=allanling_secret&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=allanling_secret)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=allanling_secret&metric=bugs)](https://sonarcloud.io/summary/new_code?id=allanling_secret)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=allanling_secret&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=allanling_secret)

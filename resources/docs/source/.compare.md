---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/involvvely-backend/public/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_a925a8d22b3615f12fca79456d286859 -->
## api/auth/login
> Example request:

```bash
curl -X POST \
    "http://localhost/involvvely-backend/public/api/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/auth/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/auth/login`


<!-- END_a925a8d22b3615f12fca79456d286859 -->

<!-- START_839ac5664a0a49c2d02a4f181a9d0aba -->
## api/auth/manage-users
> Example request:

```bash
curl -X GET \
    -G "http://localhost/involvvely-backend/public/api/auth/manage-users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/auth/manage-users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/auth/manage-users`

`POST api/auth/manage-users`

`PUT api/auth/manage-users`

`PATCH api/auth/manage-users`

`DELETE api/auth/manage-users`

`OPTIONS api/auth/manage-users`


<!-- END_839ac5664a0a49c2d02a4f181a9d0aba -->

<!-- START_9512bdc37dd9ba4836b3b3371f0fbc93 -->
## api/auth/fetch-user/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost/involvvely-backend/public/api/auth/fetch-user/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/auth/fetch-user/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/auth/fetch-user/{id}`

`POST api/auth/fetch-user/{id}`

`PUT api/auth/fetch-user/{id}`

`PATCH api/auth/fetch-user/{id}`

`DELETE api/auth/fetch-user/{id}`

`OPTIONS api/auth/fetch-user/{id}`


<!-- END_9512bdc37dd9ba4836b3b3371f0fbc93 -->

<!-- START_00bdcee6fad99d5c2fa26ff893c3a9f3 -->
## api/auth/update-profile
> Example request:

```bash
curl -X POST \
    "http://localhost/involvvely-backend/public/api/auth/update-profile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/auth/update-profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/auth/update-profile`


<!-- END_00bdcee6fad99d5c2fa26ff893c3a9f3 -->

<!-- START_ab698ac4339d8f6987fbacebbe8d2d5f -->
## api/auth/delete-user/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost/involvvely-backend/public/api/auth/delete-user/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/auth/delete-user/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": 0
}
```

### HTTP Request
`GET api/auth/delete-user/{id}`


<!-- END_ab698ac4339d8f6987fbacebbe8d2d5f -->

<!-- START_8c0e48cd8efa861b308fc45872ff0837 -->
## login

> Example request:

```bash
curl -X POST \
    "http://localhost/involvvely-backend/public/api/v1/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/v1/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/login`


<!-- END_8c0e48cd8efa861b308fc45872ff0837 -->

<!-- START_82cc61d4b6f89c83d3c0565899c2f14d -->
## api/v1/signup_student
> Example request:

```bash
curl -X POST \
    "http://localhost/involvvely-backend/public/api/v1/signup_student" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/v1/signup_student"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/signup_student`


<!-- END_82cc61d4b6f89c83d3c0565899c2f14d -->

<!-- START_922295ba193468c98d97215fea47aed7 -->
## api/v1/get_cities
> Example request:

```bash
curl -X POST \
    "http://localhost/involvvely-backend/public/api/v1/get_cities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/v1/get_cities"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/get_cities`


<!-- END_922295ba193468c98d97215fea47aed7 -->

<!-- START_b57bf29dacbb8267088d5fd0b20a650c -->
## api/v1/list_states
> Example request:

```bash
curl -X GET \
    -G "http://localhost/involvvely-backend/public/api/v1/list_states" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/v1/list_states"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "error": false,
    "data": [
        {
            "id": 1,
            "state_code": "AL",
            "state_name": "Alabama",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 2,
            "state_code": "AK",
            "state_name": "Alaska",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 3,
            "state_code": "AZ",
            "state_name": "Arizona",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 4,
            "state_code": "AR",
            "state_name": "Arkansas",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 5,
            "state_code": "CA",
            "state_name": "California",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 6,
            "state_code": "CO",
            "state_name": "Colorado",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 7,
            "state_code": "CT",
            "state_name": "Connecticut",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 8,
            "state_code": "DE",
            "state_name": "Delaware",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 9,
            "state_code": "DC",
            "state_name": "District of Columbia",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 10,
            "state_code": "FL",
            "state_name": "Florida",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 11,
            "state_code": "GA",
            "state_name": "Georgia",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 12,
            "state_code": "HI",
            "state_name": "Hawaii",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 13,
            "state_code": "ID",
            "state_name": "Idaho",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 14,
            "state_code": "IL",
            "state_name": "Illinois",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 15,
            "state_code": "IN",
            "state_name": "Indiana",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 16,
            "state_code": "IA",
            "state_name": "Iowa",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 17,
            "state_code": "KS",
            "state_name": "Kansas",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 18,
            "state_code": "KY",
            "state_name": "Kentucky",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 19,
            "state_code": "LA",
            "state_name": "Louisiana",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 20,
            "state_code": "ME",
            "state_name": "Maine",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 21,
            "state_code": "MD",
            "state_name": "Maryland",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 22,
            "state_code": "MA",
            "state_name": "Massachusetts",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 23,
            "state_code": "MI",
            "state_name": "Michigan",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 24,
            "state_code": "MN",
            "state_name": "Minnesota",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 25,
            "state_code": "MS",
            "state_name": "Mississippi",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 26,
            "state_code": "MO",
            "state_name": "Missouri",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 27,
            "state_code": "MT",
            "state_name": "Montana",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 28,
            "state_code": "NE",
            "state_name": "Nebraska",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 29,
            "state_code": "NV",
            "state_name": "Nevada",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 30,
            "state_code": "NH",
            "state_name": "New Hampshire",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 31,
            "state_code": "NJ",
            "state_name": "New Jersey",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 32,
            "state_code": "NM",
            "state_name": "New Mexico",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 33,
            "state_code": "NY",
            "state_name": "New York",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 34,
            "state_code": "NC",
            "state_name": "North Carolina",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 35,
            "state_code": "ND",
            "state_name": "North Dakota",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 36,
            "state_code": "OH",
            "state_name": "Ohio",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 37,
            "state_code": "OK",
            "state_name": "Oklahoma",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 38,
            "state_code": "OR",
            "state_name": "Oregon",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 39,
            "state_code": "PA",
            "state_name": "Pennsylvania",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 40,
            "state_code": "PR",
            "state_name": "Puerto Rico",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 41,
            "state_code": "RI",
            "state_name": "Rhode Island",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 42,
            "state_code": "SC",
            "state_name": "South Carolina",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 43,
            "state_code": "SD",
            "state_name": "South Dakota",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 44,
            "state_code": "TN",
            "state_name": "Tennessee",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 45,
            "state_code": "TX",
            "state_name": "Texas",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 46,
            "state_code": "UT",
            "state_name": "Utah",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 47,
            "state_code": "VT",
            "state_name": "Vermont",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 48,
            "state_code": "VA",
            "state_name": "Virginia",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 49,
            "state_code": "WA",
            "state_name": "Washington",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 50,
            "state_code": "WV",
            "state_name": "West Virginia",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 51,
            "state_code": "WI",
            "state_name": "Wisconsin",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 52,
            "state_code": "WY",
            "state_name": "Wyoming",
            "created_at": null,
            "updated_at": null
        }
    ]
}
```

### HTTP Request
`GET api/v1/list_states`


<!-- END_b57bf29dacbb8267088d5fd0b20a650c -->

<!-- START_363c67ba0efee74d743f6dd26e43e498 -->
## api/v1/list_schools
> Example request:

```bash
curl -X GET \
    -G "http://localhost/involvvely-backend/public/api/v1/list_schools" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/api/v1/list_schools"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "error": false,
    "data": [
        {
            "id": 1,
            "school_name": "R.S public school",
            "approved": 0,
            "created_at": null,
            "updated_at": null
        }
    ]
}
```

### HTTP Request
`GET api/v1/list_schools`


<!-- END_363c67ba0efee74d743f6dd26e43e498 -->

<!-- START_2ecd2c34871333ab0f1e6108147335fc -->
## {any}
> Example request:

```bash
curl -X GET \
    -G "http://localhost/involvvely-backend/public/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/involvvely-backend/public/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET {any}`


<!-- END_2ecd2c34871333ab0f1e6108147335fc -->



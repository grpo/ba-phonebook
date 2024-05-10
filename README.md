# SYMFONY PHONEBOOK API

### Requirements
- [x] Phonebook entries available to registered users (private)

- [x] A phonebook entry can be shared with another user (and unshared)
- [x] Phonebook entry: name, phone {full CRUD)

- [x] There must be at least one unit test

- [x] There must be at least one feature test

- [x] The application must use Docker

- [ ] Optional: Any additional ideas or suggestions of yours are not necessary
but are considered a big plus.

  
## Setup
1. pull repo
2. cd into project dir
3. ```shell
    docker compose build --no-cache &&\
    docker compose up -d
   ```
4. ```shell
    bin/console doctrine:database:create &&\
    bin/console doctrine:schema:create &&\
    bin/console --env=test doctrine:database:create &&\
    bin/console --env=test doctrine:schema:create
    ```
5. ```shell
    bin/console d:m:m &&\
    bin/console d:f:l &&\
    bin/console --env=test d:m:m &&\
    bin/console --env=test d:f:l
    ```
   
## Endpoints
>[ !IMPORTANT ] All protected endpoints must be accessed with header
> HTTP_Authorize => 'Bearer XXXXXX'
### PUBLIC

#### /api/login
```json
{
    "username": "user7",
    "password": "secret123"
}
```
---

### PROTECTED


#### /api/v1/contact
- Actions: ['GET','POST']
- POST BODY:
```json
{
    "username": "user7",
    "password": "secret123"
}
```
---

#### /api/v1/contact/{id} 
- Actions: ['GET','PUT', 'DELETE']
- POST/PUT BODY:
```json
{
    "username": "user7",
    "password": "secret123"
}
```
---

#### /api/v1/contact/share
- Actions: ['POST']
- POST BODY:
```json
{
  "user_id": "ddcef2ca-5939-4d71-b50c-b2a50bac27de",
  "contact_id": "86e31ea3-74e1-4430-8e74-d39c57324e29"
}
```
---

#### /api/v1/contact/unshare
- Actions: ['POST']
- POST BODY:
```json
{
  "user_id": "ddcef2ca-5939-4d71-b50c-b2a50bac27de",
  "contact_id": "86e31ea3-74e1-4430-8e74-d39c57324e29"
}
```
---


## Running tests
```shell
    bin/phpunit 
```
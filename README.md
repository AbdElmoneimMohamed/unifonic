# Unifonic PHP Assignment

# HLD
### We're Consuming two apis for campaigns

``` postman collection exist in ./data/Campaigns.postman_collection.json ```

#### 1.Campaigns list  `/campaigns`
```
GET http://localhost/campaigns

fetching the campaigns from database and return them as json format
```

#### 2.Create a new Campaign `/campaigns/store`
```
POST http://localhost/campaigns/store

where take two parameters in `form-data` 
message  => type text
contacts => type file (contacts.csv)
```


1. receiving campaign message and contents csv file then prepare the file and convert it to json format
   1. removing the duplicate contacts and empty phone_number Contacts


2. saving the campaign message and Contacts json into campaigns db table.
   1. saving the contents as a json column


3. Dispatch SMS Notification Queue for sending the campaign to recipients (async)
   1. in the SMS Notification handler i chunk the contacts to chunks (999 elements) and send them through bulk api.
   2. the notified recipients saved in redis cache and compare them with the new campaigns recipients to prevent sending them a sms on this day again.



    
## Local Setup

just run ``make local-setup`` 
```
 => build the docker images
 => run migrations
 => start queue
 ```

## Usage

| Command            | Meaning                 |
|--------------------|-------------------------|
| `make local-setup` | setup local environment |
| `make build`       | build docker            |
| `make start`       | start docker            |
| `make migrate`     | run migration           |
| `make ecs`         | run cs-fixer            |
| `make php-stan`    | run php-stan            |
| `make queue`       | run queue               |


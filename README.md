# akmb
Queue and send messages thought messageBird API

# requirements
 - docker
 - docker-compose
 - make (optional)

# Configuration
Is mandatory to configure messageBird API token also the originator from the message. The configuration can be
made into the file `config/config.php`. Replace the `MESSAGE_BIRD_API_TOKEN` with the real token and put a custom `originator`.

Also is possible to configure a custom host and port from redis if you want.

```
$config = [
    'messageBird' => [
        'token' => 'MESSAGE_BIRD_API_TOKEN',
        'originator' => 'Adir Kuhn'
    ],

    'redis' => [
        'scheme' => 'tcp',
        'host' => 'akmb.redis.local',
        'port' => '6379',
        'user' => '',
        'password' => ''
    ]
];
```


# running

Project runs into docker container.
There is also a Makefile to make simple to setup the environment.

## With make:

Build containers:
```
make build
```

Bring containers up:
```
make up
```

Cleaning the containers:
```
make clean
```

## With docker-compose

Build containers:
```
docker-compose build app
```

Bring containers up:
```
docker-compose up -d app
docker-compose exec -T app php ./bin/composer.phar install
```

## Extra

Running PSR2 checking:
```
make psr2
```

Running unit tests:
```
make unit-tests
```

Getting web container logs:
```
make logs
```

PHP static analyser:
```
make analyser
```

# Endpoints

# test endpoint / (root)
```
http://localhost:8888/
```

Should return
```
status	"success"
data	"Main:index"
```

# sending SMS endpoint (POST only)
```
URL: http://localhost:8888/sms/send
Method allowed: POST
params:

 - destination: mobile number (msisdn)
 - message: message to be sent
 
Empty values are not allowed
```

Request sample:
```
curl -X POST "http://localhost:8888/sms/send" -d "destination=31641111111&message=welcome"
```

All the messages are stored into Redis queue, to process the queue and send we need to run
```
make send-messages
```

# TODO
 - Better support to GSM7 and Unico
 - Save sent messages into database
 - Add retry option getting failed messages from database

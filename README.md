# Dynamicus

### Requirements
* PHP >= 7.1
* Imagick => 6.0

## Manual Preparing
1. Copy `.env` file from `.env.develop`
2. Add values to parameters `GOOGLE_API_KEY, GOOGLE_API_CX`. If you want to search images on google
3. Install composer's dependencies

### Start server
`composer serve`

### Debugging
`export XDEBUG_CONFIG="remote_enable=1 remote_mode=req remote_port=9000 remote_host=127.0.0.1 remote_connect_back=0"`

## Preparing with docker
1. `docker-compose up`
2. `docker exec -it php composer install`
3.  Copy `.env` file from `.env.develop`
4. Add values to parameters `GOOGLE_API_KEY, GOOGLE_API_CX` to file `.env`. If you want to search images on google
5. Open `http://localhost:80`

## List
`GET /list/{entity}/{entity_id}`

Example: /list/translation/34

Response [200]:
```json
{
  "data": {
    "type": "list/translation",
    "id": "34",
     "attributes": [],
     "links": {
        "original": "/images/translation/000/000/034/34.jpg",
        "default": {
          "60x60": "/images/translation/000/000/034/34_60x60.jpg",
          "50x50": "/images/translation/000/000/034/34_default_50x50.png"
        },
        "svg": {
           "60x200": "/images/translation/000/000/034/34_svg_60x200.svg"
        },
        "self": "api/list/translation/34"
     }
  }
}
 ```


## Delete
`DELETE /{entity}/{entity_id}`

Response [204]

## Create
`POST /{entity}/{entity_id}`
or create with specific namespace
`POST /{entity:namespace}/{entity_id}` example `/meta_info:og/34/`

Request:

```json
{
   "data": {
     "resize": [
       {
         "size": "240x168",
         "crop": "100x100x580x436"
       },
       {
         "size": "200x150",
         "crop": "100x100x1100x850"
       }
     ],
     "links": {
       "url": "https://static.pexels.com/photos/126407/pexels-photo-126407.jpeg"
     }
   }
 }
 ```
 `data.resize.0.size` the image size.
 `data.resize.0.crop` crop points from top-left.
 
 If an element `resize` does not exist. The image will crop for all sizes from config.

Response [201]

```json
{
  "data": {
    "id": 1,
    "links": {
      "original": "https://...\/dynamicus\/blog-post\/000\/000\/001\/1.jpg",
      "default": {
        "300x285": "https://...\/dynamicus\/blog-post\/000\/000\/001\/1_default_300x285.jpg"
      }
    }
  }
}
```

## Create content images
`POST /{entity}:content/{entity_id}` example `/blog_post:content/34/`

It does not create original image and does not return this image in list.
 
Request: like [Create](#create)

Response [201]
```json
{
  "data": {
    "id": 1,
    "links": {
      "content": {
        "700x445": "https://...\/dynamicus\/blog-post\/000\/000\/001\/1522913218_content_700x445.jpg"
      }
    }
  }
}
```

## Upload
An image upload use `multipart/form-data`

`POST /post/{entity}/{entity_id}` example `/post/blog-post/1`

Request
```
Content-Type: multipart/form-data; boundary=boundary
Accept: application/vnd.api+json

--boundary
Content-Disposition: form-data; name="image"; filename="image.jpg"

...image's content...
--boundary
Content-Disposition: form-data; name="json"

{"data":{"resize":[{"size":"300x190","crop":"0x0x300x190"}]}}
--boundary--
```

Response [201]

```json
{
  "data": {
    "id": 1,
    "links": {
      "original": "https://...\/dynamicus\/blog-post\/000\/000\/001\/1.jpg",
      "default": {
        "300x190": "https://...\/dynamicus\/blog-post\/000\/000\/001\/1_default_300x190.jpg"
      }
    }
  }
}
```

## Upload SVG

Need add to config `TYPE_SVG => true`

`POST /upload/user:svg/1`

Request
```
Content-Type: multipart/form-data; boundary=boundary
Accept: application/vnd.api+json

--boundary
Content-Disposition: form-data; name="image"; filename="image.svg"

...image's content...
--boundary--
```

Response [201]

```json
{
  "data": {
    "id": 1,
    "links": {
      "svg": "https://.../dynamicus/user/000/000/001/1.svg"
    }
  }
}
```

## Search
`GET /search/{urlencode(search_text)}`

Response [200]:
```json
{
  "data": {
    "id": null,
    "links": [
      "https:\/\/cdn.pixabay.com\/...",
      "http:\/\/www.citizensh...",
      "http:\/\/www.cbcdundalk.o...",
      "https:\/\/my-personality-te...",
      "https:\/\/cdn.pixabay.com\/ph...",
      "https:\/\/www.av-test.org\/filea...",
      "http:\/\/www.toptieradmissions.com...",
      "https:\/\/my-personality-test.com...",
      "http:\/\/mandevilleprimary.edu.jm\/wp...",
      "https:\/\/cdn.psychologytoday.com\/site..."
    ]
  },
  "meta": {
    "engine": "Google Search"
  }
}
```
A string will write to log `Dynamicus: Request to Image API for {search query}`

# Audio

Audio files can be only `.mp3`
Need redis for working with audio.

## List

`GET /audio/{entity}/{entity_id}`

### List with file info

`GET /audio/{entity}/{entity_id}/with-info` example `GET /audio/blog-post/1/with-info`

Response [200]
```json
{
  "data": {
    "id": 1,
    "links": [
      "https:\/\/...\/dynamicus\/blog-post\/000\/000\/001\/943364a76a06c7057ea847e993ac6a34.mp3"
    ],
    "info": [
      {
        "type": "file",
        "path": "blog-post\/000\/000\/001\/943364a76a06c7057ea847e993ac6a34.mp3",
        "timestamp": 1531388148,
        "size": 2385
      }
    ]
  }
}
```

## Create

`POST /audio/{entity}/{entity_id}` example `/audio/word/1`

Request:

```json
{
  "data": {
    "message": "<speak>my phone is</speak>"
  }
}
```

Response [201]
```json
{
  "data": {
    "id": 1,
    "links": [
      "https://.../dynamicus/word/000/000/001/1cd083fa859fccefca7b3b7a2517c909.mp3"
    ]
  }
}
```

## Upload audio file

`POST /audio/upload/{entity}/{entity_id}` example `/audio/upload/blog-post/1`

Request:

```
Content-Type: multipart/form-data; boundary=boundary
Accept: application/vnd.api+json

--boundary
Content-Disposition: form-data; name="audio"; filename="audio.mp3"

< ../../data/audio.mp3
--boundary
Content-Disposition: form-data; name="json"

{"data":{"message":"<speak>arm</speak>"}}
--boundary--
```

Response [201]

```json
{
  "data": {
    "id": 1,
    "links": [
      "https://.../dynamicus/blog-post/000/000/001/943364a76a06c7057ea847e993ac6a34.mp3"
    ]
  }
}
```

## Regenerate audio

Old audios will remove from entity and a new audio generate

`PATCH /audio/regenerate/blog-post/1`

Request:

```json
{
  "data": {
    "message": "<speak>my phone is</speak>"
  }
}
```

Response [201]
```json
{
  "data": {
    "id": 1,
    "links": [
      "https://.../dynamicus/word/000/000/001/1cd083fa859fccefca7b3b7a2517c909.mp3"
    ]
  }
}
```

## TestLog
`GET /test-log/{type}`

`http 127.0.0.1:8889/test-log/div-zero` -> 400
`http 127.0.0.1:8889/test-log/ob-clean` -> 204
`http 127.0.0.1:8889/test-log/object` -> 400
`http 127.0.0.1:8889/test-log/file` -> 500
`http 127.0.0.1:8889/test-log/log` -> 204 (тестирование логов)
`http 127.0.0.1:8889/test-log/exception` -> 400
`http 127.0.0.1:8889/test-log/` -> 204


``http 127.0.0.1:8889/test-log/exception``

Response [400]
```json
{
    "errors": {
        "code": "0",
        "file": "/var/www/stv2/src/Dynamicus/Action/TestLogAction.php:73",
        "id": "unknown",
        "source": {
            "parameter": "",
            "pointer": "/test-log/exception"
        },
        "status": "400",
        "title": "Exception: throw testing"
    }
}
```

# Storage Adapters
* Redis
* RQLite
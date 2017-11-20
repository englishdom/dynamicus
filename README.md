# Dynamicus

### Requirements
* PHP >= 7.1
* Imagick => 6.0

### Start server
`php -S localhost:8888 -t public public/index.php`

### Debugging
`export XDEBUG_CONFIG="remote_enable=1 remote_mode=req remote_port=9000 remote_host=127.0.0.1 remote_connect_back=0"`

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

Response: 204

## Create
`POST /{entity}/{entity_id}`

Request:

```json
{
   "data": {
     "resize": [
       { /* Первый имидж для кропа и ресайза */
         "size": "240x168", /* Размер необходимого имиджа */
         "crop": "100x100x580x436"
          /* 344x122 - верхняя левая точка отсчета для кропа */
          /* 542x378 - нижняя правая точка отсчета для кропа */
       },
       { /* Второй имидж для кропа и ресайза */
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
 Если не будет прислан массив "resize", тогда будут кропнуты имиджи всех размеров из конфига
 для указаной ентити.

Response: 204

## Generate
`POST /generate/{entity}/{entity_id}/{urlencode(search_text)}`

Получает 1 имидж из гугла по поисковой фразе и резайзит для указанной entity

Response [204]

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
В лог пишется строка `Dynamicus: Request to Image API for {search query}`
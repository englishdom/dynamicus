# Dynamicus

### Start server
`php -S localhost:8888 -t public public/index.php`

### Debugging
`export XDEBUG_CONFIG="remote_enable=1 remote_mode=req remote_port=9000 remote_host=127.0.0.1 remote_connect_back=0"`

## List
`GET /list/{entity}/{entity_id}`

Example: /list/translation/34

Response:
```
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

```
{
   "data": {
     "resize": [
       { # Первый имидж для кропа и ресайза
         "size": "240x168", #Размер необходимого имиджа
         "crop": "100x100x580x436"
          #344x122 - верхняя левая точка отсчета для кропа
          #542x378 - нижняя правая точка отсчета для кропа
       },
       { # Второй имидж для кропа и ресайза
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

Response: 204
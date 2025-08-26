# ventas
Sistema Sistema Multiventas


### Configuracion Subdominios entorno local

1. Editar el archivo: 

Subdominios en XAMPP localhost en Windows (funciona también en macOS o Linux con pequeñas variaciones):
```
C:\Windows\System32\drivers\etc\hosts
```
Se incluye el subdominio: 
``` 
127.0.0.1 tiendas.local
```

### Creacion usuario de prueba
```
curl -X POST -H 'Authorization: Bearer $ACCESS_TOKEN' -H "Content-type: application/json" -d 
'{
   	"site_id":"MLM"
}' 
'https://api.mercadolibre.com/users/test_user'
```

Respuesta:

Usuario VENTAS

```
{
    "id": 2645087980,
    "email": "test_user_662287727@testuser.com",
    "nickname": "TESTUSER662287727",
    "site_status": "active",
    "password": "DDHvivOTD0"
}
```

Ususrio COMPRAS
```
{
    "id": 2645647478,
    "email": "test_user_1724382882@testuser.com",
    "nickname": "TESTUSER1724382882",
    "site_status": "active",
    "password": "LeTY74Iu3i"
}
```


### Publicar un articulo de prueba

```
curl -X POST -H 'Authorization: Bearer $ACCESS_TOKEN' -d
{
  "title":"Item de test - No Ofertar",
  "category_id":"MLA3530",
  "price":350,
  "currency_id":"ARS",
  "available_quantity":10,
  "buying_mode":"buy_it_now",
  "condition":"new",
  "listing_type_id":"gold_special",
  "sale_terms":[
     {
        "id":"WARRANTY_TYPE",
        "value_name":"Garantía del vendedor"
     },
     {
        "id":"WARRANTY_TIME",
        "value_name":"90 días"
     }
  ],
  "pictures":[
     {
        "source":"http://mla-s2-p.mlstatic.com/968521-MLA20805195516_072016-O.jpg"
     }
  ],
  "attributes":[
     {
        "id":"BRAND",
        "value_name":"Marca del producto"
     },
     {
        "id":"EAN",
        "value_name":"7898095297749"
     }
  ]
}
https://api.mercadolibre.com/items

```

### Proceso creacion y ronevacion de token:

1. Ingresa sin login a:

https://auth.mercadolibre.com.mx/authorization?response_type=code&client_id=7626391564892909&redirect_uri=https://development.fragolan.com/sistemas/


Al solicitar el login ingresar con usuario test antes obtenido: 

```
{
    "id": 2645087980,
    "email": "test_user_662287727@testuser.com",
    "nickname": "TESTUSER662287727",
    "site_status": "active",
    "password": "DDHvivOTD0"
}
```

En la ruta del navegador se ontendra una respuesta como esta con un codigo al final:

```
https://development.fragolan.com/sistemas/?code=TG-68ace1f2a6cec700013cddb9-2645087980
```

2. Intercambiar el codigo por access code, en postman ingresar: 
curl -X POST https://api.mercadolibre.com/oauth/token \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=authorization_code' \
  -d 'client_id=7626391564892909' \
  -d 'client_secret=95FMvTcbv0d8y515xHHrtAGkxpglFYye' \
  -d 'code=TG-68ace1f2a6cec700013cddb9-2645087980' \
  -d 'redirect_uri=https://development.fragolan.com/sistemas/'

La respuesta sera: 

```
{
    "access_token": "APP_USR-7626391564892909-082518-9c481f19cfcb86dea0ca7c560b475e31-2645087980",
    "token_type": "Bearer",
    "expires_in": 21600,
    "scope": "offline_access read urn:ml:mktp:ads:/read-only write",
    "user_id": 2645087980,
    "refresh_token": "TG-68ace23d32b63600016b4fe5-2645087980"
}

```

3. Renovacion de token

Ingresar en postman: 

```
curl -X POST https://api.mercadolibre.com/oauth/token \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=refresh_token' \
  -d 'client_id=7626391564892909' \
  -d 'client_secret=95FMvTcbv0d8y515xHHrtAGkxpglFYye' \
  -d 'refresh_token=TU_REFRESH_TOKEN'

```

Respuesta: 

```
{
    "access_token": "APP_USR-7626391564892909-082518-7a738888e029a51ae0b56b959a9cb75f-2645087980",
    "token_type": "Bearer",
    "expires_in": 21600,
    "scope": "offline_access read urn:ml:mktp:ads:/read-only write",
    "user_id": 2645087980,
    "refresh_token": "TG-68ace60732b63600016b7764-2645087980"

}

```


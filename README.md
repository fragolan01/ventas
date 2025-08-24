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

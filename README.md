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


### Esquema Base de datos

```

/* 1. Canales */
CREATE TABLE `canales` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del canal',
  `nombre` VARCHAR(50) NOT NULL COMMENT 'Nombre del canal (por ejemplo: Mercado Libre, Amazon, etc.)',
  `descripcion` TEXT DEFAULT NULL COMMENT 'Descripción del canal',
  `logo_url` TEXT DEFAULT NULL COMMENT 'URL del logotipo del canal',
  `api_base_url` TEXT DEFAULT NULL COMMENT 'URL base de la API del canal',
  `activo` BOOLEAN DEFAULT TRUE COMMENT 'Indica si el canal está activo o inactivo',


  PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci
  COMMENT='Tabla para registrar los canales disponibles en la plataforma (e.g. Mercado Libre, Amazon)';



/* 2. Tiendas */
CREATE TABLE `tiendas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la tienda',
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre de la tienda o cuenta',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal asociado (clave foránea)',
 
  `acceso_token` TEXT COMMENT 'Token de acceso a la API del canal',
  `refresca_token` TEXT COMMENT 'Token de actualización del acceso',
  `expira_token` DATETIME COMMENT 'Fecha y hora de expiración del token',
  `creado` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',

  PRIMARY KEY (`id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci
  COMMENT='Tabla para registrar tiendas o cuentas conectadas por canal (por ejemplo, cuentas de Mercado Libre)';



/* 3. Monedas */
CREATE TABLE `monedas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la moneda',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `moneda` VARCHAR(255) NOT NULL COMMENT 'Nombre o código de la moneda (ej. USD, MXN)',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar las monedas utilizadas en la plataforma';



/* 4. Envios */
CREATE TABLE `envios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de envíos',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `nombre_envio` VARCHAR(255) COMMENT 'Nombre del envío',
  `costo` DECIMAL(10,2) COMMENT 'Costo del envío',
  `moneda_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador de la moneda (clave foránea)',

  PRIMARY KEY (`id`),
  FOREIGN KEY (`moneda_id`) REFERENCES `monedas` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar los envíos';



/* 5. Proveedores */
CREATE TABLE `proveedores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del proveedor',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `nombre_proveedor` VARCHAR(255) COMMENT 'Nombre o descripción del proveedor',
  `creado` timestamp NOT NULL DEFAULT current_timestamp() 
  ON UPDATE current_timestamp() COMMENT 'Fecha y hora actual TC',
 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar los proveedores';



/* 6. Marcas */
CREATE TABLE `marcas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la marca',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `proveedor_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del proveedor (clave foránea)',
  `nombre_marca` VARCHAR(250) COMMENT 'Nombre o descripción de la marca',

  PRIMARY KEY (`id`),
  FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar las marcas';



/* 7. Modelos (Syscom) */
CREATE TABLE `modelos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del modelo',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `modelo` VARCHAR(100) COMMENT 'Modelo de producto',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para registrar los modelos de productos';



/* 8. Tipos Publicación (Meli) */
CREATE TABLE `tipos_publicaciones` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo de publicación',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `site_id` VARCHAR(255) DEFAULT 'MLM' COMMENT 'Identificador del sitio (ej. MLM)',

  `tipo_publi_id` VARCHAR(255) NOT NULL COMMENT 'Identificador del tipo de publicación asociado al sitio',
  `name` VARCHAR(250) COMMENT 'Nombre del tipo de publicación',

  `canal_id` INT UNSIGNED NOT NULL COMMENT 'Identificador del canal (clave foránea)',
  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Identificador de la tienda (clave foránea)',

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_tipo_publi` (`tipo_publi_id`),

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE

) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar los tipos de publicación por tienda';



/* 9. Atributos (ML)*/
CREATE TABLE `atributos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del atributo',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `item_id` VARCHAR(50) NOT NULL COMMENT 'ID del producto/publicación en la plataforma',
  `title` VARCHAR(255) DEFAULT NULL COMMENT 'Título del producto o publicación',
  `family_name` VARCHAR(20) DEFAULT NULL COMMENT 'Familia del producto (clasificación interna)',
  `seller_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del vendedor en la plataforma',
  `category_id` VARCHAR(20) DEFAULT NULL COMMENT 'ID de categoría de la plataforma',
  `price` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Precio del producto',
  `currency_id` VARCHAR(20) DEFAULT NULL COMMENT 'Moneda usada en la publicación',
  `listing_type_id` VARCHAR(20) DEFAULT NULL COMMENT 'Tipo de publicación (ej. gold_pro)',
  `condition` VARCHAR(20) DEFAULT NULL COMMENT 'Condición del producto (nuevo, usado)',
  `permalink` TEXT COMMENT 'URL de la publicación',
  `status` VARCHAR(10) DEFAULT NULL COMMENT 'Estado de la publicación',
  
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',
  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Identificador de la tienda (clave foránea)',

  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_item_id_tienda` (`item_id`, `tienda_id`),
  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci
  COMMENT='Tabla para almacenar los atributos completos de las publicaciones por tienda';



/* 10. Total ITEMS Meli (API)*/
CREATE TABLE `total_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del ITEM',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `item_id` VARCHAR(30)  COMMENT 'Identificador del ítem en Meli',

  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',
  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Identificador de la tienda (clave foránea)',

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_item_id` (`item_id`) COMMENT 'Llave única para publicaciones Meli', 

  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE

) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar los ítems publicados en Meli';



/* 12.  syscom */
CREATE TABLE `syscom` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del ITEM',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `producto_id` INT UNSIGNED NOT NULL COMMENT 'ID producto syscom',
  `modelo` VARCHAR(50)  COMMENT 'El modelo clasificado por Syscom',
  `total_existencia` INT UNSIGNED COMMENT 'existencias por producto',
  `titulo` TEXT COMMENT 'Descripcion del producto por syscom',
  `marca` VARCHAR(250)  COMMENT 'Marca correspondiente al producto Fk tabla marca ',
  `imagen` TEXT  COMMENT 'imagen del producto',
  `link_privado` TEXT  COMMENT 'link de la publicacion en SYSCOM',
  `descripcion` TEXT  COMMENT 'Detalles adicionales al producto',
  `caracteristicas` TEXT  COMMENT 'Caracteristicas del producto',
  `imagens` TEXT  COMMENT 'Una imagen del producto',
  `peso` DECIMAL(10,2)  COMMENT 'Peso del producto',
  `alto` DECIMAL(10,2)  COMMENT 'altura del producto',
  `largo` DECIMAL(10,2)  COMMENT 'Largo del producto',
  `ancho` DECIMAL(10,2)  COMMENT 'Ancho del producto',

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_producto_id_tienda` (`producto_id`, `tienda_id`),
  UNIQUE KEY `uk_producto_id` (`producto_id`),

  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_producto_id` (`producto_id`) COMMENT 'Llave única para el ID producto syscom'
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='La tabla contiene todos los productos que vende fragolan de syscom';

/*  Esta tabla no se crea*/
/* 13. total prod syscom */
CREATE TABLE `total_prod_syscom` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del ITEM',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `producto_id` INT UNSIGNED NOT NULL COMMENT 'ID producto syscom',

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_producto_syscom` (`producto_id`) COMMENT 'Llave única para el ID SYSCOM'
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='La tabla contiene todos los productos que vende fragolan de syscom';



/* 14. precios syscom */
CREATE TABLE `precios_syscom` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha y hora actual TC',

  `producto_id` INT UNSIGNED COMMENT 'ID syscom de la tabla syscom',
  `precio1` DECIMAL(10,2) ,
  `precio_especial` DECIMAL(10,2) ,
  `precio_descuento` DECIMAL(10,2) ,
  `precio_lista` DECIMAL(10,2) ,

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_item_id_tienda` (`producto_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`producto_id`) REFERENCES `syscom` (`producto_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;



/* 15. categorias syscom */
CREATE TABLE `categorias_syscom` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `producto_id` INT UNSIGNED COMMENT 'ID syscom de la tabla syscom',
  `categorias_id` int UNSIGNED,
  `nombre` VARCHAR(250) ,
  `nivel` INT UNSIGNED ,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`producto_id`) REFERENCES `syscom` (`producto_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;



/* 16  inventario mini API*/
CREATE TABLE `inventario_mini` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `producto_id` INT UNSIGNED COMMENT 'Id productos Syscom FK',
  `inv_min` INT UNSIGNED COMMENT 'Inventario minimo por producto',

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_producto_id_tienda` (`producto_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`producto_id`) REFERENCES `syscom` (`producto_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;




/* 17. packs */
CREATE TABLE `packs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `producto_id` INT UNSIGNED COMMENT 'ID syscom de la tabla syscom',
  `item_id` VARCHAR(30)  COMMENT 'Identificador del ítem en Meli',
  `num_piezas` int UNSIGNED,

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_producto_id_tienda` (`item_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;




/* 18 comisiones (ML)*/
CREATE TABLE `comisiones` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
    
  `item_id` VARCHAR(50) NOT NULL,
  `percentage_fee` DECIMAL(10, 2) DEFAULT NULL,

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_item_id_tienda` (`item_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
 
  PRIMARY KEY (`id`),       -- Se usa `id` como clave primaria para el auto_increment
  UNIQUE KEY (`item_id`)    -- Se asegura que `item_id` sea único
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;



  /* 19. tipo cambio (Syscom)*/
CREATE TABLE `tipo_de_cambio` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha y hora actual TC',

  `normal` decimal(10,2)  COMMENT 'tipo de cambio normal en Syscom',
  `preferencial` decimal(10,0) ,
  `un_dia` decimal(10,0) ,
  `una_semana` decimal(10,0) ,
  `dos_semanas` decimal(10,0) ,
  `tres_semanas` decimal(10,0) ,
  `un_mes` decimal(10,0) ,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Almacena el TC diario';


/* Aqui */
/* 20. stock */
CREATE TABLE `stock` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `categoria_id` INT UNSIGNED COMMENT 'Llave foránea a la tabla arbol_de_categorias',
  `proveedores_id` INT UNSIGNED COMMENT 'Llave foránea a la tabla proveedores',
  `marcas_id` INT UNSIGNED COMMENT 'Llave foránea a la tabla marcas',
  `envios_id` INT UNSIGNED COMMENT 'Llave foránea a la tabla envios',
  `item_id` VARCHAR(255) COMMENT 'Llave foránea a la tabla total_items',
  `producto_id` INT UNSIGNED COMMENT 'Llave foránea a la tabla syscom',
  `inv_min_id` INT UNSIGNED  COMMENT 'Inventario mínimo del producto para publicar en Meli',
  `modelo_id` INT UNSIGNED  COMMENT 'ID Modelo del producto en Syscom',
  `pack_id` INT UNSIGNED  COMMENT 'Id del paquete de prodisctos',
  `orden` INT UNSIGNED  COMMENT 'Orden para el reporte',
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha y hora de captura',
  `fijo_plataforma` TEXT  COMMENT 'URL interna del vendedor',
  `url_proveedor_1` TEXT  COMMENT 'URL 1 del proveedor',
  `url_proveedor_2` TEXT  COMMENT 'URL 2 del proveedor',
  `url_proveedor_3` TEXT  COMMENT 'URL 3 del proveedor',
  `url_proveedor_4` TEXT  COMMENT 'URL 4 del proveedor',
  `observaciones` TEXT  COMMENT 'Observaciones sobre el producto',

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_item_id_tienda` (`item_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,


  PRIMARY KEY (`id`),
  FOREIGN KEY (`proveedores_id`) REFERENCES `proveedores` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`marcas_id`) REFERENCES `marcas` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`envios_id`) REFERENCES `envios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `total_items` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `syscom` (`producto_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='Tabla para almacenar información del stock de productos.';




/* 21. result campania */
CREATE TABLE `result_campania` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `id_dominio` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `campaign_id` INT UNSIGNED NOT NULL COMMENT 'ID campania',
  `nombre_campania` VARCHAR(250) COMMENT 'Nombre de la campania',
  `status` VARCHAR(255) COMMENT 'Estado de la campania ON/OFF',
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de ultima modificacion',
  `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creacion',
  `channel` VARCHAR(255)  COMMENT 'imagen del producto',
  `acos` DECIMAL(10,2) NOT NULL COMMENT 'El costo de la campania',
  `limit` INT UNSIGNED DEFAULT 250 COMMENT 'limite del numero de resultados',
  `date_from` DATE NOT NULL COMMENT 'Fecha inicio para obtener el reporte de campanias',
  `date_to` DATE NOT NULL COMMENT 'Fecha final para obtener el reporte de campanias',


  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_item_id_tienda` (`campaign_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_campaign_id` (`campaign_id`) COMMENT 'Llave única para el ID campania'

) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='La tabala que contiene todas las campanias con un rango de fecha';




/* 22. anuncios meli */
CREATE TABLE `anuncio_meli` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `dominio_id` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  
  `item_id` VARCHAR(255)  COMMENT 'Identificador del ítem en Meli (Llave foranea)', 
  `campaign_id` INT UNSIGNED  COMMENT 'Id Campania (Llave foranea)',

  `price` DECIMAL(10,2)  COMMENT 'Precio venta del producto',
  `title` TEXT  COMMENT 'Descripcion del producto',
  `status` VARCHAR(255)  COMMENT 'Estado de la publicacion',
  `domain_id` VARCHAR(255)  COMMENT 'Dominio de la publicacion',
  `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creacion del publicacion',
  `channel` VARCHAR(255)  COMMENT 'Canal de publicacion',
  `brand_value_id` INT UNSIGNED  COMMENT 'Valor de la marca ID',
  `brand_value_name` VARCHAR(250)  COMMENT 'Nombre de la publicidad',
  `current_level` VARCHAR(255)  COMMENT 'nivel actual de la publicidad',
  `permalink` TEXT  COMMENT 'Link del anuncio',

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_item_id_tienda` (`campaign_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`item_id`) REFERENCES `total_items` (`item_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  
  FOREIGN KEY (`campaign_id`) REFERENCES `result_campania` (`campaign_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='La tabala el anuncio correspondiente por id publicacion Meli';





/* 23. metric campania */
CREATE TABLE `metric_campania` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `id_dominio` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',
  `campaign_id` INT UNSIGNED  COMMENT 'ID campania (Llave foranea)',
 
  `clicks` INT UNSIGNED  COMMENT 'Numero clicks de la campania',
  `prints` INT UNSIGNED  COMMENT 'Impreciones de campania',
  `cost` DECIMAL(10,2)  COMMENT 'Costo de campania',
  `cpc` DECIMAL(10,2)  COMMENT 'Costo CPC campania',
  `ctr` DECIMAL(10,2)  COMMENT 'Costo CTR campania',
  `direct_amount` DECIMAL(10,2)  COMMENT 'Monto directo campania',
  `indirect_amount` DECIMAL(10,2)  COMMENT 'Monto indirecto campania',
  `total_amount` DECIMAL(10,2)  COMMENT 'Total de montos',
  `direct_units_quantity` INT UNSIGNED NOT NULL COMMENT 'total Unidades directas ',
  `indirect_units_quantity` INT UNSIGNED NOT NULL COMMENT 'total Unidades indirectas ',
  `direct_items_quantity` INT UNSIGNED NOT NULL COMMENT 'cantidad_artículos_directos',

  `tienda_id` INT UNSIGNED NOT NULL COMMENT 'Tienda a la que pertenece el tipo de publicación',
  `canal_id` INT UNSIGNED DEFAULT NULL COMMENT 'Identificador del canal (clave foránea)',

  FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  UNIQUE KEY `uk_item_id_tienda` (`campaign_id`, `tienda_id`),
  FOREIGN KEY (`canal_id`) REFERENCES `canales` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE,


  PRIMARY KEY (`id`),
  FOREIGN KEY (`campaign_id`) REFERENCES `result_campania` (`campaign_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci 
  COMMENT='La tabala que contiene todas las campanias con un rango de fecha';



/*  variables*/
CREATE TABLE `variables` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `id_dominio` INT UNSIGNED DEFAULT 9999 COMMENT 'Identificador del dominio asociado',

  `iva` DECIMAL(10,2) DEFAULT 0.16 COMMENT 'iva',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT 
  CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci


/* Productos */
CREATE TABLE IF NOT EXISTS productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  
  -- Datos de venta
  title VARCHAR(255) NOT NULL,
  category_id VARCHAR(50) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  currency_id VARCHAR(3) NOT NULL DEFAULT 'MXN',
  available_quantity INT NOT NULL DEFAULT 1,
  buying_mode ENUM('buy_it_now','auction') DEFAULT 'buy_it_now',
  conditions ENUM('new','used') DEFAULT 'new',
  listing_type_id VARCHAR(50) NOT NULL DEFAULT 'gold_special',

  -- Garantía
  warranty_type VARCHAR(255) DEFAULT 'Garantía del vendedor',
  warranty_time VARCHAR(255) DEFAULT '12 meses',

  -- Multimedia
  pictures JSON NOT NULL,                         -- array de URLs
  description TEXT,                               -- descripción en texto plano
  
  -- Atributos del producto
  attributes JSON,                                -- array con id/value_name (MODEL, BRAND, EAN, etc.)
  product_id VARCHAR(50) DEFAULT NULL,            -- referencia al catálogo de ML (en prod puede llenarse)

  -- Envío
  shipping_mode VARCHAR(10) DEFAULT 'me2',
  shipping_local_pickup TINYINT(1) DEFAULT 0,
  shipping_free TINYINT(1) DEFAULT 1,

  -- Estado del flujo de publicación
  status ENUM('pending','processing','published','error') DEFAULT 'pending',
  item_id VARCHAR(50) DEFAULT NULL,               -- id devuelto por ML (ej: MLM123456789)
  error_message TEXT,                             -- si falla guardar mensaje

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

```

/* Trigger  actualiza_tc */

DELIMITER $$

CREATE TRIGGER actualiza_tc
AFTER INSERT ON tipo_de_cambio
FOR EACH ROW
BEGIN
	UPDATE proveedores SET tc = NEW.normal WHERE id=3;
END$$
    

/* Listar los triggers CREADOS en DB */
USE NOMBRE_BD
SHOW TRIGGERS;

/* listar el triggrer especifico */
SHOW CREATE TRIGGER actualiza_tC

/* Mostrar todos los triggers */
/*  */
SELECT TRIGGER_NAME, EVENT_MANIPULATION, ACTION_TIMING, EVENT_OBJECT_TABLE
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE();

/* Para ver el cuerpo del trigger */
SELECT ACTION_STATEMENT
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_NAME = 'actualiza_tc';


/* 

| Qué hacer                        | Comando                                                                                       |
| -------------------------------- | --------------------------------------------------------------------------------------------- |
| Verificar base actual            | `SELECT DATABASE();`                                                                          |
| Ver todos los triggers           | `SHOW TRIGGERS;`                                                                              |
| Ver código (forma segura)        | `SHOW CREATE TRIGGER actualiza_tc;`                                                           |
| Ver código vertical (en consola) | `SHOW CREATE TRIGGER actualiza_tc\G` *(sin `;`)*                                              |
| Ver solo el cuerpo               | `SELECT ACTION_STATEMENT FROM INFORMATION_SCHEMA.TRIGGERS WHERE TRIGGER_NAME='actualiza_tc';` |


 */

/* Ver la version exacta de Mysql */
SELECT VERSION();

/* Cuerpo del trigger */
SELECT ACTION_TIMING, EVENT_MANIPULATION, EVENT_OBJECT_TABLE, ACTION_STATEMENT
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_NAME = 'actualiza_tc';


/* Al neceistar hacer modificaciones */

DROP TRIGGER actualiza_tc;
-- (haz los cambios necesarios)
CREATE TRIGGER actualiza_tc ...;








/* Ejemplo practico completo */

-- Ver triggers en la base
SHOW TRIGGERS;

-- Ver código completo
SHOW CREATE TRIGGER actualiza_tc;

-- Ver solo el cuerpo (por si SHOW CREATE no funciona)
SELECT ACTION_STATEMENT
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_NAME = 'actualiza_tc';

-- Eliminar (si quieres reemplazarlo)
DROP TRIGGER actualiza_tc;

-- Volver a crear con cambios
CREATE TRIGGER actualiza_tc
AFTER UPDATE ON tipo_de_cambio
FOR EACH ROW
BEGIN
    UPDATE proveedores
    SET tc = NEW.normal
    WHERE id = 3;
END;



/* Listar todos los triggers del codigo */

SELECT 
    TRIGGER_NAME AS nombre_trigger,
    EVENT_MANIPULATION AS evento,
    ACTION_TIMING AS momento,
    EVENT_OBJECT_TABLE AS tabla,
    ACTION_STATEMENT AS codigo,
    DEFINER,
    CREATED
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE();


/* Exportar todos los trigeers en linux */
SELECT 
    TRIGGER_NAME, EVENT_MANIPULATION, ACTION_TIMING, EVENT_OBJECT_TABLE, ACTION_STATEMENT
INTO OUTFILE '/tmp/listado_triggers.csv'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE();



Cómo lo haría en un equipo profesional


Mantener los triggers versionados en archivos .sql (por ejemplo, en Git).

Nombrar los triggers claramente, ejemplo:
ventas_before_insert_log, clientes_after_update_sync.

Documentar cada trigger en una tabla interna o wiki con:

Nombre

Tabla asociada

Evento (INSERT, UPDATE, DELETE)

Descripción funcional (“Actualiza campo tc de proveedores con tipo de cambio actual”



/* Consulta maestra: listado completo de triggers, procedimientos y funciones
 */

 -- Cambia "nombre_de_tu_base" por el nombre real de tu BD
USE nombre_de_tu_base;

-- ===========================
-- 🔹 LISTAR TODOS LOS TRIGGERS
-- ===========================
SELECT 
    'TRIGGER' AS tipo_objeto,
    TRIGGER_NAME AS nombre,
    EVENT_MANIPULATION AS evento,
    ACTION_TIMING AS momento,
    EVENT_OBJECT_TABLE AS tabla,
    CREATED AS fecha_creacion,
    ACTION_STATEMENT AS codigo
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE()

UNION ALL

-- ===========================
-- 🔹 LISTAR TODOS LOS PROCEDIMIENTOS
-- ===========================
SELECT 
    'PROCEDURE' AS tipo_objeto,
    ROUTINE_NAME AS nombre,
    NULL AS evento,
    NULL AS momento,
    NULL AS tabla,
    CREATED AS fecha_creacion,
    ROUTINE_DEFINITION AS codigo
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = DATABASE()
AND ROUTINE_TYPE = 'PROCEDURE'

UNION ALL

-- ===========================
-- 🔹 LISTAR TODAS LAS FUNCIONES
-- ===========================
SELECT 
    'FUNCTION' AS tipo_objeto,
    ROUTINE_NAME AS nombre,
    NULL AS evento,
    NULL AS momento,
    NULL AS tabla,
    CREATED AS fecha_creacion,
    ROUTINE_DEFINITION AS codigo
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = DATABASE()
AND ROUTINE_TYPE = 'FUNCTION';


/* guardar el anterior script como una vista permanente */

CREATE OR REPLACE VIEW reporte_objetos_bd AS
-- (pega aquí el SELECT completo anterior)
;



/* Cualquier persona puede consultarlo de esta manera */

SELECT * FROM reporte_objetos_bd;


/* Cómo exportar la base completa con triggers y procedimientos */
por defecto, mysqldump sí incluye triggers, pero no siempre los procedimientos ni funciones, a menos que se usen las opciones adecuadas.


/* Para respaldar todos los elementos completos */
mysqldump -u root -p \
  --routines \
  --triggers \
  --events \
  --databases nombre_de_tu_base \
  > respaldo_completo.sql

/* Establecer estandar de nombres */
trg_tabla_evento_timing.sql     → triggers
sp_nombre_funcion.sql           → procedimientos
fn_nombre_funcion.sql           → funciones


/* Crea una tabla interna de auditoría, por ejemplo: */

CREATE TABLE control_objetos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20),
    nombre VARCHAR(100),
    tabla_asociada VARCHAR(100),
    ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    creado_por VARCHAR(100),
    descripcion TEXT
);

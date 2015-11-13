use proyecto;

DROP TABLE IF EXISTS GRUPO3TEMP;

CREATE TABLE GRUPO3_TEMP(
	id_establecimiento INT,
	nombre VARCHAR(255),
	coordenadas VARCHAR(255),
	tipo_establecimiento VARCHAR(255),
	establecimiento_oficial VARCHAR(4),
	servicio_establecimiento VARCHAR(255),
	descripcion_servicio VARCHAR(255),
	calificacion INT,
	comentario VARCHAR(255)
);

LOAD DATA LOCAL INFILE 'Grupo3.xlsx' 
INTO TABLE GRUPO3_TEMP 
FIELDS TERMINATED BY ';' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

INSERT INTO TIPO_ESTABLECIMIENTO (nombre)
SELECT DISTINCT tipo_establecimiento
FROM GRUPO3_TEMP
WHERE tipo_establecimiento NOT IN (SELECT nombre FROM TIPO_ESTABLECIMIENTO)
;

INSERT INTO ESTABLECIMIENTO (nombre, posicion, tipo_establecimiento, oficial, usuario)
SELECT DISTINCT g3.nombre, g3.coordenadas, t.tipo_establecimiento, if(g3.establecimiento_oficial like 'Si', 1, 0), 4
FROM GRUPO3_TEMP g3, TIPO_ESTABLECIMIENTO t
WHERE g3.tipo_establecimiento like t.nombre
;

INSERT INTO TIPO_SERVICIO (nombre, descripcion)
SELECT DISTINCT servicio_establecimiento, descripcion_servicio
FROM GRUPO3_TEMP
WHERE servicio_establecimiento NOT IN (SELECT nombre FROM TIPO_SERVICIO)
;

INSERT INTO SERVICIO (cupo, establecimiento, tipo_servicio, oficial, no_oficial)
SELECT 5, e.establecimiento, t.tipo_servicio, 1, 0
FROM GRUPO3_TEMP g3, ESTABLECIMIENTO e, TIPO_SERVICIO t
WHERE g3.tipo_establecimiento like t.nombre
	AND g3.nombre like e.nombre
;

INSERT INTO CALIFICACION (punteo, comentario, usuario, servicio)
SELECT  g3.calificacion, g3.comentario, 4, s.servicio
FROM GRUPO3_TEMP g3, SERVICIO s, TIPO_SERVICIO t
WHERE g3.servicio_establecimiento like t.nombre
	AND t.tipo_servicio = s.tipo_servicio
;
use proyecto;

DROP TABLE IF EXISTS GRUPO6_TEMP;

CREATE TABLE GRUPO6_TEMP(
	establecimiento INT,
	telefono_establecimiento VARCHAR(255),
	direccion_establecimiento VARCHAR(255),
	nombre_establecimiento VARCHAR(255),
	latitud_establecimiento VARCHAR(255),
	longitud_establecimiento VARCHAR(255),
	username_user_creador VARCHAR(255),
	telefono_user_creador VARCHAR(255),
	correo_user_creador VARCHAR(255),
	password_user_creador VARCHAR(255),
	nombre_user_creador VARCHAR(255),
	tipo_user_creador VARCHAR(255),
	nombre_dimension VARCHAR(255),
	nombre_atributo VARCHAR(255),
	descripcion_atributo VARCHAR(255),
	nombre_servicio VARCHAR(255),
	descripcion_servicio VARCHAR(255),
	nombre_r_c VARCHAR(255),
	servicio_trc VARCHAR(255),
	establecimiento_trc VARCHAR(255),
	valor_rc INT,
	comentario_rc VARCHAR(255),
	inicio_rc VARCHAR(255),
	final_rc VARCHAR(255),
	username_user_trc VARCHAR(255),
	telefono_user_trc VARCHAR(255),
	correo_user_trc VARCHAR(255),
	password_user_trc VARCHAR(255),
	nombre_user_trc VARCHAR(255),
	tipo_user_trc VARCHAR(255)
);

LOAD DATA LOCAL INFILE '/home/pruebas/Bases2/salida_grupo6.csv'
INTO TABLE GRUPO6_TEMP
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
;

INSERT INTO TIPO_ESTABLECIMIENTO (nombre)
VALUES ('Establecimiento del grupo 6')
;

INSERT INTO USUARIO(usuario, nombre, apellido, rol, password)
SELECT DISTINCT username_user_creador, nombre_user_creador, 'G6', 2, password_user_creador
FROM GRUPO6_TEMP
;

INSERT INTO USUARIO(usuario, nombre, apellido, rol, password)
SELECT DISTINCT username_user_trc, nombre_user_trc, 'G6', 2, password_user_trc
FROM GRUPO6_TEMP
WHERE username_user_trc NOT IN (SELECT usuario FROM USUARIO)
;

INSERT INTO ESTABLECIMIENTO (nombre, posicion, tipo_establecimiento, oficial, usuario)
SELECT g6.nombre_establecimiento, CONCAT(g6.latitud_establecimiento,',', g6.longitud_establecimiento),
	t.tipo_establecimiento, 0, u.id_usuario
FROM GRUPO6_TEMP g6, TIPO_ESTABLECIMIENTO t, USUARIO u
WHERE t.nombre like 'Establecimiento del grupo 6'
	AND u.usuario like g6.username_user_creador
GROUP BY g6.nombre_establecimiento
;

INSERT INTO TIPO_SERVICIO (nombre, descripcion)
SELECT DISTINCT nombre_servicio, descripcion_servicio
FROM GRUPO6_TEMP
WHERE nombre_servicio NOT IN (SELECT nombre FROM TIPO_SERVICIO)
	AND nombre_servicio NOT like ''
;

INSERT INTO SERVICIO (cupo, establecimiento, tipo_servicio, oficial, nombre)
SELECT 5, e.establecimiento, t.tipo_servicio, 1, t.nombre
FROM GRUPO6_TEMP g6, ESTABLECIMIENTO e, TIPO_SERVICIO t
WHERE g6.nombre_servicio like t.nombre
	AND g6.nombre_establecimiento like e.nombre
	AND g6.nombre_servicio NOT like ''
GROUP BY e.establecimiento, t.tipo_servicio
;

INSERT INTO CALIFICACION (punteo, comentario, usuario, servicio)
SELECT g6.valor_rc, g6.comentario_rc, u.usuario, s.servicio
FROM GRUPO6_TEMP g6, SERVICIO s, TIPO_SERVICIO t, USUARIO u
WHERE u.usuario like g6.username_user_trc
	AND t.nombre like g6.nombre_servicio
	AND t.tipo_servicio = s.tipo_servicio
	AND g6.valor_rc IS NOT NULL
	AND g6.comentario_rc NOT like ''
GROUP BY u.usuario, s.servicio
;

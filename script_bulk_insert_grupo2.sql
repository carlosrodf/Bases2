use proyecto;

DROP TABLE IF EXISTS GRUPO2TEMP;

CREATE TABLE GRUPO2TEMP(
	establecimiento_id_establecimiento INT,
	establecimiento_nombre VARCHAR(255),
	establecimiento_direccion VARCHAR(255),
	establecimiento_tipo VARCHAR(255),
	establecimiento_longitud VARCHAR(255),
	establecimiento_latitud VARCHAR(255),
	establecimiento_oficial INT,
	establecimiento_calificacion_general DECIMAL,
	establecimiento_dimension_id_establecimiento INT,
	establecimiento_dimension_id_dimension INT,
	establecimiento_dimension_id_categoria INT,
	categoria_id_categoria INT,
	categoria_nombre VARCHAR(255),
	categoria_descripcion VARCHAR(255),
	servicio_id_servicio INT,
	servicio_nombre VARCHAR(255),
	servicio_descripcion VARCHAR(255),
	caracteristica_id_caracteristica INT,
	caracteristica_nombre VARCHAR(255),
	caracteristica_valor VARCHAR(255),
	caracteristica_Fid_servicio INT,
	establecimiento_servicio_id_establecimiento_servicio INT,
	establecimiento_servicio_Fid_establecimiento INT,
	establecimiento_servicio_Fid_servicio INT,
	usuario_id_usuario INT,
	usuario_nombre VARCHAR(255),
	usuario_correo VARCHAR(255),
	usuario_telefono VARCHAR(255),
	usuario_rol VARCHAR(255),
	usuario_id_establecimiento INT,
	usuario_password VARCHAR(255),
	prereserva_id_prereserva INT,
	prereserva_horayfecha VARCHAR(255),
	prereserva_cantpersonas INT,
	prereserva_id_establecimiento_servicio INT,
	prereserva_id_usuario INT,
	comentario_id_comentario INT,
	comentario_contenido VARCHAR(255),
	comentario_calificacion INT,
	comentario_Fid_establecimiento_servicio INT
);

LOAD DATA LOCAL INFILE '/home/carlosrodf/Bases2/GRUPO2reporte.csv' 
INTO TABLE GRUPO2TEMP 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

INSERT INTO USUARIO (usuario, nombre, apellido, rol, password) values ('no_oficial', 'no_oficial', 'no_oficial', 4, 'no_oficial');
INSERT INTO TIPO_SERVICIO (nombre) VALUES ('OTROS-G2');
INSERT INTO USUARIO (usuario, nombre, apellido, rol, password) values ('G2', 'G2', 'G2', 2, 'G2');

INSERT INTO TIPO_ESTABLECIMIENTO (nombre) 
SELECT establecimiento_tipo FROM GRUPO2TEMP
WHERE establecimiento_tipo NOT IN (SELECT nombre FROM TIPO_ESTABLECIMIENTO)
group by GRUPO2TEMP.establecimiento_tipo;

INSERT INTO DIMENSION (nombre) 
SELECT categoria_nombre FROM GRUPO2TEMP
WHERE categoria_nombre NOT IN (SELECT nombre FROM DIMENSION)
group by GRUPO2TEMP.categoria_nombre;

INSERT INTO USUARIO (usuario, nombre, rol, password)
SELECT usuario_correo, usuario_nombre, if(usuario_rol like 'especial', 2, 3), usuario_password
FROM GRUPO2TEMP
LEFT JOIN USUARIO ON GRUPO2TEMP.usuario_correo like USUARIO.nombre
WHERE USUARIO.id_usuario is null and not( usuario_correo is null or usuario_nombre is null or usuario_password is null)
GROUP BY usuario_id_usuario;

INSERT INTO ESTABLECIMIENTO (nombre, posicion, descripcion, punteo, tipo_establecimiento, oficial, usuario)
SELECT CONCAT(G2_ESTABLECIMIENTO.establecimiento_nombre, '-M-G2'), 
CONCAT(G2_ESTABLECIMIENTO.establecimiento_longitud, ', ', G2_ESTABLECIMIENTO.establecimiento_latitud), 
G2_ESTABLECIMIENTO.establecimiento_direccion, 
G2_ESTABLECIMIENTO.establecimiento_calificacion_general,
TIPO_ESTABLECIMIENTO.tipo_establecimiento,
G2_ESTABLECIMIENTO.establecimiento_oficial,
IF(USUARIO.id_usuario IS NULL, (SELECT USUARIO.id_usuario FROM USUARIO WHERE usuario = 'no_oficial'GROUP BY USUARIO.usuario), USUARIO.id_usuario)
FROM GRUPO2TEMP G2_ESTABLECIMIENTO
INNER JOIN TIPO_ESTABLECIMIENTO ON G2_ESTABLECIMIENTO.establecimiento_tipo = TIPO_ESTABLECIMIENTO.nombre
LEFT JOIN GRUPO2TEMP G2_ADMIN ON G2_ESTABLECIMIENTO.establecimiento_id_establecimiento = G2_ADMIN.usuario_id_establecimiento
LEFT JOIN USUARIO ON G2_ADMIN.usuario_correo = USUARIO.usuario
GROUP BY G2_ESTABLECIMIENTO.establecimiento_id_establecimiento;

INSERT INTO SERVICIO (cupo, punteo, establecimiento, tipo_servicio, nombre)
SELECT 0, 0, ESTABLECIMIENTO.establecimiento, 
(SELECT tipo_servicio FROM TIPO_SERVICIO WHERE nombre = 'OTROS-G2' GROUP BY nombre)
, CONCAT(S.servicio_nombre, ' - G2')
FROM GRUPO2TEMP E
INNER JOIN GRUPO2TEMP ES ON E.establecimiento_id_establecimiento = ES.establecimiento_servicio_Fid_establecimiento
INNER JOIN GRUPO2TEMP S ON E.establecimiento_servicio_Fid_servicio = S.servicio_id_servicio
INNER JOIN ESTABLECIMIENTO ON CONCAT(E.establecimiento_nombre, '-M-G2') = ESTABLECIMIENTO.nombre
GROUP BY S.servicio_id_servicio;

INSERT INTO DIMENSION_ESTABLECIMIENTO (dimension, establecimiento)
SELECT DIMENSION.dimension, ESTABLECIMIENTO.establecimiento
FROM GRUPO2TEMP
INNER JOIN ESTABLECIMIENTO ON CONCAT(GRUPO2TEMP.establecimiento_nombre, '-M-G2') = ESTABLECIMIENTO.nombre
INNER JOIN DIMENSION ON GRUPO2TEMP.categoria_nombre = DIMENSION.nombre
GROUP BY GRUPO2TEMP.establecimiento_dimension_id_categoria, GRUPO2TEMP.establecimiento_dimension_id_establecimiento;

INSERT INTO CARACTERISTICA (nombre, duracion)
SELECT CONCAT(GRUPO2TEMP.caracteristica_nombre, ' - G2'), 0
FROM GRUPO2TEMP
LEFT JOIN CARACTERISTICA ON GRUPO2TEMP.caracteristica_nombre = CARACTERISTICA.nombre
WHERE CARACTERISTICA.caracteristica is null AND GRUPO2TEMP.caracteristica_nombre is not null
GROUP BY GRUPO2TEMP.caracteristica_id_caracteristica;

INSERT INTO DETALLE_SERVICIO (servicio, caracteristica)
SELECT S.servicio, C.caracteristica
FROM (SELECT CARACTERISTICA.caracteristica, GRUPO2TEMP.caracteristica_id_caracteristica, GRUPO2TEMP.caracteristica_Fid_servicio
FROM GRUPO2TEMP
INNER JOIN CARACTERISTICA ON CONCAT(GRUPO2TEMP.caracteristica_nombre, ' - G2') = CARACTERISTICA.nombre
GROUP BY GRUPO2TEMP.caracteristica_id_caracteristica) C
INNER JOIN
(SELECT SERVICIO.servicio, GRUPO2TEMP.servicio_id_servicio
FROM SERVICIO 
INNER JOIN GRUPO2TEMP ON SERVICIO.nombre = CONCAT(GRUPO2TEMP.servicio_nombre, ' - G2')) S
ON C.caracteristica_Fid_servicio = S.servicio_id_servicio
GROUP BY S.servicio, C.caracteristica;

-- INSERT INTO CALIFICACION (punteo, comentario, usuario, servicio)
-- SELECT C.comentario_calificacion, C.comentario_contenido, 
-- (SELECT USUARIO.usuario FROM USUARIO WHERE USUARIO.nombre = 'G2'), S2.servicio
-- FROM GRUPO2TEMP C
-- INNER JOIN GRUPO2TEMP SE ON C.comentario_Fid_establecimiento_servicio = SE.establecimiento_servicio_id_establecimiento_servicio
-- INNER JOIN GRUPO2TEMP S ON SE.establecimiento_servicio_Fid_servicio = S.servicio_id_servicio
-- INNER JOIN SERVICIO S2 ON CONCAT(S.servicio_nombre, ' - G2') = S2.nombre
-- GROUP BY C.comentario_id_comentario;
use proyecto;

DROP TABLE IF EXISTS GRUPO7TEMP;

CREATE TABLE GRUPO7TEMP(
	id_estabto INT,
	nombre VARCHAR(255),
	id_tipo VARCHAR(255),
	latitud VARCHAR(255),
	longitud VARCHAR(255),
	id_tamano VARCHAR(255),
	id_costo VARCHAR(255),
	id_usr INT,
	id_tp_user VARCHAR(255),
	usuario VARCHAR(255),
	contrasena VARCHAR(255),
	id_srv INT,
	tipo VARCHAR(255),
	id_estabto_usuario INT,
	precio DECIMAL,
	usr_estabto VARCHAR(255)
);

LOAD DATA LOCAL INFILE '/home/carlosrodf/Bases2/salidaprecargagrupo7.csv' 
INTO TABLE GRUPO7TEMP 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

INSERT INTO TIPO_SERVICIO (nombre) VALUES ('OTROS-G2');

INSERT INTO USUARIO (usuario, nombre, rol, password)
SELECT GRUPO7TEMP.usuario, GRUPO7TEMP.usuario, if(GRUPO7TEMP.id_tp_user like '%administrador%', 1, 
	if(GRUPO7TEMP.id_tp_user like '%establecimiento%', 2, 3)
), GRUPO7TEMP.contrasena
FROM GRUPO7TEMP
LEFT JOIN USUARIO ON GRUPO7TEMP.usuario like USUARIO.usuario
WHERE USUARIO.id_usuario is null and not( GRUPO7TEMP.usuario is null or GRUPO7TEMP.contrasena is null)
GROUP BY GRUPO7TEMP.usuario;

INSERT INTO TIPO_ESTABLECIMIENTO (nombre)
SELECT GRUPO7TEMP.id_tipo
FROM GRUPO7TEMP
LEFT JOIN TIPO_ESTABLECIMIENTO ON GRUPO7TEMP.id_tipo like TIPO_ESTABLECIMIENTO.nombre
WHERE TIPO_ESTABLECIMIENTO.tipo_establecimiento is null and not( GRUPO7TEMP.id_tipo is null )
GROUP BY GRUPO7TEMP.id_tipo;

INSERT INTO ESTABLECIMIENTO (nombre, posicion, descripcion, punteo, tipo_establecimiento, oficial, usuario)
SELECT CONCAT(E.nombre, '-M-G2'), 
CONCAT(E.longitud, ', ', E.latitud), 
"", 
0,
(SELECT TIPO_ESTABLECIMIENTO.tipo_establecimiento FROM TIPO_ESTABLECIMIENTO WHERE TIPO_ESTABLECIMIENTO.nombre like E.id_tipo),
0,
(SELECT USUARIO.id_usuario FROM USUARIO WHERE usuario like U.usr_estabto GROUP BY USUARIO.usuario)
FROM GRUPO7TEMP E
INNER JOIN GRUPO7TEMP U ON E.id_estabto = U.id_estabto_usuario
GROUP BY E.id_estabto;

DROP TABLE GRUPO7TEMP;

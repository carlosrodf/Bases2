USE proyecto;

DROP TABLE IF EXISTS GRUPO5TEMP;

CREATE TABLE GRUPO5TEMP(
	tipoEstablecimiento_id INT,
	tipoEstablecimiento_nombre VARCHAR(255),
	establecimiento_id INT,
	establecimiento_idTipoEstablecimiento INT,
	servicio_id INT,
	servicio_nombre VARCHAR(255),
	establecimientoServicio_idServicio INT,
	establecimientoServicio_idEstablecimiento INT,
	establecimientoServicio_porcentaje INT,
	tipoUsuario_id INT,
	tipoUsuario_nombre VARCHAR(255),
	usuario_id INT,
	usuario_idTipoUsuario INT,
	usuario_usuario VARCHAR(255),
	usuario_nombre VARCHAR(255),
	usuario_fechaNacimiento DATETIME,
	usuario_telefono VARCHAR(255),
	usuario_correo VARCHAR(255),
	usuario_genero VARCHAR(255),
	usuario_password VARCHAR(255),
	calificacion_idEstablecimiento INT,
	calificacion_idServicio INT,
	calificacion_idUsuario INT,
	calificacion_punteo INT,
	calificacion_fecha DATETIME,
	atributo_id INT,
	atributo_nombre VARCHAR(255),
	atributo_dimension INT,
	valor_id INT,
	valor_cantidad INT,
	valor_valor VARCHAR(255),
	valor_idEstablecimiento INT,
	valor_idAtributo INT,
	comentario_id INT,
	comentario_comentario VARCHAR(255),
	comentario_idUsuario INT,
	comentario_establecimiento INT
);

LOAD DATA LOCAL INFILE '/home/carlosrodf/Bases2/Grupo5.csv' 
INTO TABLE GRUPO5TEMP 
FIELDS TERMINATED BY ';' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

INSERT INTO USUARIO (usuario, nombre, apellido, rol, password) values ('G5', 'G5', 'G5', 2, 'G5');

INSERT INTO TIPO_ESTABLECIMIENTO(nombre)
SELECT DISTINCT(tipoEstablecimiento_nombre)
FROM GRUPO5TEMP;

INSERT INTO ESTABLECIMIENTO(nombre,descripcion,punteo,posicion,oficial,usuario,tipo_establecimiento)
SELECT DISTINCT(P.valor_valor),S.valor_valor,-1,CONCAT(LA.valor_valor,',',LO.valor_valor),0,(
SELECT id_usuario
FROM USUARIO U
WHERE U.usuario = 'G5'
),(
SELECT tipo_establecimiento
FROM TIPO_ESTABLECIMIENTO
WHERE nombre = P.tipoEstablecimiento_nombre
)
FROM GRUPO5TEMP P JOIN GRUPO5TEMP S ON P.establecimiento_id = S.establecimiento_id
JOIN GRUPO5TEMP LA ON LA.establecimiento_id = S.establecimiento_id
JOIN GRUPO5TEMP LO ON LO.establecimiento_id = LA.establecimiento_id
WHERE P.atributo_nombre = 'Nombre'
AND S.atributo_nombre = 'Descripcion'
AND LA.atributo_nombre = 'Latitud'
AND LO.atributo_nombre = 'Longitud';
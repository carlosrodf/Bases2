USE proyecto;

DROP TABLE IF EXISTS GRUPO1TEMP;

CREATE TABLE GRUPO1TEMP(
	id_tipo_establecimiento INT,
	nombre VARCHAR(255),
	estado INT,
	servicio_default INT,
	id_establecimiento INT,
	nombre1 VARCHAR(255),
	latitud FLOAT,
	longitud FLOAT,
	is_oficial INT,
	tipo INT,
	estado1 INT,
	descripcion VARCHAR(255),
	id_servicio_establecimiento INT,
	id_servicio INT,
	id_establecimiento1 INT,
	prioridad INT,
	estado2 INT,
	id_servicio1 INT,
	nombre2 VARCHAR(255),
	estado3 INT,
	id_detalle_tipo INT,
	etiqueta VARCHAR(255),
	tipo_dato VARCHAR(255),
	es_reservable INT,
	metrica_reserva INT,
	id_detalle_servicio INT,
	estado4 INT,
	valor INT,
	descripcion1 VARCHAR(255),
	id_servicio_establecimiento1 INT,
	hora_checkIn VARCHAR(255),
	id_establecimiento2 INT,
	id_usuario INT,
	estado5 INT,
	id_usuario1 INT,
	nombre3 VARCHAR(255),
	nick VARCHAR(255),
	email VARCHAR(255),
	estado6 INT,
	password VARCHAR(255),
	id_usuario2 INT,
	id_rol INT,
	estado7 INT,
	nombre4 VARCHAR(255),
	estado8 INT,
	id_calificacion INT,
	id_categoria INT,
	valor1 VARCHAR(255),
	fecha DECIMAL,
	comentario VARCHAR(255),
	id_servicio_establecimiento2 INT,
	id_categoria1 INT,
	nombre5 VARCHAR(255),
	tipo_dato1 VARCHAR(255),
	estado9 INT,
	id_detalle_categoria INT,
	valor2 VARCHAR(255),	
	id_categoria2 INT,
	estado10 INT,
	id_reserva INT,
	fecha_inicio DATETIME,
	fecha_fin DATETIME,
	aprobada INT,
	id_detalle_servicio1 INT,
	usuario_id_usuario INT
);

LOAD DATA LOCAL INFILE '/home/carlosrodf/Bases2/Grupo1-Datos.csv' 
INTO TABLE GRUPO1TEMP 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

INSERT INTO USUARIO (usuario, nombre, apellido, rol, password) values ('G1', 'G1', 'G1', 2, 'G1');

INSERT INTO TIPO_ESTABLECIMIENTO(nombre)
SELECT DISTINCT(nombre)
FROM GRUPO1TEMP
WHERE nombre NOT IN (
SELECT nombre 
FROM TIPO_ESTABLECIMIENTO
);

INSERT INTO ESTABLECIMIENTO(nombre,posicion,descripcion,punteo,tipo_establecimiento,oficial,usuario)
SELECT DISTINCT(nombre1),CONCAT(latitud,',',longitud),descripcion,-1,(
SELECT X.tipo_establecimiento
FROM TIPO_ESTABLECIMIENTO X
WHERE X.nombre = G.nombre
),0,(
SELECT id_usuario
FROM USUARIO U
WHERE U.usuario = 'G1'
)
FROM GRUPO1TEMP G;
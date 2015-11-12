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

LOAD DATA INFILE '/media/kevin/4712A7D769A4B1CC/Dropbox/usac/8voSemestre/bases2/BD2-20015-S2/ArchivosBases2/GRUPO2reporte.csv' 
INTO TABLE GRUPO2TEMP 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;



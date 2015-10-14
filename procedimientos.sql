DELIMITER $$
-- USUARIO

CREATE PROCEDURE crearUsuario(usuario VARCHAR(20), nombre VARCHAR(20), apellido VARCHAR(20), rol INT)
BEGIN
INSERT INTO USUARIO(usuario,nombre,apellido,rol) VALUES(usuario,nombre,apellido,rol);
END $$

CREATE PROCEDURE actualizarUsuario(usuario VARCHAR(20), nombre VARCHAR(20), apellido VARCHAR(20), rol INT)
BEGIN
UPDATE USUARIO SET nombre=nombre, apellido=apellido, rol=rol WHERE usuario=usuario;
END $$

CREATE PROCEDURE eliminarUsuario(usuario VARCHAR(20))
BEGIN
DELETE FROM CALIFICACION WHERE usuario=usuario;
DELETE FROM RESERVA WHERE usuario=usuario;
DELETE FROM USUARIO WHERE usuario=usuario;
END $$

-- TIPO ESTABLECIMIENTO

CREATE PROCEDURE crearTipoEstablecimiento(nombre VARCHAR(20), descripcion VARCHAR(200))
BEGIN
INSERT INTO TIPO_ESTABLECIMIENTO(nombre,descripcion) VALUES(nombre,descripcion);
END $$

CREATE PROCEDURE actualizarTipoEstablecimiento(id INT, nombre VARCHAR(20))
BEGIN
UPDATE TIPO_ESTABLECIMIENTO SET nombre=nombre WHERE tipo_establecimiento=id;
END $$

CREATE PROCEDURE eliminarTipoEstablecimiento(id INT)
BEGIN
DECLARE limE INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
DECLARE e INT DEFAULT 0;
DECLARE limS INT DEFAULT 0;
DECLARE j INT DEFAULT 0;
DECLARE s INT DEFAULT 0;
SELECT COUNT(*) FROM ESTABLECIMIENTO WHERE tipo_establecimiento=id INTO limE;
WHILE i<limE DO
SELECT establecimiento FROM ESTABLECIMIENTO WHERE tipo_establecimiento=id ORDER BY establecimiento LIMIT 1 INTO e;
DELETE FROM DIMENSION_ESTABLECIMIENTO WHERE establecimiento=e;
SELECT COUNT(*) FROM SERVICIO WHERE establecimiento=e INTO limS;
WHILE j<limS DO
SELECT servicio FROM SERVICIO WHERE establecimiento=e ORDER BY servicio LIMIT 1 INTO s;
DELETE FROM CALIFICACION WHERE servicio=s;
DELETE FROM RESERVA WHERE servicio=s;
DELETE FROM DETALLE_SERVICIO WHERE servicio=s;
DELETE FROM SERVICIO WHERE servicio=s;
SET j = j + 1;
END WHILE;
DELETE FROM ESTABLECIMIENTO WHERE establecimiento=e;
SET i = i + 1;
END WHILE;
DELETE FROM TIPO_ESTABLECIMIENTO WHERE tipo_establecimiento=id;
END $$

-- ESTABLECIMIENTO

CREATE PROCEDURE crearEstablecimiento(nombre VARCHAR(20), posicion VARCHAR(30), descripcion VARCHAR(200), tipo INT, oficial INT)
BEGIN
INSERT INTO ESTABLECIMIENTO(nombre,posicion,descripcion,punteo,tipo_establecimiento,oficial) VALUES(nombre,posicion,descripcion,-1,tipo,oficial);
END $$

CREATE PROCEDURE actualizarEstablecimiento(id INT, nombre VARCHAR(20), posicion VARCHAR(30), descripcion VARCHAR(200), punteo INT, tipo INT, oficial INT)
BEGIN
UPDATE ESTABLECIMIENTO SET nombre=nombre, posicion=posicion, descripcion=descripcion, punteo=punteo, tipo=tipo, oficial=oficial WHERE establecimiento=id;
END $$

CREATE PROCEDURE eliminarEstablecimiento(establecimiento INT)
BEGIN
DECLARE limS INT DEFAULT 0;
DECLARE j INT DEFAULT 0;
DECLARE s INT DEFAULT 0;
DELETE FROM DIMENSION_ESTABLECIMIENTO WHERE establecimiento=e;
SELECT COUNT(*) FROM SERVICIO WHERE establecimiento=establecimiento INTO limS;
WHILE j<limS DO
SELECT servicio FROM SERVICIO WHERE establecimiento=establecimiento ORDER BY servicio LIMIT 1 INTO s;
DELETE FROM CALIFICACION WHERE servicio=s;
DELETE FROM RESERVA WHERE servicio=s;
DELETE FROM DETALLE_SERVICIO WHERE servicio=s;
DELETE FROM SERVICIO WHERE servicio=s;
SET j = j + 1;
END WHILE;
DELETE FROM ESTABLECIMIENTO WHERE establecimiento=establecimiento;
END $$

-- DIMENSION

CREATE PROCEDURE crearDimension(nombre VARCHAR(20))
BEGIN
INSERT INTO DIMENSION(nombre) VALUES(nombre);
END $$

CREATE PROCEDURE actualizarDimension(id INT, nombre VARCHAR(20))
BEGIN
UPDATE DIMENSION SET nombre=nombre WHERE dimension=id;
END $$

CREATE PROCEDURE eliminarDimension(id INT)
BEGIN
DELETE FROM CATEGORIA WHERE dimension=id;
DELETE FROM DIMENSION_ESTABLECIMIENTO WHERE dimension=id;
DELETE FROM DIMENSION WHERE dimension=id;
END $$

-- CATEGORIA

CREATE PROCEDURE crearCategoria(dimension INT, nombre VARCHAR(20))
BEGIN
INSERT INTO CATEGORIA(dimension,nombre) VALUES(dimension,nombre);
END $$

CREATE PROCEDURE actualizarCategoria(id INT, nombre VARCHAR(20))
BEGIN
UPDATE CATEGORIA SET nombre=nombre WHERE categoria=id;
END $$

CREATE PROCEDURE eliminarCategoria(id INT)
BEGIN
DELETE FROM CATEGORIA WHERE categoria=id;
END $$

-- DIMENSION-ESTABLECIMIENTO

CREATE PROCEDURE crearDimensionEstablecimiento(dimension INT, establecimiento INT)
BEGIN
INSERT INTO DIMENSION_ESTABLECIMIENTO(dimension,establecimiento) VALUES(dimension,establecimiento);
END $$

CREATE PROCEDURE eliminarDimensionEstablecimiento(dimension INT, establecimiento INT)
BEGIN
DELETE FROM DIMENSION_ESTABLECIMIENTO WHERE dimension=dimension AND establecimiento=establecimiento;
END $$

-- TIPO SERVICIO

CREATE PROCEDURE crearTipoServicio(nombre VARCHAR(20), descripcion VARCHAR(200))
BEGIN
INSERT INTO TIPO_SERVICIO(nombre,descripcion) VALUES(nombre,descripcion);
END $$

CREATE PROCEDURE actualizarTipoServicio(id INT, nombre VARCHAR(20), descripcion VARCHAR(200))
BEGIN
UPDATE TIPO_SERVICIO SET nombre=nombre, descripcion=descripcion WHERE tipo_servicio=id;
END $$

CREATE PROCEDURE eliminarTipoServicio(tipo_servicio INT)
BEGIN
DECLARE limS INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
DECLARE s INT DEFAULT 0;
SELECT COUNT(*) FROM SERVICIO WHERE tipo_servicio=tipo_servicio INTO limS;
WHILE i<limS DO
SELECT servicio FROM SERVICIO WHERE tipo_servicio=tipo_servicio ORDER BY servicio LIMIT 1 INTO s;
DELETE FROM DETALLE_SERVICIO WHERE servicio=s;
DELETE FROM RESERVA WHERE servicio=s;
DELETE FROM CALIFICACION WHERE servicio=s;
DELETE FROM SERVICIO WHERE servicio=s;
SET i = i + 1;
END WHILE;
DELETE FROM TIPO_SERVICIO WHERE tipo_servicio=tipo_servicio;
END $$

-- SERVICIO

CREATE PROCEDURE crearServicio(nombre VARCHAR(20), cupo INT, establecimiento INT, tipo_servicio INT)
BEGIN
INSERT INTO SERVICIO(nombre,cupo,establecimiento,tipo_servicio,punteo) VALUES(nombre,cupo,establecimiento,tipo_servicio,-1);
END $$

CREATE PROCEDURE actualizarServicio(id INT, nombre VARCHAR(20), cupo INT, establecimiento INT, tipo_servicio INT, punteo INT)
BEGIN
UPDATE SERVICIO SET nombre=nombre, cupo=cupo, establecimiento=establecimiento, tipo_servicio=tipo_servicio, punteo=punteo WHERE servicio=id;
END $$

CREATE PROCEDURE eliminarServicio(servicio INT)
BEGIN
DELETE FROM DETALLE_SERVICIO WHERE servicio=servicio;
DELETE FROM RESERVA WHERE servicio=servicio;
DELETE FROM CALIFICACION WHERE servicio=servicio;
DELETE FROM SERVICIO WHERE servicio=servicio;
END $$

-- CARACTERISTICA

CREATE PROCEDURE crearCaracteristica(nombre VARCHAR(20), duracion INT)
BEGIN
INSERT INTO CARACTERISTICA(nombre,duracion) VALUES(nombre,duracion);
END $$

CREATE PROCEDURE actualizarCaracteristica(id INT, nombre VARCHAR(20), duracion INT)
BEGIN
UPDATE CARACTERISTICA SET nombre=nombre, duracion=duracion WHERE caracteristica=id;
END $$

CREATE PROCEDURE eliminarCaracteristica(caracteristica INT)
BEGIN
DELETE FROM DETALLE_SERVICIO WHERE caracteristica=caracteristica;
DELETE FROM CARACTERISTICA WHERE caracteristica=CARACTERISTICA;
END $$

-- DETALLE SERVICIO

CREATE PROCEDURE crearDetalleServicio(servicio INT, caracteristica INT)
BEGIN
INSERT INTO DETALLE_SERVICIO(servicio, caracteristica) VALUES(servicio,caracteristica);
END $$

CREATE PROCEDURE eliminarDetalleServicio(servicio INT, caracteristica INT)
BEGIN
DELETE FROM DETALLE_SERVICIO WHERE servicio=servicio AND caracteristica=caracteristica;
END $$

-- RESERVA

CREATE PROCEDURE crearReserva(fecha DATETIME, usuario VARCHAR(20), servicio INT)
BEGIN
INSERT INTO RESERVA(fecha,usuario,servicio) VALUES(fecha,usuario,servicio);
END $$

CREATE PROCEDURE actualizarReserva(reserva INT, fecha DATETIME, usuario VARCHAR(20), servicio INT)
BEGIN
UPDATE RESERVA SET fecha=fecha, usuario=usuario, servicio=servicio WHERE reserva=reserva;
END $$

CREATE PROCEDURE eliminarReserva(reserva INT)
BEGIN
DELETE FROM RESERVA WHERE reserva=reserva;
END $$

-- CALIFICACION

CREATE PROCEDURE crearCalificacion(punteo INT, comentario VARCHAR(200), usuario INT, servicio INT)
BEGIN
INSERT INTO CALIFICACION(punteo,comentario,usuario,servicio) VALUES(punteo,comentario,usuario,servicio);
END $$

CREATE PROCEDURE actualizarCalificacion(calificacion INT, punteo INT, comentario VARCHAR(200), usuario INT, servicio INT)
BEGIN
UPDATE CALIFICACION SET punteo=punteo, comentario=comentario, usuario=usuario, servicio=servicio WHERE calificacion=calificacion;
END $$

CREATE PROCEDURE eliminarCalificacion(calificacion INT)
BEGIN
DELETE FROM CALIFICACION WHERE calificacion=calificacion;
END $$

DELIMITER ;

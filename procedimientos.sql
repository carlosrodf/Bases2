DELIMITER $$
-- USUARIO

CREATE PROCEDURE crearUsuario(usuario VARCHAR(20), password VARCHAR(20), nombre VARCHAR(20), apellido VARCHAR(20), rol INT)
BEGIN
INSERT INTO USUARIO(usuario,password,nombre,apellido,rol) VALUES(usuario,password,nombre,apellido,rol);
END $$

CREATE PROCEDURE actualizarUsuario(usuari VARCHAR(20), passwor VARCHAR(20), nombr VARCHAR(20), apellid VARCHAR(20), ro INT)
BEGIN
UPDATE USUARIO SET nombre=nombr, apellido=apellid, rol=ro, password=passwor WHERE usuario=usuari;
END $$

CREATE PROCEDURE eliminarUsuario(usuari VARCHAR(20))
BEGIN
DELETE FROM CALIFICACION WHERE usuario=usuari;
DELETE FROM RESERVA WHERE usuario=usuari;
DELETE FROM USUARIO WHERE usuario=usuari;
END $$

-- TIPO ESTABLECIMIENTO

CREATE PROCEDURE crearTipoEstablecimiento(nombr VARCHAR(20), descripcio VARCHAR(200))
BEGIN
INSERT INTO TIPO_ESTABLECIMIENTO(nombre,descripcion) VALUES(nombr,descripcio);
END $$

CREATE PROCEDURE actualizarTipoEstablecimiento(id INT, nombr VARCHAR(20))
BEGIN
UPDATE TIPO_ESTABLECIMIENTO SET nombre=nombr WHERE tipo_establecimiento=id;
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

CREATE PROCEDURE crearEstablecimiento(nombr VARCHAR(20), posicio VARCHAR(30), descripcio VARCHAR(200), tip INT, oficia INT)
BEGIN
INSERT INTO ESTABLECIMIENTO(nombre,posicion,descripcion,punteo,tipo_establecimiento,oficial) VALUES(nombr,posicio,descripcio,-1,tip,oficia);
END $$

CREATE PROCEDURE actualizarEstablecimiento(id INT, nombr VARCHAR(20), posicio VARCHAR(30), descripcio VARCHAR(200), punte INT, tip INT, oficia INT)
BEGIN
UPDATE ESTABLECIMIENTO SET nombre=nombr, posicion=posicio, descripcion=descripcio, punteo=punte, tipo_establecimiento=tip, oficial=oficia WHERE establecimiento=id;
END $$

CREATE PROCEDURE eliminarEstablecimiento(e INT)
BEGIN
DECLARE limS INT DEFAULT 0;
DECLARE j INT DEFAULT 0;
DECLARE s INT DEFAULT 0;
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
END $$

-- DIMENSION

CREATE PROCEDURE crearDimension(nombr VARCHAR(20))
BEGIN
INSERT INTO DIMENSION(nombre) VALUES(nombr);
END $$

CREATE PROCEDURE actualizarDimension(id INT, nombr VARCHAR(20))
BEGIN
UPDATE DIMENSION SET nombre=nombr WHERE dimension=id;
END $$

CREATE PROCEDURE eliminarDimension(id INT)
BEGIN
DELETE FROM CATEGORIA WHERE dimension=id;
DELETE FROM DIMENSION_ESTABLECIMIENTO WHERE dimension=id;
DELETE FROM DIMENSION WHERE dimension=id;
END $$

-- CATEGORIA

CREATE PROCEDURE crearCategoria(dimensio INT, nombr VARCHAR(20))
BEGIN
INSERT INTO CATEGORIA(dimension,nombre) VALUES(dimensio,nombr);
END $$

CREATE PROCEDURE actualizarCategoria(id INT, nombr VARCHAR(20))
BEGIN
UPDATE CATEGORIA SET nombre=nombr WHERE categoria=id;
END $$

CREATE PROCEDURE eliminarCategoria(id INT)
BEGIN
DELETE FROM CATEGORIA WHERE categoria=id;
END $$

-- DIMENSION-ESTABLECIMIENTO

CREATE PROCEDURE crearDimensionEstablecimiento(dimensio INT, establecimient INT)
BEGIN
INSERT INTO DIMENSION_ESTABLECIMIENTO(dimension,establecimiento) VALUES(dimensio,establecimient);
END $$

CREATE PROCEDURE eliminarDimensionEstablecimiento(dimensio INT, establecimient INT)
BEGIN
DELETE FROM DIMENSION_ESTABLECIMIENTO WHERE dimension=dimensio AND establecimiento=establecimient;
END $$

-- TIPO SERVICIO

CREATE PROCEDURE crearTipoServicio(nombr VARCHAR(20), descripcio VARCHAR(200))
BEGIN
INSERT INTO TIPO_SERVICIO(nombre,descripcion) VALUES(nombr,descripcio);
END $$

CREATE PROCEDURE actualizarTipoServicio(id INT, nombr VARCHAR(20), descripcio VARCHAR(200))
BEGIN
UPDATE TIPO_SERVICIO SET nombre=nombr, descripcion=descripcio WHERE tipo_servicio=id;
END $$

CREATE PROCEDURE eliminarTipoServicio(tipo_servici INT)
BEGIN
DECLARE limS INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
DECLARE s INT DEFAULT 0;
SELECT COUNT(*) FROM SERVICIO WHERE tipo_servicio=tipo_servici INTO limS;
WHILE i<limS DO
SELECT servicio FROM SERVICIO WHERE tipo_servicio=tipo_servici ORDER BY servicio LIMIT 1 INTO s;
DELETE FROM DETALLE_SERVICIO WHERE servicio=s;
DELETE FROM RESERVA WHERE servicio=s;
DELETE FROM CALIFICACION WHERE servicio=s;
DELETE FROM SERVICIO WHERE servicio=s;
SET i = i + 1;
END WHILE;
DELETE FROM TIPO_SERVICIO WHERE tipo_servicio=tipo_servici;
END $$

-- SERVICIO

CREATE PROCEDURE crearServicio(nombr VARCHAR(20), cup INT, establecimient INT, tipo_servici INT)
BEGIN
INSERT INTO SERVICIO(nombre,cupo,establecimiento,tipo_servicio,punteo) VALUES(nombr,cup,establecimient,tipo_servici,-1);
END $$

CREATE PROCEDURE actualizarServicio(id INT, nombr VARCHAR(20), cup INT, establecimient INT, tipo_servici INT, punte INT)
BEGIN
UPDATE SERVICIO SET nombre=nombr, cupo=cup, establecimiento=establecimient, tipo_servicio=tipo_servici, punteo=punte WHERE servicio=id;
END $$

CREATE PROCEDURE eliminarServicio(servici INT)
BEGIN
DELETE FROM DETALLE_SERVICIO WHERE servicio=servici;
DELETE FROM RESERVA WHERE servicio=servici;
DELETE FROM CALIFICACION WHERE servicio=servici;
DELETE FROM SERVICIO WHERE servicio=servici;
END $$

-- CARACTERISTICA

CREATE PROCEDURE crearCaracteristica(nombr VARCHAR(20), duracio INT)
BEGIN
INSERT INTO CARACTERISTICA(nombre,duracion) VALUES(nombr,duracio);
END $$

CREATE PROCEDURE actualizarCaracteristica(id INT, nombr VARCHAR(20), duracio INT)
BEGIN
UPDATE CARACTERISTICA SET nombre=nombr, duracion=duracio WHERE caracteristica=id;
END $$

CREATE PROCEDURE eliminarCaracteristica(caracteristic INT)
BEGIN
DELETE FROM DETALLE_SERVICIO WHERE caracteristica=caracteristic;
DELETE FROM CARACTERISTICA WHERE caracteristica=caracteristic;
END $$

-- DETALLE SERVICIO

CREATE PROCEDURE crearDetalleServicio(servici INT, caracteristic INT)
BEGIN
INSERT INTO DETALLE_SERVICIO(servicio, caracteristica) VALUES(servici,caracteristic);
END $$

CREATE PROCEDURE eliminarDetalleServicio(servici INT, caracteristic INT)
BEGIN
DELETE FROM DETALLE_SERVICIO WHERE servicio=servici AND caracteristica=caracteristic;
END $$

-- RESERVA

CREATE PROCEDURE crearReserva(fech DATETIME, usuari VARCHAR(20), servici INT)
BEGIN
INSERT INTO RESERVA(fecha,usuario,servicio) VALUES(fech,usuari,servici);
UPDATE SERVICIO SET cupo = cupo - 1 WHERE servicio = servici;
END $$

CREATE PROCEDURE actualizarReserva(reserv INT, fech DATETIME, usuari VARCHAR(20), servici INT)
BEGIN
UPDATE RESERVA SET fecha=fech, usuario=usuari, servicio=servici WHERE reserva=reserv;
END $$

CREATE PROCEDURE eliminarReserva(reserv INT)
BEGIN
DELETE FROM RESERVA WHERE reserva=reserv;
END $$

-- CALIFICACION

CREATE PROCEDURE crearCalificacion(points INT, comentario VARCHAR(200), user VARCHAR(20), service INT)
BEGIN
DECLARE last INT DEFAULT 0;
DECLARE flag INT DEFAULT 0;
DECLARE promedio INT DEFAULT 0;
DECLARE est INT DEFAULT 0;
SELECT E.establecimiento FROM ESTABLECIMIENTO E, SERVICIO S WHERE S.servicio = service AND S.establecimiento = E.establecimiento LIMIT 1 INTO est;
SELECT COUNT(*) FROM CALIFICACION WHERE servicio = service AND usuario = user INTO flag;
SELECT punteo FROM SERVICIO WHERE servicio = service LIMIT 1 INTO last;
IF flag > 0 THEN UPDATE CALIFICACION SET punteo = points WHERE servicio = service AND usuario = user;
ELSE INSERT INTO CALIFICACION(punteo,comentario,usuario,servicio) VALUES(points,comentario,user,service);
END IF;
SELECT AVG(punteo) FROM CALIFICACION WHERE servicio = service INTO promedio;
UPDATE SERVICIO SET punteo = promedio WHERE servicio = service;
SELECT AVG(punteo) FROM SERVICIO WHERE establecimiento = est INTO promedio;
UPDATE ESTABLECIMIENTO SET punteo = promedio WHERE establecimiento = est;
END $$

CREATE PROCEDURE actualizarCalificacion(calificacio INT, punte INT, comentari VARCHAR(200), usuari VARCHAR(20), servici INT)
BEGIN
UPDATE CALIFICACION SET punteo=punte, comentario=comentari, usuario=usuari, servicio=servici WHERE calificacion=calificacio;
END $$

CREATE PROCEDURE eliminarCalificacion(calificacio INT)
BEGIN
DELETE FROM CALIFICACION WHERE calificacion=calificacio;
END $$

-- FUNCION DE BUSQUEDA

CREATE PROCEDURE busqueda(item VARCHAR(20))
BEGIN
SELECT E.establecimiento,E.nombre,E.descripcion,E.punteo
FROM ESTABLECIMIENTO E LEFT JOIN TIPO_ESTABLECIMIENTO TE ON E.tipo_establecimiento = TE.tipo_establecimiento
WHERE E.nombre LIKE CONCAT('%',item,'%')
OR TE.nombre LIKE CONCAT('%',item,'%')
UNION
SELECT E.establecimiento,E.nombre,E.descripcion,E.punteo
FROM ESTABLECIMIENTO E LEFT JOIN DIMENSION_ESTABLECIMIENTO DE ON E.establecimiento = DE.establecimiento,
DIMENSION_ESTABLECIMIENTO DE2 LEFT JOIN DIMENSION D ON DE2.dimension = D.dimension
WHERE DE.establecimiento = DE2.establecimiento
AND D.nombre LIKE CONCAT('%',item,'%');
END $$

-- Procedimientos para los dropdown list

CREATE PROCEDURE getEstablecimientos(calificacio INT)
BEGIN
SELECT tipo_establecimiento, nombre FROM TIPO_ESTABLECIMIENTO;
END $$

DELIMITER ;

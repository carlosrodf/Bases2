-- Procedimientos para dropdownlist

DELIMITER $$

CREATE PROCEDURE getTiposEstablecimiento()
BEGIN
SELECT tipo_establecimiento, nombre FROM TIPO_ESTABLECIMIENTO;
END $$


CREATE PROCEDURE getEstablecimientos()
BEGIN
SELECT establecimiento, nombre FROM ESTABLECIMIENTO;
END $$


CREATE PROCEDURE getTiposServicio()
BEGIN
SELECT tipo_servicio, nombre FROM TIPO_SERVICIO;
END $$


CREATE PROCEDURE getServicios()
BEGIN
SELECT servicio, nombre  FROM SERVICIO;
END $$


CREATE PROCEDURE getDimensiones()
BEGIN
SELECT dimension, nombre FROM DIMENSION;
END $$


CREATE PROCEDURE getCategorias()
BEGIN
SELECT categoria, nombre FROM CATEGORIA;
END $$


CREATE PROCEDURE getCaracteristicas()
BEGIN
SELECT caracteristica, nombre FROM CARACTERISTICA;
END $$


CREATE PROCEDURE getUsuarios()
BEGIN
SELECT id_usuario, usuario FROM USUARIO;
END $$

DELIMITER ;

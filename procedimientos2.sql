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

DELIMITER ;

--
-- ER/Studio 8.0 SQL Code Generation
-- Company :      CR
-- Project :      Bases2.DM1
-- Author :       Carlos Rodriguez
--
-- Date Created : Monday, October 12, 2015 12:05:17
-- Target DBMS : MySQL 5.x
--

CREATE DATABASE proyecto;
USE proyecto;

--
-- TABLE: CALIFICACION
--

CREATE TABLE CALIFICACION(
    calificacion    INT             AUTO_INCREMENT,
    punteo          INT             NOT NULL,
    comentario      VARCHAR(200),
    usuario         VARCHAR(20)     NOT NULL,
    servicio        INT             NOT NULL,
    PRIMARY KEY (calificacion)
)ENGINE=INNODB
;



--
-- TABLE: CARACTERISTICA
--

CREATE TABLE CARACTERISTICA(
    caracteristica    INT            AUTO_INCREMENT,
    nombre            VARCHAR(20)    NOT NULL,
    duracion          INT            NOT NULL,
    PRIMARY KEY (caracteristica)
)ENGINE=INNODB
;



--
-- TABLE: CATEGORIA
--

CREATE TABLE CATEGORIA(
    categoria    INT            AUTO_INCREMENT,
    dimension    INT            NOT NULL,
    nombre       VARCHAR(20),
    PRIMARY KEY (categoria, dimension)
)ENGINE=INNODB
;



--
-- TABLE: DETALLE_SERVICIO
--

CREATE TABLE DETALLE_SERVICIO(
    servicio          INT    NOT NULL,
    caracteristica    INT    NOT NULL,
    PRIMARY KEY (servicio, caracteristica)
)ENGINE=INNODB
;



--
-- TABLE: DIMENSION
--

CREATE TABLE DIMENSION(
    dimension    INT            AUTO_INCREMENT,
    nombre       VARCHAR(20)    NOT NULL,
    PRIMARY KEY (dimension)
)ENGINE=INNODB
;



--
-- TABLE: DIMENSION_ESTABLECIMIENTO
--

CREATE TABLE DIMENSION_ESTABLECIMIENTO(
    dimension          INT    NOT NULL,
    establecimiento    INT    NOT NULL,
    PRIMARY KEY (dimension, establecimiento)
)ENGINE=INNODB
;



--
-- TABLE: ESTABLECIMIENTO
--

CREATE TABLE ESTABLECIMIENTO(
    establecimiento         INT             AUTO_INCREMENT,
    nombre                  VARCHAR(100)     NOT NULL,
    posicion                VARCHAR(30),
    descripcion             VARCHAR(200),
    punteo                  INT,
    tipo_establecimiento    INT             NOT NULL,
    oficial INT NOT NULL,
		usuario INT NOT NULL,
    PRIMARY KEY (establecimiento)
)ENGINE=INNODB
;


--
-- TABLE: OTRO_NOMBRE
--

CREATE TABLE OTRO_NOMBRE(
    no_oficial  INT		AUTO_INCREMENT,
    oficial     INT     	NOT NULL,
    alias       VARCHAR(20)     NOT NULL,
    PRIMARY KEY (no_oficial)
)ENGINE=INNODB
;


--
-- TABLE: RESERVA
--

CREATE TABLE RESERVA(
    reserva     INT            AUTO_INCREMENT,
    fecha       DATETIME       NOT NULL,
    usuario     VARCHAR(20)    NOT NULL,
    servicio    INT            NOT NULL,
    PRIMARY KEY (reserva)
)ENGINE=INNODB
;



--
-- TABLE: SERVICIO
--

CREATE TABLE SERVICIO(
    servicio           INT    AUTO_INCREMENT,
    cupo               INT    NOT NULL,
    punteo             INT,
    establecimiento    INT    NOT NULL,
    tipo_servicio      INT    NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    oficial INT NULL,
    PRIMARY KEY (servicio)
)ENGINE=INNODB
;



--
-- TABLE: TIPO_ESTABLECIMIENTO
--

CREATE TABLE TIPO_ESTABLECIMIENTO(
    tipo_establecimiento    INT             AUTO_INCREMENT,
    nombre                  VARCHAR(100)     NOT NULL,
    descripcion             VARCHAR(200),
    PRIMARY KEY (tipo_establecimiento)
)ENGINE=INNODB
;



--
-- TABLE: TIPO_SERVICIO
--

CREATE TABLE TIPO_SERVICIO(
    tipo_servicio    INT             AUTO_INCREMENT,
    nombre           VARCHAR(100)     NOT NULL,
    descripcion      VARCHAR(200),
    PRIMARY KEY (tipo_servicio)
)ENGINE=INNODB
;



--
-- TABLE: USUARIO
--

CREATE TABLE USUARIO(
    id_usuario INT AUTO_INCREMENT,
    usuario     VARCHAR(20)    NOT NULL,
    nombre      VARCHAR(20)    NOT NULL,
    apellido    VARCHAR(20),
    rol         INT            NOT NULL,
    password VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_usuario)
)ENGINE=INNODB
;

--
-- TABLE: USUARIO
--

ALTER TABLE USUARIO ADD UNIQUE UsuarioUnique(usuario)
;

--
-- TABLE: CALIFICACION
--

ALTER TABLE CALIFICACION ADD UNIQUE calificacionUnique(usuario,servicio)
;

ALTER TABLE CALIFICACION ADD CONSTRAINT RefUSUARIO9
    FOREIGN KEY (usuario)
    REFERENCES USUARIO(usuario)
;

ALTER TABLE CALIFICACION ADD CONSTRAINT RefSERVICIO10
    FOREIGN KEY (servicio)
    REFERENCES SERVICIO(servicio)
;


--
-- TABLE: CATEGORIA
--

ALTER TABLE CATEGORIA ADD CONSTRAINT RefDIMENSION2
    FOREIGN KEY (dimension)
    REFERENCES DIMENSION(dimension)
;


--
-- TABLE: DETALLE_SERVICIO
--

ALTER TABLE DETALLE_SERVICIO ADD CONSTRAINT RefSERVICIO7
    FOREIGN KEY (servicio)
    REFERENCES SERVICIO(servicio)
;

ALTER TABLE DETALLE_SERVICIO ADD CONSTRAINT RefCARACTERISTICA8
    FOREIGN KEY (caracteristica)
    REFERENCES CARACTERISTICA(caracteristica)
;


--
-- TABLE: DIMENSION_ESTABLECIMIENTO
--

ALTER TABLE DIMENSION_ESTABLECIMIENTO ADD CONSTRAINT RefDIMENSION3
    FOREIGN KEY (dimension)
    REFERENCES DIMENSION(dimension)
;

ALTER TABLE DIMENSION_ESTABLECIMIENTO ADD CONSTRAINT RefESTABLECIMIENTO4
    FOREIGN KEY (establecimiento)
    REFERENCES ESTABLECIMIENTO(establecimiento)
;


--
-- TABLE: ESTABLECIMIENTO
--

ALTER TABLE ESTABLECIMIENTO ADD CONSTRAINT RefTIPO_ESTABLECIMIENTO1
    FOREIGN KEY (tipo_establecimiento)
    REFERENCES TIPO_ESTABLECIMIENTO(tipo_establecimiento)
;

ALTER TABLE ESTABLECIMIENTO ADD CONSTRAINT RefUSUARIO
    FOREIGN KEY (usuario)
    REFERENCES USUARIO(id_usuario)
;

--
-- TABLE: OTRO_NOMBRE
--

ALTER TABLE OTRO_NOMBRE ADD CONSTRAINT Ref1
    FOREIGN KEY (oficial)
    REFERENCES ESTABLECIMIENTO(establecimiento)
;

--
-- TABLE: RESERVA
--

ALTER TABLE RESERVA ADD CONSTRAINT RefUSUARIO11
    FOREIGN KEY (usuario)
    REFERENCES USUARIO(usuario)
;

ALTER TABLE RESERVA ADD CONSTRAINT RefSERVICIO12
    FOREIGN KEY (servicio)
    REFERENCES SERVICIO(servicio)
;


--
-- TABLE: SERVICIO
--

ALTER TABLE SERVICIO ADD CONSTRAINT RefESTABLECIMIENTO6
    FOREIGN KEY (oficial)
    REFERENCES ESTABLECIMIENTO(establecimiento)
;

ALTER TABLE SERVICIO ADD CONSTRAINT RefSERVICIO_TIPO_SERVICIO
    FOREIGN KEY (tipo_servicio)
    REFERENCES TIPO_SERVICIO(tipo_servicio)
;
--
-- TABLE: BITACORA
--

CREATE TABLE BITACORA(
    id INT AUTO_INCREMENT,
    fecha     DATETIME    NOT NULL,
    accion      VARCHAR(20)    NOT NULL,
    establecimiento    INT NULL,
    usuario         VARCHAR(20) NULL,
    mensaje VARCHAR(200) NULL,
    PRIMARY KEY (id)
)ENGINE=INNODB
;

ALTER TABLE BITACORA ADD CONSTRAINT RefBITACORA1
    FOREIGN KEY (establecimiento)
    REFERENCES ESTABLECIMIENTO(establecimiento)
;

ALTER TABLE BITACORA ADD CONSTRAINT RefBITACORA2
    FOREIGN KEY (usuario)
    REFERENCES USUARIO(usuario)
;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `reporte2view` AS
SELECT
        `TIPO_ESTABLECIMIENTO`.`nombre` AS `Tipo`,
        Concat('<a href="http://192.168.122.243:8080/establecimiento/', `proyecto`.`ESTABLECIMIENTO`.`establecimiento`,'">',
         `proyecto`.`ESTABLECIMIENTO`.`nombre`, '</a>') AS `Establecimiento`,
        `DIMENSION`.`nombre` AS `Dimension`,
        `CATEGORIA`.`nombre` AS `Categoria`,
        `SERVICIO`.`nombre` AS `Servicio`,
        AVG(`CALIFICACION`.`punteo`) AS `Calificacion`
    FROM
        ((((((`ESTABLECIMIENTO`
        JOIN `TIPO_ESTABLECIMIENTO` ON ((`TIPO_ESTABLECIMIENTO`.`tipo_establecimiento` = `ESTABLECIMIENTO`.`tipo_establecimiento`)))
        LEFT JOIN `DIMENSION_ESTABLECIMIENTO` ON ((`DIMENSION_ESTABLECIMIENTO`.`establecimiento` = `ESTABLECIMIENTO`.`establecimiento`)))
        LEFT JOIN `DIMENSION` ON ((`DIMENSION_ESTABLECIMIENTO`.`dimension` = `DIMENSION`.`dimension`)))
        LEFT JOIN `SERVICIO` ON ((`ESTABLECIMIENTO`.`establecimiento` = `SERVICIO`.`establecimiento`)))
        LEFT JOIN `CALIFICACION` ON ((`SERVICIO`.`servicio` = `CALIFICACION`.`servicio`)))
        LEFT JOIN `CATEGORIA` ON ((`DIMENSION`.`dimension` = `CATEGORIA`.`dimension`)))
    GROUP BY `TIPO_ESTABLECIMIENTO`.`nombre` , `ESTABLECIMIENTO`.`nombre` , `DIMENSION`.`nombre` , `CATEGORIA`.`nombre` , `SERVICIO`.`nombre`;

CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `reporte3view` AS
select `TIPO_ESTABLECIMIENTO`.`nombre` AS `Tipo`,
Concat('<a href="http://192.168.122.243:8080/establecimiento/', `proyecto`.`ESTABLECIMIENTO`.`establecimiento`,'">',
         `proyecto`.`ESTABLECIMIENTO`.`nombre`, '</a>') AS `Establecimiento`,
`SERVICIO`.`nombre` AS `Servicio`,
`CALIFICACION`.`punteo` AS `EsferasI`,
(select avg(`CALIFICACION`.`punteo`) AS `Esferas total`
from (`SERVICIO`
join `CALIFICACION` on((`CALIFICACION`.`servicio` = `SERVICIO`.`servicio`)))
where (`SERVICIO`.`establecimiento` = `ESTABLECIMIENTO`.`establecimiento`)) AS `Esferas`,
`CALIFICACION`.`comentario` AS `Comentario`
from (((`ESTABLECIMIENTO`
join `TIPO_ESTABLECIMIENTO` on((`TIPO_ESTABLECIMIENTO`.`tipo_establecimiento` = `ESTABLECIMIENTO`.`tipo_establecimiento`)))
join `SERVICIO` on((`SERVICIO`.`establecimiento` = `ESTABLECIMIENTO`.`establecimiento`)))
join `CALIFICACION` on((`CALIFICACION`.`servicio` = `SERVICIO`.`servicio`))) ;

CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `reporte4view` AS
select `BITACORA`.`usuario` AS `Usuario`,
`BITACORA`.`establecimiento` AS `Establecimiento`,
`BITACORA`.`accion` AS `Accion`,
`BITACORA`.`fecha` AS `Fecha`,
`BITACORA`.`mensaje` AS `Mensaje`
from `BITACORA`;


CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `reporte5view` AS
select `USUARIO`.`usuario` AS `usuario`,
`USUARIO`.`nombre` AS `nombre`,
`USUARIO`.`apellido` AS `apellido`,
if((`USUARIO`.`rol` = 1),'SUPER',if((`USUARIO`.`rol` = 2),'ADMIN','FINAL')) AS `rol`,
Concat('<a href="http://192.168.122.243:8080/establecimiento/', `proyecto`.`ESTABLECIMIENTO`.`establecimiento`,'">',
         `proyecto`.`ESTABLECIMIENTO`.`nombre`, '</a>') AS `Establecimiento`
from (`USUARIO`
left join `ESTABLECIMIENTO` on((`USUARIO`.`id_usuario` = `ESTABLECIMIENTO`.`usuario`))) ;


ALTER TABLE ESTABLECIMIENTO MODIFY nombre VARCHAR(255);

--
-- TABLE: TAREA PROGRAMADA
--

CREATE TABLE REPORTE(
    id INT AUTO_INCREMENT,
    fecha DATETIME NOT NULL,
    calificacion_total INT NOT NULL,
    calificacion_inserciones INT NOT NULL,
    calificacion_actualizaciones INT NOT NULL,
    calificacion_eliminaciones INT NOT NULL,
    caracteristica_total INT NOT NULL,
    caracteristica_inserciones INT NOT NULL,
    caracteristica_actualizaciones INT NOT NULL,
    caracteristica_eliminaciones INT NOT NULL,
    categoria_total INT NOT NULL,
    categoria_inserciones INT NOT NULL,
    categoria_actualizaciones INT NOT NULL,
    categoria_eliminaciones INT NOT NULL,
    detalleServicio_total INT NOT NULL,
    detalleServicio_inserciones INT NOT NULL,
    detalleServicio_actualizaciones INT NOT NULL,
    detalleServicio_eliminaciones INT NOT NULL,
    dimension_total INT NOT NULL,
    dimension_inserciones INT NOT NULL,
    dimension_actualizaciones INT NOT NULL,
    dimension_eliminaciones INT NOT NULL,
    dimensionEstablecimiento_total INT NOT NULL,
    dimensionEstablecimiento_inserciones INT NOT NULL,
    dimensionEstablecimiento_actualizaciones INT NOT NULL,
    dimensionEstablecimiento_eliminaciones INT NOT NULL,
    establecimiento_total INT NOT NULL,
    establecimiento_inserciones INT NOT NULL,
    establecimiento_actualizaciones INT NOT NULL,
    establecimiento_eliminaciones INT NOT NULL,
    reserva_total INT NOT NULL,
    reserva_inserciones INT NOT NULL,
    reserva_actualizaciones INT NOT NULL,
    reserva_eliminaciones INT NOT NULL,
    servicio_total INT NOT NULL,
    servicio_inserciones INT NOT NULL,
    servicio_actualizaciones INT NOT NULL,
    servicio_eliminaciones INT NOT NULL,
    tipoEstablecimiento_total INT NOT NULL,
    tipoEstablecimiento_inserciones INT NOT NULL,
    tipoEstablecimiento_actualizaciones INT NOT NULL,
    tipoEstablecimiento_eliminaciones INT NOT NULL,
    tipoServicio_total INT NOT NULL,
    tipoServicio_inserciones INT NOT NULL,
    tipoServicio_actualizaciones INT NOT NULL,
    tipoServicio_eliminaciones INT NOT NULL,
    usuario_total INT NOT NULL,
    usuario_inserciones INT NOT NULL,
    usuario_actualizaciones INT NOT NULL,
    usuario_eliminaciones INT NOT NULL,
    PRIMARY KEY (id)
)ENGINE=INNODB
;

CREATE TABLE TEMPORAL(
    id INT AUTO_INCREMENT,
    calificacion_inserciones INT NOT NULL,
    calificacion_actualizaciones INT NOT NULL,
    calificacion_eliminaciones INT NOT NULL,
    caracteristica_inserciones INT NOT NULL,
    caracteristica_actualizaciones INT NOT NULL,
    caracteristica_eliminaciones INT NOT NULL,
    categoria_inserciones INT NOT NULL,
    categoria_actualizaciones INT NOT NULL,
    categoria_eliminaciones INT NOT NULL,
    detalleServicio_inserciones INT NOT NULL,
    detalleServicio_actualizaciones INT NOT NULL,
    detalleServicio_eliminaciones INT NOT NULL,
    dimension_inserciones INT NOT NULL,
    dimension_actualizaciones INT NOT NULL,
    dimension_eliminaciones INT NOT NULL,
    dimensionEstablecimiento_inserciones INT NOT NULL,
    dimensionEstablecimiento_actualizaciones INT NOT NULL,
    dimensionEstablecimiento_eliminaciones INT NOT NULL,
    establecimiento_inserciones INT NOT NULL,
    establecimiento_actualizaciones INT NOT NULL,
    establecimiento_eliminaciones INT NOT NULL,
    reserva_inserciones INT NOT NULL,
    reserva_actualizaciones INT NOT NULL,
    reserva_eliminaciones INT NOT NULL,
    servicio_inserciones INT NOT NULL,
    servicio_actualizaciones INT NOT NULL,
    servicio_eliminaciones INT NOT NULL,
    tipoEstablecimiento_inserciones INT NOT NULL,
    tipoEstablecimiento_actualizaciones INT NOT NULL,
    tipoEstablecimiento_eliminaciones INT NOT NULL,
    tipoServicio_inserciones INT NOT NULL,
    tipoServicio_actualizaciones INT NOT NULL,
    tipoServicio_eliminaciones INT NOT NULL,
    usuario_inserciones INT NOT NULL,
    usuario_actualizaciones INT NOT NULL,
    usuario_eliminaciones INT NOT NULL,
    PRIMARY KEY (id)
)ENGINE=INNODB
;

INSERT INTO REPORTE VALUES(0,NOW(),0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
INSERT INTO TEMPORAL VALUES(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

CREATE
    ALGORITHM = UNDEFINED
    DEFINER = `root`@`localhost`
    SQL SECURITY DEFINER
VIEW `proyecto`.`jobview` AS
    SELECT
        'Calificacion' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`calificacion_inserciones` AS `inserciones`,
        `proyecto`.`REPORTE`.`calificacion_actualizaciones` AS `actualizacioens`,
        `proyecto`.`REPORTE`.`calificacion_eliminaciones` AS `eliminaciones`,
        `proyecto`.`REPORTE`.`calificacion_total` AS `total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Caracteristica' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`caracteristica_inserciones` AS `caracteristica_inserciones`,
        `proyecto`.`REPORTE`.`caracteristica_actualizaciones` AS `caracteristica_actualizaciones`,
        `proyecto`.`REPORTE`.`caracteristica_eliminaciones` AS `caracteristica_eliminaciones`,
        `proyecto`.`REPORTE`.`caracteristica_total` AS `caracteristica_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Categoria' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`categoria_inserciones` AS `categoria_inserciones`,
        `proyecto`.`REPORTE`.`categoria_actualizaciones` AS `categoria_actualizaciones`,
        `proyecto`.`REPORTE`.`categoria_eliminaciones` AS `categoria_eliminaciones`,
        `proyecto`.`REPORTE`.`categoria_total` AS `categoria_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Detalle Servicio' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`detalleServicio_inserciones` AS `detalleServicio_inserciones`,
        `proyecto`.`REPORTE`.`detalleServicio_actualizaciones` AS `detalleServicio_actualizaciones`,
        `proyecto`.`REPORTE`.`detalleServicio_eliminaciones` AS `detalleServicio_eliminaciones`,
        `proyecto`.`REPORTE`.`detalleServicio_total` AS `detalleServicio_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Dimension' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`dimension_inserciones` AS `dimension_inserciones`,
        `proyecto`.`REPORTE`.`dimension_actualizaciones` AS `dimension_actualizaciones`,
        `proyecto`.`REPORTE`.`dimension_eliminaciones` AS `dimension_eliminaciones`,
        `proyecto`.`REPORTE`.`dimension_total` AS `dimension_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Dimension Establecimiento' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`dimensionEstablecimiento_inserciones` AS `dimensionEstablecimiento_inserciones`,
        `proyecto`.`REPORTE`.`dimensionEstablecimiento_actualizaciones` AS `dimensionEstablecimiento_actualizaciones`,
        `proyecto`.`REPORTE`.`dimensionEstablecimiento_eliminaciones` AS `dimensionEstablecimiento_eliminaciones`,
        `proyecto`.`REPORTE`.`dimensionEstablecimiento_total` AS `dimensionEstablecimiento_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Establecimiento' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`establecimiento_inserciones` AS `establecimiento_inserciones`,
        `proyecto`.`REPORTE`.`establecimiento_actualizaciones` AS `establecimiento_actualizaciones`,
        `proyecto`.`REPORTE`.`establecimiento_eliminaciones` AS `establecimiento_eliminaciones`,
        `proyecto`.`REPORTE`.`establecimiento_total` AS `establecimiento_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Reserva' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`reserva_inserciones` AS `reserva_inserciones`,
        `proyecto`.`REPORTE`.`reserva_actualizaciones` AS `reserva_actualizaciones`,
        `proyecto`.`REPORTE`.`reserva_eliminaciones` AS `reserva_eliminaciones`,
        `proyecto`.`REPORTE`.`reserva_total` AS `reserva_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Servicio' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`servicio_inserciones` AS `servicio_inserciones`,
        `proyecto`.`REPORTE`.`servicio_actualizaciones` AS `servicio_actualizaciones`,
        `proyecto`.`REPORTE`.`servicio_eliminaciones` AS `servicio_eliminaciones`,
        `proyecto`.`REPORTE`.`servicio_total` AS `servicio_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Tipo Establecimiento' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`tipoEstablecimiento_inserciones` AS `tipoEstablecimiento_inserciones`,
        `proyecto`.`REPORTE`.`tipoEstablecimiento_actualizaciones` AS `tipoEstablecimiento_actualizaciones`,
        `proyecto`.`REPORTE`.`tipoEstablecimiento_eliminaciones` AS `tipoEstablecimiento_eliminaciones`,
        `proyecto`.`REPORTE`.`tipoEstablecimiento_total` AS `tipoEstablecimiento_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Tipo Servicio' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`tipoServicio_inserciones` AS `tipoServicio_inserciones`,
        `proyecto`.`REPORTE`.`tipoServicio_actualizaciones` AS `tipoServicio_actualizaciones`,
        `proyecto`.`REPORTE`.`tipoServicio_eliminaciones` AS `tipoServicio_eliminaciones`,
        `proyecto`.`REPORTE`.`tipoServicio_total` AS `tipoServicio_total`
    FROM
        `proyecto`.`REPORTE`
    UNION SELECT
        'Usuario' AS `Tabla`,
        `proyecto`.`REPORTE`.`fecha` AS `fecha`,
        `proyecto`.`REPORTE`.`usuario_inserciones` AS `usuario_inserciones`,
        `proyecto`.`REPORTE`.`usuario_actualizaciones` AS `usuario_actualizaciones`,
        `proyecto`.`REPORTE`.`usuario_eliminaciones` AS `usuario_eliminaciones`,
        `proyecto`.`REPORTE`.`usuario_total` AS `usuario_total`
    FROM
        `proyecto`.`REPORTE`;


CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost`
    SQL SECURITY DEFINER
VIEW `proyecto`.`reporte0view` AS
    SELECT 
        'Categoria' AS `Caracteristica`,
        `proyecto`.`DIMENSION`.`nombre` AS `Dimension`,
        COUNT(1) AS `Total`
    FROM
        ((`proyecto`.`ESTABLECIMIENTO`
        JOIN `proyecto`.`DIMENSION_ESTABLECIMIENTO` ON ((`proyecto`.`ESTABLECIMIENTO`.`establecimiento` = `proyecto`.`DIMENSION_ESTABLECIMIENTO`.`establecimiento`)))
        JOIN `proyecto`.`DIMENSION` ON ((`proyecto`.`DIMENSION_ESTABLECIMIENTO`.`dimension` = `proyecto`.`DIMENSION`.`dimension`)))
    GROUP BY `proyecto`.`DIMENSION`.`dimension` 
    UNION SELECT 
        'Tipo establecimeinto' AS `Caracteristica`,
        `proyecto`.`TIPO_ESTABLECIMIENTO`.`nombre` AS `TipoEstablecimiento`,
        COUNT(1) AS `Total`
    FROM
        (`proyecto`.`ESTABLECIMIENTO`
        JOIN `proyecto`.`TIPO_ESTABLECIMIENTO` ON ((`proyecto`.`TIPO_ESTABLECIMIENTO`.`tipo_establecimiento` = `proyecto`.`ESTABLECIMIENTO`.`tipo_establecimiento`)))
    GROUP BY `proyecto`.`TIPO_ESTABLECIMIENTO`.`tipo_establecimiento` 
    UNION SELECT 
        'Servicio' AS `Caracteristica`,
        `proyecto`.`SERVICIO`.`nombre` AS `Servicio`,
        COUNT(1) AS `Total`
    FROM
        (`proyecto`.`ESTABLECIMIENTO`
        JOIN `proyecto`.`SERVICIO` ON ((`proyecto`.`ESTABLECIMIENTO`.`establecimiento` = `proyecto`.`SERVICIO`.`establecimiento`)))
    GROUP BY `proyecto`.`SERVICIO`.`nombre`;

CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `proyecto`.`reporte1view` AS
    SELECT 
        `proyecto`.`USUARIO`.`usuario` AS `Usuario`,
        CONCAT(`proyecto`.`USUARIO`.`nombre`,
                ' ',
                `proyecto`.`USUARIO`.`apellido`) AS `Nombre`,
        Concat('<a href="http://192.168.122.243:8080/establecimiento/', `proyecto`.`ESTABLECIMIENTO`.`establecimiento`,'">',
         `proyecto`.`ESTABLECIMIENTO`.`nombre`, '</a>') AS `Establecimiento`,
        IF((`proyecto`.`ESTABLECIMIENTO`.`oficial` = 1),
            'ofcial',
            'no oficial') AS `Oficial`
    FROM
        (`proyecto`.`USUARIO`
        JOIN `proyecto`.`ESTABLECIMIENTO` ON ((`proyecto`.`USUARIO`.`id_usuario` = `proyecto`.`ESTABLECIMIENTO`.`usuario`)));

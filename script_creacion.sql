--
-- ER/Studio 8.0 SQL Code Generation
-- Company :      CR
-- Project :      Bases2.DM1
-- Author :       Carlos Rodriguez
--
-- Date Created : Monday, October 12, 2015 12:05:17
-- Target DBMS : MySQL 5.x
--

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
    nombre                  VARCHAR(20)     NOT NULL,
    posicion                VARCHAR(30),
    descripcion             VARCHAR(200),
    punteo                  INT,
    tipo_establecimiento    INT             NOT NULL,
    oficial INT NOT NULL,
    PRIMARY KEY (establecimiento)
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
    nombre VARCHAR(20) NOT NULL,
    PRIMARY KEY (servicio)
)ENGINE=INNODB
;



-- 
-- TABLE: TIPO_ESTABLECIMIENTO 
--

CREATE TABLE TIPO_ESTABLECIMIENTO(
    tipo_establecimiento    INT             AUTO_INCREMENT,
    nombre                  VARCHAR(20)     NOT NULL,
    descripcion             VARCHAR(200),
    PRIMARY KEY (tipo_establecimiento)
)ENGINE=INNODB
;



-- 
-- TABLE: TIPO_SERVICIO 
--

CREATE TABLE TIPO_SERVICIO(
    tipo_servicio    INT             AUTO_INCREMENT,
    nombre           VARCHAR(20)     NOT NULL,
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

ALTER TABLE SERVICIO ADD CONSTRAINT RefESTABLECIMIENTO5 
    FOREIGN KEY (establecimiento)
    REFERENCES ESTABLECIMIENTO(establecimiento)
;

ALTER TABLE SERVICIO ADD CONSTRAINT RefTIPO_SERVICIO6 
    FOREIGN KEY (tipo_servicio)
    REFERENCES TIPO_SERVICIO(tipo_servicio)
;
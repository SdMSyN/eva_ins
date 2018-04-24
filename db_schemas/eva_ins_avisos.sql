-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2016 a las 23:06:58
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `eva_ins`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_asig_alum`
--

CREATE TABLE IF NOT EXISTS `aviso_asig_alum` (
  `id` int(11) NOT NULL,
  `aviso_info_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `enterado` int(11) DEFAULT NULL,
  `fecha_enterado` datetime DEFAULT NULL,
  `creado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_asig_tutor`
--

CREATE TABLE IF NOT EXISTS `aviso_asig_tutor` (
  `id` int(11) NOT NULL,
  `aviso_info_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `enterado` int(11) DEFAULT NULL,
  `fecha_enterado` datetime DEFAULT NULL,
  `creado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_info`
--

CREATE TABLE IF NOT EXISTS `aviso_info` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `tipo_aviso_id` int(11) NOT NULL,
  `dirigido_a` int(11) DEFAULT NULL,
  `creado_por` int(11) NOT NULL,
  `perfil_creador` int(11) NOT NULL,
  `escuela_id` int(11) NOT NULL,
  `creado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_tipo`
--

CREATE TABLE IF NOT EXISTS `aviso_tipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aviso_asig_alum`
--
ALTER TABLE `aviso_asig_alum`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aviso_asig_tutor`
--
ALTER TABLE `aviso_asig_tutor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aviso_info`
--
ALTER TABLE `aviso_info`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aviso_tipo`
--
ALTER TABLE `aviso_tipo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aviso_asig_alum`
--
ALTER TABLE `aviso_asig_alum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT de la tabla `aviso_asig_tutor`
--
ALTER TABLE `aviso_asig_tutor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `aviso_info`
--
ALTER TABLE `aviso_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `aviso_tipo`
--
ALTER TABLE `aviso_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

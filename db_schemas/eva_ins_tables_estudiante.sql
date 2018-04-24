-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-10-2016 a las 02:26:12
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
-- Estructura de tabla para la tabla `est_exa_respuestas_tmp`
--

CREATE TABLE IF NOT EXISTS `est_exa_respuestas_tmp` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `exa_info_id` int(11) NOT NULL,
  `exa_info_asig_alum_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `tipo_resp_id` int(11) NOT NULL,
  `respuesta_id` int(11) NOT NULL,
  `respuesta` varchar(250) DEFAULT NULL,
  `creado` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_exa_result_info`
--

CREATE TABLE IF NOT EXISTS `est_exa_result_info` (
  `id` int(11) NOT NULL,
  `exa_info_id` int(11) NOT NULL,
  `exa_info_asig_alum_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `num_pregs` int(11) NOT NULL,
  `preg_contestadas` int(11) NOT NULL,
  `preg_no_contestadas` int(11) NOT NULL,
  `resp_buenas` int(11) NOT NULL,
  `resp_malas` int(11) NOT NULL,
  `valor_exa` int(11) NOT NULL,
  `valor_exa_alum` int(11) NOT NULL,
  `calificacion` float NOT NULL,
  `porcentaje` float NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `creado` date NOT NULL,
  `actualizado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_exa_result_preguntas`
--

CREATE TABLE IF NOT EXISTS `est_exa_result_preguntas` (
  `id` int(11) NOT NULL,
  `exa_info_id` int(11) NOT NULL,
  `exa_info_asig_alum_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `tipo_resp_id` int(11) NOT NULL,
  `respuesta_id` int(11) NOT NULL,
  `respuesta` varchar(250) NOT NULL,
  `exa_result_info_id` int(11) NOT NULL,
  `calificacion` float NOT NULL,
  `creado` date NOT NULL,
  `actualizado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_exa_tiempos`
--

CREATE TABLE IF NOT EXISTS `est_exa_tiempos` (
  `id` int(11) NOT NULL,
  `exa_info_id` int(11) NOT NULL,
  `exa_info_asig_alum_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time DEFAULT NULL,
  `tiempo` time DEFAULT NULL,
  `creado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `est_exa_respuestas_tmp`
--
ALTER TABLE `est_exa_respuestas_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `est_exa_result_info`
--
ALTER TABLE `est_exa_result_info`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `est_exa_result_preguntas`
--
ALTER TABLE `est_exa_result_preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `est_exa_tiempos`
--
ALTER TABLE `est_exa_tiempos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `est_exa_respuestas_tmp`
--
ALTER TABLE `est_exa_respuestas_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `est_exa_result_info`
--
ALTER TABLE `est_exa_result_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `est_exa_result_preguntas`
--
ALTER TABLE `est_exa_result_preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `est_exa_tiempos`
--
ALTER TABLE `est_exa_tiempos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

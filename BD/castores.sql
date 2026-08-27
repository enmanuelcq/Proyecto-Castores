-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-08-2026 a las 03:26:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `castores`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atleta`
--

CREATE TABLE `atleta` (
  `Atleta_id` varchar(15) NOT NULL,
  `1er_nombre` varchar(40) NOT NULL,
  `2do_nombre` varchar(40) NOT NULL,
  `1er_apellido` varchar(40) NOT NULL,
  `2do_apellido` varchar(40) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `pie_dominante` varchar(20) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `foto` varchar(400) NOT NULL,
  `documento_cedula` varchar(400) DEFAULT '',
  `documento_partida` varchar(400) DEFAULT '',
  `Atleta_status` tinyint(1) NOT NULL,
  `Uniforme_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `atleta`
--

INSERT INTO `atleta` (`Atleta_id`, `1er_nombre`, `2do_nombre`, `1er_apellido`, `2do_apellido`, `fecha_nacimiento`, `sexo`, `cedula`, `pie_dominante`, `direccion`, `foto`, `documento_cedula`, `documento_partida`, `Atleta_status`, `Uniforme_id`) VALUES
('atl_6a59935c88b', 'Carlos', 'Andrés', 'perez', 'camacho', '2019-02-08', 'Masculino', '2307911', 'Derecho', 'San Antonio', 'fotos_atletas/atleta_6a866baaebe07.png', 'documentos/cedula_6a866baaee75d.pdf', '', 1, 59),
('atl_6a59996790a', 'Carlos', 'Miguel', 'Camacho', 'Castillo', '2018-12-25', 'Masculino', '3699882', 'Ambidiestro', 'dhdhfjdhfjhdf', 'fotos_atletas/atleta_6a878e90376f7.png', 'documentos/cedula_6a8787e462234.pdf', '', 1, 121),
('atl_6a599a02aaa', 'Abraham', 'mikel', 'González', 'Pérez', '2018-12-12', 'Masculino', '', 'Ambidiestro', 'edfdfedfe', 'fotos_atletas/atleta_6a878c15ef0b6.png', 'documentos/cedula_6a8788dd2039d.pdf', '', 1, 122),
('atl_6a59a404403', 'NORA', 'DEL VALLE', 'González', 'camacho', '2018-12-18', 'Masculino', '23079110', 'Derecho', 'edfdfedfe', 'fotos_atletas/atleta_6a878e9e36fcf.png', '', '', 1, 140),
('atl_6a59a5fe7a5', 'Abraham', 'Alejandro', 'Camacho', 'Perez', '2018-12-05', 'Femenino', '', 'Ambidiestro', 'Calle Principal #123, Urbanizaci??n Los Pinos', '', '', '', 0, 141),
('atl_6a5ed7b875c', 'Carlos', 'Alejandro', 'perez', 'DUARTE', '2023-12-27', 'Masculino', '', 'Ambidiestro', 'dhdhfjdhfjhdf', 'fotos_atletas/atleta_6a879573c8a0b.png', 'documentos/cedula_6a8789471e002.pdf', '', 1, 142),
('atl_6a8600a77ee', 'Carlos', 'Andrés', 'perez', 'camacho', '2019-02-06', 'Masculino', '', 'Derecho', 'Calle Principal #123, Urbanización Los Pinos', 'fotos_atletas/atleta_6a8600a77df82.png', '', '', 1, 144),
('atl_6a865fde048', 'Pedro', 'DEL VALLE', 'MEDINA', 'camacho', '2020-02-26', 'Masculino', '', 'Ambidiestro', 'Calle Principal #123, Urbanización Los Pinos', 'fotos_atletas/atleta_6a878eac28188.png', '', '', 1, 145),
('atl_6a8667c4287', 'Carlos', 'Andrés', 'perez', 'camacho', '2023-06-19', 'Masculino', '', 'Derecho', 'Calle Principal #123, Urbanización Los Pinos', 'fotos_atletas/atleta_6a8667c40285f.png', 'documentos/cedula_6a8667c406a6e.pdf', 'documentos/partida_6a8667c4075a8.pdf', 1, 146);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atleta_representante`
--

CREATE TABLE `atleta_representante` (
  `AR_id` int(11) NOT NULL,
  `parentesco` varchar(40) NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `cedula_representante` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `atleta_representante`
--

INSERT INTO `atleta_representante` (`AR_id`, `parentesco`, `fecha_inscripcion`, `Atleta_id`, `cedula_representante`) VALUES
(57, 'Representante legal', '2026-07-17', 'atl_6a59935c88b', 'v32323232'),
(119, 'Representante legal', '2026-07-17', 'atl_6a59996790a', 'V-32499689'),
(120, 'Representante legal', '2026-07-17', 'atl_6a599a02aaa', 'V10053620'),
(138, 'Representante legal', '2026-07-17', 'atl_6a59a404403', 'V-11111112'),
(139, 'Representante legal', '2026-07-17', 'atl_6a59a5fe7a5', 'V10053625'),
(140, 'Representante legal', '2026-07-21', 'atl_6a5ed7b875c', 'v-22222222'),
(141, 'Representante legal', '2026-08-19', 'atl_6a8600a67d2', 'V21111212'),
(142, 'Padre', '2026-08-19', 'atl_6a8600a67d2', 'V3322222'),
(143, 'Representante legal', '2026-08-19', 'atl_6a8600a77ee', 'V21111212'),
(144, 'Padre', '2026-08-19', 'atl_6a8600a77ee', 'V3322222'),
(145, 'Representante legal', '2026-08-20', 'atl_6a865fde048', 'V3366557'),
(146, 'Representante legal', '2026-08-20', 'atl_6a8667c4287', 'V3366557');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambios`
--

CREATE TABLE `cambios` (
  `cambio_id` int(11) NOT NULL,
  `tiempo` varchar(10) NOT NULL,
  `minuto` varchar(5) NOT NULL,
  `tipo_cambio` enum('Entra','Sale') DEFAULT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `Atleta_sale_id` varchar(15) DEFAULT NULL,
  `partido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campo`
--

CREATE TABLE `campo` (
  `campo_id` int(11) NOT NULL,
  `nombre_campo` varchar(100) NOT NULL,
  `direccion_campo` varchar(500) NOT NULL,
  `mapa_campo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `campo`
--

INSERT INTO `campo` (`campo_id`, `nombre_campo`, `direccion_campo`, `mapa_campo`) VALUES
(1, 'Polideportivo AVP', '', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3924.6405820928508!2d-67.0101995!3d10.3705959!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c2a8caf84406987%3A0x6236f9a6bd44d2b5!2sPOLIDEPORTIVO%20AVP!5e0!3m2!1ses!2sve!4v1787171886760!5m2!1ses!2sve\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"strict-origin-when-cross-origin\"'),
(2, 'Campo Liceo San Jos??', '', 'https://www.google.com/maps/place/Liceo+San+Jos??/@10.3518375,-67.0342911,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgICk4czFgQE!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHRPTWl85cc6DkDtLJb8Sgf8Vl11snHoaEKgiExMk6T-wAAL-Wy0l65YxGtQlgcNfwpjO8sUR4e1wADr2KeBjYkCnc_diRn5Ibc08_ZmH7dslrBi8CVDRLvCaq2o8FwyVSIlsH9ROO8taw%3Dw152-h86-k-no!7i4160!8i2340!4m11!1m2!2m1!1sliceo+san+jose+campo!3m7!1s0x8c2a8cefd646e149:0x97950a25c46e9e8!8m2!3d10.3518375!4d-67.0342911!10e5!15sChRsaWNlbyBzYW4ga');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(11) NOT NULL,
  `sub_categoria` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `sub_categoria`) VALUES
(1, 'Sub-4'),
(2, 'Sub-5'),
(3, 'Sub-6'),
(4, 'Sub-7'),
(5, 'Sub-8'),
(6, 'Sub-9'),
(7, 'Sub-10'),
(8, 'Sub-11'),
(9, 'Sub-12'),
(10, 'Sub-13'),
(11, 'Sub-14'),
(12, 'Sub-15'),
(13, 'Sub-16'),
(14, 'Sub-17'),
(15, 'Sub-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_atleta`
--

CREATE TABLE `categoria_atleta` (
  `anio_id` int(11) NOT NULL,
  `anio` varchar(40) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria_atleta`
--

INSERT INTO `categoria_atleta` (`anio_id`, `anio`, `Atleta_id`, `categoria_id`) VALUES
(2, 'Segundo', 'atl_6a59935c88b', 9),
(64, 'Segundo', 'atl_6a59996790a', 7),
(65, 'Primero', 'atl_6a599a02aaa', 9),
(83, 'Segundo', 'atl_6a59a404403', 4),
(84, 'Segundo', 'atl_6a59a5fe7a5', 9),
(85, 'Primero', 'atl_6a5ed7b875c', 2),
(86, 'Primero', 'atl_6a8600a67d2', 5),
(87, 'Primero', 'atl_6a8600a77ee', 5),
(88, 'Segundo', 'atl_6a865fde048', 7),
(89, 'Primero', 'atl_6a8667c4287', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_entrenamiento`
--

CREATE TABLE `categoria_entrenamiento` (
  `convocatoria_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `entrenamiento_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colegio`
--

CREATE TABLE `colegio` (
  `colegio_id` int(11) NOT NULL,
  `nombre_colegio` varchar(100) NOT NULL,
  `direccion_colegio` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `colegio`
--

INSERT INTO `colegio` (`colegio_id`, `nombre_colegio`, `direccion_colegio`) VALUES
(1, 'Colegio San Jos??', 'Panamericana'),
(2, 'martin tovar y tovar', 'Madre Isabel'),
(7, 'Santa mar??a', 'Panamericana'),
(9, 'Carlos Gauna', 'Panamericana'),
(10, 'Colegio San José', 'Panamericana'),
(11, 'Santa maría', 'Panamericana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `descripcion`, `fecha_actualizacion`) VALUES
(1, 'monto_mensual_usd', '50.00', 'Monto de la mensualidad en d??lares', '2026-07-21 21:35:40'),
(2, 'tasa_dolar_bcv', '777.4161', 'Tasa de cambio del d??lar BCV obtenida por API', '2026-08-20 19:33:29'),
(3, 'ultima_actualizacion_tasa', '2026-08-21 01:33:29', 'Fecha de la ??ltima actualizaci??n de la tasa', '2026-08-20 19:33:29'),
(10, 'ultima_actualizacion_cuotas', '2026-07-22 03:35:40', NULL, '2026-07-21 21:35:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convocado`
--

CREATE TABLE `convocado` (
  `convocado_id` int(11) NOT NULL,
  `partido_id` int(11) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `minutos_jugados` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `convocado`
--

INSERT INTO `convocado` (`convocado_id`, `partido_id`, `Atleta_id`, `minutos_jugados`) VALUES
(1, 15, 'atl_6a59a5fe7a5', NULL),
(2, 15, 'atl_6a599a02aaa', NULL),
(3, 15, 'atl_6a59935c88b', NULL),
(6, 15, 'atl_6a59996790a', NULL),
(7, 9, 'atl_6a59996790a', NULL),
(8, 9, 'atl_6a865fde048', NULL),
(9, 9, 'atl_6a8600a77ee', NULL),
(10, 14, 'atl_6a59996790a', NULL),
(11, 14, 'atl_6a865fde048', NULL),
(12, 14, 'atl_6a59935c88b', NULL),
(13, 14, 'atl_6a599a02aaa', NULL),
(14, 14, 'atl_6a59a5fe7a5', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_mensual`
--

CREATE TABLE `cuota_mensual` (
  `cuota_id` int(11) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `mes` int(2) NOT NULL,
  `anio` int(4) NOT NULL,
  `monto_establecido` decimal(10,2) NOT NULL,
  `estatus_cuota` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `cuota_mensual`
--

INSERT INTO `cuota_mensual` (`cuota_id`, `Atleta_id`, `mes`, `anio`, `monto_establecido`, `estatus_cuota`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'atl_6a59935c88b', 7, 2026, 14744.64, 'Pagado', '2026-07-21 12:38:57', '2026-07-21 19:41:54'),
(2, 'atl_6a59935c88b', 8, 2026, 36861.61, 'Parcial', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(3, 'atl_6a59935c88b', 9, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(4, 'atl_6a59935c88b', 10, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(5, 'atl_6a59935c88b', 11, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(6, 'atl_6a59935c88b', 12, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(7, 'atl_6a59996790a', 7, 2026, 14744.64, 'Pagado', '2026-07-21 12:38:57', '2026-07-21 20:23:37'),
(8, 'atl_6a59996790a', 8, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(9, 'atl_6a59996790a', 9, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(10, 'atl_6a59996790a', 10, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(11, 'atl_6a59996790a', 11, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(12, 'atl_6a59996790a', 12, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(13, 'atl_6a599a02aaa', 7, 2026, 36861.61, 'Parcial', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(14, 'atl_6a599a02aaa', 8, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(15, 'atl_6a599a02aaa', 9, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(16, 'atl_6a599a02aaa', 10, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(17, 'atl_6a599a02aaa', 11, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(18, 'atl_6a599a02aaa', 12, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(19, 'atl_6a59a404403', 7, 2026, 36861.61, 'Parcial', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(20, 'atl_6a59a404403', 8, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(21, 'atl_6a59a404403', 9, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(22, 'atl_6a59a404403', 10, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(23, 'atl_6a59a404403', 11, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(24, 'atl_6a59a404403', 12, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(25, 'atl_6a59a5fe7a5', 7, 2026, 36861.61, 'Pagado', '2026-07-21 12:38:57', '2026-07-21 21:12:48'),
(26, 'atl_6a59a5fe7a5', 8, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(27, 'atl_6a59a5fe7a5', 9, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(28, 'atl_6a59a5fe7a5', 10, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(29, 'atl_6a59a5fe7a5', 11, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(30, 'atl_6a59a5fe7a5', 12, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(31, 'atl_6a5ed7b875c', 7, 2026, 36861.61, 'Pagado', '2026-07-21 12:38:57', '2026-07-21 20:58:54'),
(32, 'atl_6a5ed7b875c', 8, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(33, 'atl_6a5ed7b875c', 9, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(34, 'atl_6a5ed7b875c', 10, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(35, 'atl_6a5ed7b875c', 11, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(36, 'atl_6a5ed7b875c', 12, 2026, 36861.61, 'Pendiente', '2026-07-21 12:38:57', '2026-07-21 21:35:40'),
(37, 'atl_6a8600a67d2', 8, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:46', '2026-08-19 15:14:46'),
(38, 'atl_6a8600a67d2', 9, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:46', '2026-08-19 15:14:46'),
(39, 'atl_6a8600a67d2', 10, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:46', '2026-08-19 15:14:46'),
(40, 'atl_6a8600a67d2', 11, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:46', '2026-08-19 15:14:46'),
(41, 'atl_6a8600a67d2', 12, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:46', '2026-08-19 15:14:46'),
(42, 'atl_6a8600a77ee', 8, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:47', '2026-08-19 15:14:47'),
(43, 'atl_6a8600a77ee', 9, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:47', '2026-08-19 15:14:47'),
(44, 'atl_6a8600a77ee', 10, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:47', '2026-08-19 15:14:47'),
(45, 'atl_6a8600a77ee', 11, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:47', '2026-08-19 15:14:47'),
(46, 'atl_6a8600a77ee', 12, 2026, 38627.21, 'Pendiente', '2026-08-19 15:14:47', '2026-08-19 15:14:47'),
(47, 'atl_6a865fde048', 8, 2026, 38766.78, 'Pendiente', '2026-08-19 22:01:02', '2026-08-19 22:01:02'),
(48, 'atl_6a865fde048', 9, 2026, 38766.78, 'Pendiente', '2026-08-19 22:01:02', '2026-08-19 22:01:02'),
(49, 'atl_6a865fde048', 10, 2026, 38766.78, 'Pendiente', '2026-08-19 22:01:02', '2026-08-19 22:01:02'),
(50, 'atl_6a865fde048', 11, 2026, 38766.78, 'Pendiente', '2026-08-19 22:01:02', '2026-08-19 22:01:02'),
(51, 'atl_6a865fde048', 12, 2026, 38766.78, 'Pendiente', '2026-08-19 22:01:02', '2026-08-19 22:01:02'),
(52, 'atl_6a8667c4287', 8, 2026, 38766.78, 'Pendiente', '2026-08-19 22:34:44', '2026-08-19 22:34:44'),
(53, 'atl_6a8667c4287', 9, 2026, 38766.78, 'Pendiente', '2026-08-19 22:34:44', '2026-08-19 22:34:44'),
(54, 'atl_6a8667c4287', 10, 2026, 38766.78, 'Pendiente', '2026-08-19 22:34:44', '2026-08-19 22:34:44'),
(55, 'atl_6a8667c4287', 11, 2026, 38766.78, 'Pendiente', '2026-08-19 22:34:44', '2026-08-19 22:34:44'),
(56, 'atl_6a8667c4287', 12, 2026, 38766.78, 'Pendiente', '2026-08-19 22:34:44', '2026-08-19 22:34:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desempe??o_entrenamiento`
--

CREATE TABLE `desempe??o_entrenamiento` (
  `desempenio_id` int(11) NOT NULL,
  `asistencia` tinyint(1) NOT NULL,
  `desempenio` varchar(500) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `entrenamiento_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamiento`
--

CREATE TABLE `entrenamiento` (
  `entrenamiento_id` int(11) NOT NULL,
  `fecha_entrenamiento` datetime NOT NULL,
  `objetivo_entrenamiento` varchar(500) NOT NULL,
  `tiempo_estimado` time NOT NULL,
  `tipo_entrenamiento` varchar(100) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_rival`
--

CREATE TABLE `equipo_rival` (
  `equipo_id` int(11) NOT NULL,
  `nombre_equipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `equipo_rival`
--

INSERT INTO `equipo_rival` (`equipo_id`, `nombre_equipo`) VALUES
(1, 'Caracas FC'),
(2, 'Club Atl??tico Colinas de Miranda'),
(3, 'San Jos??');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `goles`
--

CREATE TABLE `goles` (
  `gol_id` int(11) NOT NULL,
  `cant_goles` int(11) NOT NULL,
  `tiempo` time NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `partido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `goles`
--

INSERT INTO `goles` (`gol_id`, `cant_goles`, `tiempo`, `descripcion`, `Atleta_id`, `partido_id`) VALUES
(1, 1, '05:00:00', 'Gol de Cabeza', 'atl_6a59996790a', 12),
(2, 1, '05:00:00', 'Gol de Cabeza', 'atl_6a59996790a', 10),
(3, 1, '15:00:00', 'Gol de Penalti', 'atl_6a59996790a', 10),
(4, 1, '05:00:00', 'Gol de Cabeza', 'atl_6a865fde048', 9),
(5, 1, '12:00:00', 'Gol de Cabeza', 'atl_6a865fde048', 9),
(6, 1, '03:00:00', 'Gol de Cabeza', 'atl_6a59935c88b', 14),
(7, 1, '25:00:00', 'Gol de regate', 'atl_6a59935c88b', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado`
--

CREATE TABLE `grado` (
  `grado_id` int(11) NOT NULL,
  `grado` varchar(40) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `colegio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`grado_id`, `grado`, `Atleta_id`, `colegio_id`) VALUES
(2, '3ro grado', 'atl_6a59935c88b', 9),
(64, '2do grado', 'atl_6a59996790a', 10),
(65, '4to grado', 'atl_6a599a02aaa', 10),
(83, '5to grado', 'atl_6a59a404403', 10),
(84, '4to grado', 'atl_6a59a5fe7a5', 1),
(85, '1er año', 'atl_6a5ed7b875c', 11),
(86, '6to grado', 'atl_6a8600a67d2', 10),
(87, '6to grado', 'atl_6a8600a77ee', 10),
(88, '4to grado', 'atl_6a865fde048', 10),
(89, '3ro grado', 'atl_6a8667c4287', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hobbies`
--

CREATE TABLE `hobbies` (
  `hobbie_id` int(11) NOT NULL,
  `nombre_hobbie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `hobbies`
--

INSERT INTO `hobbies` (`hobbie_id`, `nombre_hobbie`) VALUES
(1, 'Cantar'),
(2, 'tenis'),
(3, 'Pintura'),
(13, 'F??tbol'),
(14, 'Baloncesto'),
(15, 'Voleibol'),
(16, 'Nataci??n'),
(17, 'Atletismo'),
(18, 'Ciclismo'),
(19, 'Patinaje'),
(20, 'Escalada'),
(21, 'Gimnasia art??stica'),
(22, 'Taekwondo'),
(23, 'Karate'),
(24, 'Judo'),
(25, 'Boxeo'),
(26, 'Esgrima'),
(27, 'Tenis'),
(28, 'P??del'),
(29, 'B??dminton'),
(30, 'Ping pong'),
(31, 'Ajedrez'),
(32, 'Damas'),
(33, 'Puzzle'),
(34, 'Lego'),
(35, 'Rob??tica'),
(36, 'Programaci??n'),
(37, 'Videojuegos'),
(38, 'Dibujo'),
(39, 'Pintura'),
(40, 'Modelado'),
(41, 'Origami'),
(42, 'Papiroflexia'),
(43, 'Manualidades'),
(44, 'Teatro'),
(45, 'Baile'),
(46, 'Canto'),
(47, 'M??sica'),
(48, 'Piano'),
(49, 'Guitarra'),
(50, 'Viol??n'),
(51, 'Bater??a'),
(52, 'Cocina'),
(53, 'Reposter??a'),
(54, 'Jardiner??a'),
(55, 'Acampada'),
(56, 'Senderismo'),
(57, 'Observaci??n de aves'),
(58, 'Astronom??a'),
(59, 'Fotograf??a'),
(60, 'Cine'),
(61, 'Lectura'),
(62, 'Escritura creativa'),
(63, 'Leer'),
(76, 'Coser');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hobbie_atleta`
--

CREATE TABLE `hobbie_atleta` (
  `hobbie_atleta_id` int(11) NOT NULL,
  `horario_hobbie` varchar(100) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `hobbie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `hobbie_atleta`
--

INSERT INTO `hobbie_atleta` (`hobbie_atleta_id`, `horario_hobbie`, `Atleta_id`, `hobbie_id`) VALUES
(3, 'domingo 4:00pm', 'atl_6a59a5fe7a5', 63),
(172, 'domingo 4:00pm', 'atl_6a8600a67d2', 3),
(173, 'domingo 4:00pm', 'atl_6a8600a77ee', 3),
(177, 'Sábados 10am-12pm', 'atl_6a8667c4287', 63),
(178, 'Martes 4:00pm', 'atl_6a59935c88b', 76),
(190, 'domingo 4:00pm', 'atl_6a599a02aaa', 63),
(191, 'Lunes 4:00pm', 'atl_6a59996790a', 14),
(192, 'miercoles', 'atl_6a59a404403', 3),
(193, 'domingo 4:00pm', 'atl_6a865fde048', 14),
(194, 'domingo 4:00pm', 'atl_6a5ed7b875c', 76);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencia`
--

CREATE TABLE `incidencia` (
  `incidencia_id` int(11) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `partido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `incidencia`
--

INSERT INTO `incidencia` (`incidencia_id`, `descripcion`, `Atleta_id`, `partido_id`) VALUES
(1, 'Se lastim?? al chocar con otro ni??o', 'atl_6a59996790a', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `noticia_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `usuario_creador` varchar(20) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `es_destacada` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`noticia_id`, `titulo`, `contenido`, `imagen`, `fecha_publicacion`, `usuario_creador`, `estado`, `es_destacada`) VALUES
(6, 'Bienvenido a la Academia de F??tbol \"Deportivo Castores\"', 'La Escuela de F??tbol EFAR Deportivo Los Castores (ahora Castores Performance Club) es una de las instituciones formativas m??s tradicionales de los Altos Mirandinos. Fundada en 2012, opera en el Polideportivo Los Castores en San Antonio de los Altos, entrenando a m??s de 220 atletas desde la categor??a Sub-5 hasta Sub-17.', 'Public/Uploads/noticias/1784774481_6a617f51cde74.png', '2026-07-23 04:41:00', 'sec_6a5700c52c8c9', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacion`
--

CREATE TABLE `operacion` (
  `operacion_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `pago_id` int(11) NOT NULL,
  `tipo_pago_id` int(11) NOT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `fecha_operacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `operacion`
--

INSERT INTO `operacion` (`operacion_id`, `monto`, `pago_id`, `tipo_pago_id`, `referencia`, `banco`, `fecha_operacion`) VALUES
(1, 5000.00, 0, 2, '252622', NULL, '2026-07-21 10:53:18'),
(2, 2000.00, 2, 2, '336698', NULL, '2026-07-21 11:04:02'),
(3, 5000.00, 3, 2, '123366', 'Venezuela', '2026-07-21 11:20:17'),
(4, 1000.00, 4, 2, '33366', 'Venezuela', '2026-07-21 11:20:44'),
(5, 2000.00, 5, 3, '66666', 'Venezuela', '2026-07-21 19:13:39'),
(6, 10000.00, 6, 1, NULL, NULL, '2026-07-21 19:24:54'),
(7, 4744.64, 7, 1, NULL, NULL, '2026-07-21 19:41:54'),
(8, 2000.00, 8, 1, NULL, NULL, '2026-07-21 19:42:44'),
(9, 3000.00, 9, 1, NULL, NULL, '2026-07-21 19:58:45'),
(10, 14744.64, 10, 1, NULL, NULL, '2026-07-21 20:23:37'),
(11, 3000.00, 11, 1, NULL, NULL, '2026-07-21 20:24:36'),
(12, 3000.00, 12, 1, NULL, NULL, '2026-07-21 20:25:14'),
(13, 5000.00, 13, 1, NULL, NULL, '2026-07-21 20:54:12'),
(14, 36861.61, 14, 1, NULL, NULL, '2026-07-21 20:58:54'),
(15, 36861.61, 15, 1, NULL, NULL, '2026-07-21 21:12:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `pago_id` int(11) NOT NULL,
  `total_pago` decimal(10,2) NOT NULL,
  `concepto_pago` varchar(100) NOT NULL,
  `cedula_representante` varchar(20) NOT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `estatus_pago` varchar(20) DEFAULT 'Completado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`pago_id`, `total_pago`, `concepto_pago`, `cedula_representante`, `fecha_pago`, `estatus_pago`) VALUES
(1, 5000.00, 'Inscripci??n', 'v32323232', '2026-07-21 16:53:18', 'Completado'),
(2, 2000.00, 'Uniforme', 'v32323232', '2026-07-21 17:04:02', 'Completado'),
(3, 5000.00, 'Pago de Torneo - Atleta atl_6a59935c88b', 'v32323232', '2026-07-21 17:20:17', 'Completado'),
(4, 1000.00, 'Pago de Uniforme - Atleta atl_6a59935c88b', 'v32323232', '2026-07-21 17:20:44', 'Completado'),
(5, 2000.00, 'Pago de Arbitraje - Atleta atl_6a59935c88b', 'v32323232', '2026-07-22 01:13:39', 'Completado'),
(6, 10000.00, 'Pago de mensualidad - Atleta atl_6a59935c88b', 'v32323232', '2026-07-22 01:24:54', 'Completado'),
(7, 4744.64, 'Pago de mensualidad - Atleta atl_6a59935c88b', 'v32323232', '2026-07-22 01:41:54', 'Completado'),
(8, 2000.00, 'Pago de mensualidad - Atleta atl_6a59935c88b', 'v32323232', '2026-07-22 01:42:44', 'Completado'),
(9, 3000.00, 'Pago de Inscripci??n - Atleta atl_6a59996790a', 'V-32499689', '2026-07-22 01:58:45', 'Completado'),
(10, 14744.64, 'Pago de mensualidad - Atleta atl_6a59996790a', 'V-32499689', '2026-07-22 02:23:36', 'Completado'),
(11, 3000.00, 'Pago de mensualidad - Atleta atl_6a599a02aaa', 'V10053620', '2026-07-22 02:24:36', 'Completado'),
(12, 3000.00, 'Pago de mensualidad - Atleta atl_6a59a404403', 'V-11111112', '2026-07-22 02:25:14', 'Completado'),
(13, 5000.00, 'Pago de mensualidad - Atleta atl_6a599a02aaa', 'V10053620', '2026-07-22 02:54:12', 'Completado'),
(14, 36861.61, 'Pago de mensualidad - Atleta atl_6a5ed7b875c', 'v-22222222', '2026-07-21 02:58:54', 'Completado'),
(15, 36861.61, 'Pago de mensualidad - Atleta atl_6a59a5fe7a5', 'V10053625', '2026-07-22 03:12:48', 'Completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_cuota`
--

CREATE TABLE `pago_cuota` (
  `pago_cuota_id` int(11) NOT NULL,
  `pago_id` int(11) NOT NULL,
  `cuota_id` int(11) DEFAULT NULL,
  `monto_abonado` decimal(10,2) NOT NULL,
  `fecha_asignacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `pago_cuota`
--

INSERT INTO `pago_cuota` (`pago_cuota_id`, `pago_id`, `cuota_id`, `monto_abonado`, `fecha_asignacion`) VALUES
(1, 6, 1, 10000.00, '2026-07-21 19:24:54'),
(2, 7, 1, 4744.64, '2026-07-21 19:41:54'),
(3, 8, 2, 2000.00, '2026-07-21 19:42:44'),
(4, 10, 7, 14744.64, '2026-07-21 20:23:37'),
(5, 11, 13, 3000.00, '2026-07-21 20:24:36'),
(6, 12, 19, 3000.00, '2026-07-21 20:25:14'),
(7, 13, 13, 5000.00, '2026-07-21 20:54:12'),
(8, 14, 31, 36861.61, '2026-07-21 20:58:54'),
(9, 15, 25, 36861.61, '2026-07-21 21:12:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `partido_id` int(11) NOT NULL,
  `fecha_partido` datetime NOT NULL,
  `duracion` varchar(20) NOT NULL,
  `estado` enum('Pendiente','En Curso','Finalizado') DEFAULT 'Pendiente',
  `tipo_partido` enum('Torneo','Liga','Copa','Amistoso') DEFAULT 'Amistoso',
  `nombre_competencia` varchar(100) DEFAULT NULL,
  `goles_favor` int(3) NOT NULL,
  `goles_contra` int(3) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `campo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`partido_id`, `fecha_partido`, `duracion`, `estado`, `tipo_partido`, `nombre_competencia`, `goles_favor`, `goles_contra`, `categoria_id`, `equipo_id`, `campo_id`) VALUES
(9, '2026-07-31 16:00:00', '90 min', 'Finalizado', 'Amistoso', NULL, 2, 0, 7, 2, 1),
(10, '2026-07-22 17:00:00', '50 min', 'Finalizado', 'Torneo', 'Olga Marina', 2, 0, 7, 2, 1),
(12, '2026-07-22 17:00:00', '30 min', 'Finalizado', 'Amistoso', NULL, 1, 0, 7, 2, 1),
(13, '2026-07-22 17:00:00', '60 min', 'Finalizado', 'Amistoso', NULL, 0, 0, 7, 3, 2),
(14, '2026-08-19 22:10:00', '60 min', 'Finalizado', 'Amistoso', NULL, 2, 0, 9, 1, 1),
(15, '2026-08-31 22:11:00', '30 min', 'Pendiente', 'Amistoso', NULL, 0, 0, 9, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido_tarjetas`
--

CREATE TABLE `partido_tarjetas` (
  `p_t_id` int(11) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `partido_id` int(11) NOT NULL,
  `tarjeta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `partido_tarjetas`
--

INSERT INTO `partido_tarjetas` (`p_t_id`, `Atleta_id`, `partido_id`, `tarjeta_id`) VALUES
(1, 'atl_6a59996790a', 13, 1),
(2, 'atl_6a59935c88b', 14, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_balon_aereo`
--

CREATE TABLE `prueba_balon_aereo` (
  `id_balon_aereo` int(14) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `total_toques` int(5) NOT NULL,
  `toques_pecho` int(5) NOT NULL,
  `toques_rodilla` int(5) NOT NULL,
  `toques_pie` int(5) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_balon_aereo`
--

INSERT INTO `prueba_balon_aereo` (`id_balon_aereo`, `usuario_cedula`, `Atleta_id`, `categoria_id`, `total_toques`, `toques_pecho`, `toques_rodilla`, `toques_pie`, `fecha`) VALUES
(1, '', 'atl_6a59935c88b', 9, 10, 5, 9, 7, '2026-08-21'),
(2, '', 'atl_6a5ed7b875c', 2, 5, 4, 3, 2, '2026-08-25'),
(3, '', 'atl_6a5ed7b875c', 2, 10, 10, 10, 10, '2026-08-25'),
(4, '', 'atl_6a59935c88b', 9, 5, 4, 2, 1, '2026-08-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_conduccion`
--

CREATE TABLE `prueba_conduccion` (
  `id_conduccion` int(14) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `distancia_metros` decimal(6,2) NOT NULL,
  `distancia_obstaculos` decimal(6,2) NOT NULL,
  `tiempo_derecha` decimal(6,2) NOT NULL,
  `tiempo_izquierda` decimal(6,2) NOT NULL,
  `tiempo_ambas_piernas` decimal(6,2) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_conduccion`
--

INSERT INTO `prueba_conduccion` (`id_conduccion`, `usuario_cedula`, `Atleta_id`, `categoria_id`, `distancia_metros`, `distancia_obstaculos`, `tiempo_derecha`, `tiempo_izquierda`, `tiempo_ambas_piernas`, `fecha`, `descripcion`) VALUES
(1, '', 'atl_6a59935c88b', 9, 20.00, 2.00, 10.00, 9.50, 7.50, '2026-08-24', 'ZigZag');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_definicion`
--

CREATE TABLE `prueba_definicion` (
  `id_definicion` int(14) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `distancia_metros` decimal(6,2) NOT NULL,
  `cantidad_tiros` int(5) NOT NULL,
  `centro_bajo` int(5) NOT NULL,
  `centro_alto` int(5) NOT NULL,
  `derecha_bajo` int(5) NOT NULL,
  `derecha_alto` int(5) NOT NULL,
  `izquierda_bajo` int(5) NOT NULL,
  `izquierda_alto` int(5) NOT NULL,
  `empeine` int(5) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_definicion`
--

INSERT INTO `prueba_definicion` (`id_definicion`, `usuario_cedula`, `Atleta_id`, `categoria_id`, `distancia_metros`, `cantidad_tiros`, `centro_bajo`, `centro_alto`, `derecha_bajo`, `derecha_alto`, `izquierda_bajo`, `izquierda_alto`, `empeine`, `fecha`) VALUES
(1, '', 'atl_6a59935c88b', 9, 0.00, 3, 2, 1, 3, 2, 1, 2, 2, '2026-08-25'),
(2, '', 'atl_6a59935c88b', 9, 30.00, 5, 5, 2, 3, 2, 4, 3, 4, '2026-08-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_fuerza`
--

CREATE TABLE `prueba_fuerza` (
  `pruebaf_id` int(11) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `pruebaf_flexiones_1min` int(20) NOT NULL,
  `pruebaf_abdominales_1min` int(20) NOT NULL,
  `pruebaf_sentadillas_1min` int(20) NOT NULL,
  `pruebaf_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_fuerza`
--

INSERT INTO `prueba_fuerza` (`pruebaf_id`, `Atleta_id`, `categoria_id`, `usuario_cedula`, `pruebaf_flexiones_1min`, `pruebaf_abdominales_1min`, `pruebaf_sentadillas_1min`, `pruebaf_fecha`) VALUES
(1, 'atl_6a59935c88b', 9, '', 20, 50, 30, '2026-08-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_pases`
--

CREATE TABLE `prueba_pases` (
  `id_pases` int(14) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `total_pases` int(5) NOT NULL,
  `derecha` int(5) NOT NULL,
  `izquierda` int(5) NOT NULL,
  `primera_intencion` int(5) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_pases`
--

INSERT INTO `prueba_pases` (`id_pases`, `usuario_cedula`, `Atleta_id`, `categoria_id`, `total_pases`, `derecha`, `izquierda`, `primera_intencion`, `fecha`) VALUES
(1, '', 'atl_6a59935c88b', 9, 20, 0, 0, 10, '2026-08-25'),
(2, '', 'atl_6a59935c88b', 9, 30, 18, 15, 12, '2026-08-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_recepcion_balon`
--

CREATE TABLE `prueba_recepcion_balon` (
  `id_recepcion` int(14) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `distancia_metros` decimal(6,2) NOT NULL,
  `cantidad_pases` int(5) NOT NULL,
  `derecha` int(5) NOT NULL,
  `izquierda` int(5) NOT NULL,
  `derecha_orientado` int(5) NOT NULL,
  `izquierda_orientado` int(5) NOT NULL,
  `orientado_giro` int(5) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_recepcion_balon`
--

INSERT INTO `prueba_recepcion_balon` (`id_recepcion`, `usuario_cedula`, `Atleta_id`, `categoria_id`, `distancia_metros`, `cantidad_pases`, `derecha`, `izquierda`, `derecha_orientado`, `izquierda_orientado`, `orientado_giro`, `fecha`) VALUES
(1, '', 'atl_6a59935c88b', 9, 20.00, 20, 15, 16, 20, 10, 9, '2026-08-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_resistencia`
--

CREATE TABLE `prueba_resistencia` (
  `id_resistencia` int(14) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `tiempo_total` decimal(10,2) NOT NULL,
  `pausa` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_resistencia`
--

INSERT INTO `prueba_resistencia` (`id_resistencia`, `usuario_cedula`, `Atleta_id`, `categoria_id`, `tiempo_total`, `pausa`, `fecha`) VALUES
(1, '', 'atl_6a59935c88b', 9, 25.00, 19.00, '2026-08-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_salto`
--

CREATE TABLE `prueba_salto` (
  `pruebaS_id` int(11) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `pruebaS_alt_inicial` decimal(6,2) NOT NULL,
  `pruebaS_1er_salto` decimal(6,2) NOT NULL,
  `pruebaS_2do_salto` decimal(6,2) NOT NULL,
  `pruebaS_3er_salto` decimal(6,2) NOT NULL,
  `pruebaS_Promedio` decimal(6,2) NOT NULL,
  `pruebaS_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_salto`
--

INSERT INTO `prueba_salto` (`pruebaS_id`, `Atleta_id`, `categoria_id`, `usuario_cedula`, `pruebaS_alt_inicial`, `pruebaS_1er_salto`, `pruebaS_2do_salto`, `pruebaS_3er_salto`, `pruebaS_Promedio`, `pruebaS_fecha`) VALUES
(1, 'atl_6a59935c88b', 9, '', 165.00, 24.00, 27.00, 25.00, 25.33, '2026-08-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_velocidad`
--

CREATE TABLE `prueba_velocidad` (
  `pruebav_id` int(11) NOT NULL,
  `Atleta_id` varchar(15) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `pruebav_distancia_mtr` decimal(10,2) NOT NULL,
  `pruebav_1er_intento_seg` decimal(10,2) NOT NULL,
  `pruebav_2do_intento_seg` decimal(10,2) NOT NULL,
  `pruebav_3er_intento_seg` decimal(10,2) NOT NULL,
  `pruebav_Promedio` decimal(10,2) NOT NULL,
  `pruebav_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `prueba_velocidad`
--

INSERT INTO `prueba_velocidad` (`pruebav_id`, `Atleta_id`, `categoria_id`, `usuario_cedula`, `pruebav_distancia_mtr`, `pruebav_1er_intento_seg`, `pruebav_2do_intento_seg`, `pruebav_3er_intento_seg`, `pruebav_Promedio`, `pruebav_fecha`) VALUES
(1, 'atl_6a59935c88b', 9, '', 30.00, 5.50, 7.10, 6.10, 6.23, '2026-08-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representante`
--

CREATE TABLE `representante` (
  `cedula_representante` varchar(20) NOT NULL,
  `nombre_representante` varchar(40) NOT NULL,
  `apellido_representante` varchar(40) NOT NULL,
  `correo_representante` varchar(40) NOT NULL,
  `ocupacion_representante` varchar(40) NOT NULL,
  `telefono_representante` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `representante`
--

INSERT INTO `representante` (`cedula_representante`, `nombre_representante`, `apellido_representante`, `correo_representante`, `ocupacion_representante`, `telefono_representante`) VALUES
('V-11111112', 'Clara Gabriela', 'perez gimenez', 'hghj@adadada.com', 'dgdfgdfgdfgdfgdfg', '0412-0000000'),
('v-22222222', 'fdgfdghdfgf', 'dfgdgdfgdfg', 'hola@example.com', 'dgdfgdfgdfgdfgdfg', '0412-0000000'),
('V-32499689', 'Clara Gabriela', 'Medina Gutierrez', 'clara18medin@gmail.com', 'doctor', '04242476942'),
('V10053620', 'fdgfdghdfgf', 'perez gimenez', 'clara18medin@gmail.com', 'dgdfgdfgdfgdfgdfg', '0412-0000000'),
('V10053625', 'enmanuel carlos', 'perez gimenez', 'clara18medin@gmail.com', 'dgdfgdfgdfgdfgdfg', '0414-2589869'),
('V21111212', 'pedro', 'perez', 'jjhh@gmail.com', 'ingeniero', '+58 412-0011003'),
('v32323232', 'Clara Gabriela', 'Medina Gutierrez', 'usuario@dominio.com', 'doctor', '0412-0000000'),
('V3322222', 'dfdfdf', 'sdfsdfsd', 'ffddfd@dsds.com', 'ama de casa', '0412-3399855'),
('V3366557', 'pedro', 'perez', 'jjhh@gmail.com', 'ingeniero', '0412996663');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `rol_rol` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `rol_rol`) VALUES
(1, 'Administrador'),
(2, 'Secretaria'),
(3, 'Entrenador'),
(4, 'Representante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salud`
--

CREATE TABLE `salud` (
  `salud_id` int(11) NOT NULL,
  `tipo_sangre` varchar(10) NOT NULL,
  `alergias` varchar(100) NOT NULL,
  `padecimiento` varchar(100) NOT NULL,
  `medicamento_no_tomar` varchar(200) NOT NULL,
  `lesiones_previas` varchar(300) NOT NULL,
  `poliza_seguro` varchar(20) DEFAULT NULL,
  `vigencia` date DEFAULT NULL,
  `empresa` varchar(200) DEFAULT NULL,
  `cobertura` varchar(100) DEFAULT NULL,
  `nro_poliza` varchar(30) DEFAULT NULL,
  `nombre_medico` varchar(300) DEFAULT NULL,
  `telefono_medico` varchar(30) DEFAULT NULL,
  `Atleta_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `salud`
--

INSERT INTO `salud` (`salud_id`, `tipo_sangre`, `alergias`, `padecimiento`, `medicamento_no_tomar`, `lesiones_previas`, `poliza_seguro`, `vigencia`, `empresa`, `cobertura`, `nro_poliza`, `nombre_medico`, `telefono_medico`, `Atleta_id`) VALUES
(57, 'O-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'posee', '2026-07-31', 'Seguros Caracas', 'Completa', 'POL-2024-05', 'Dr. cabeza', '0422-4863258', 'atl_6a59935c88b'),
(119, 'O-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a59996790a'),
(120, 'O-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'posee', '2026-12-31', 'Seguros Caracas', 'Completa', 'POL-2024-001', 'Dr. Luis Rodríguez', '0412-3456789', 'atl_6a599a02aaa'),
(138, 'O+', 'Ninguna', 'Asma leve', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a59a404403'),
(139, 'O-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a59a5fe7a5'),
(140, 'O-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a5ed7b875c'),
(141, 'O+', 'Ninguna', 'Asma leve', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a8600a67d2'),
(142, 'O+', 'Ninguna', 'Asma leve', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a8600a77ee'),
(143, 'A-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a865fde048'),
(144, 'A-', 'Ninguna', 'Ninguno', 'Ninguno', 'Ninguna', 'no_posee', NULL, NULL, NULL, NULL, NULL, NULL, 'atl_6a8667c4287');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas`
--

CREATE TABLE `tarjetas` (
  `tarjeta_id` int(11) NOT NULL,
  `tarjeta` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tarjetas`
--

INSERT INTO `tarjetas` (`tarjeta_id`, `tarjeta`) VALUES
(1, 'Amarilla'),
(2, 'Roja'),
(3, 'Doble Amarilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `tipo_pago_id` int(11) NOT NULL,
  `tipo_pago` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`tipo_pago_id`, `tipo_pago`) VALUES
(1, 'Efectivo'),
(2, 'Pago M??vil'),
(3, 'Transferencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uniforme`
--

CREATE TABLE `uniforme` (
  `Uniforme_id` int(11) NOT NULL,
  `franela_talla` varchar(10) NOT NULL,
  `franela_nro` int(3) NOT NULL,
  `short_talla` varchar(10) NOT NULL,
  `zapatos_talla` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `uniforme`
--

INSERT INTO `uniforme` (`Uniforme_id`, `franela_talla`, `franela_nro`, `short_talla`, `zapatos_talla`) VALUES
(59, 'M', 20, 's', '39'),
(121, 'M', 9, 'M', '32'),
(122, 'M', 9, '12', '12'),
(140, '12', 98, '12', '38'),
(141, 'M', 99, 'M', '40'),
(142, '6', 21, '6', '30'),
(144, '10', 21, '12', '36'),
(145, '12', 66, '28', '40'),
(146, '12', 33, '12', '30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` varchar(20) NOT NULL,
  `usuario_nombre` varchar(40) NOT NULL,
  `usuario_apellido` varchar(40) NOT NULL,
  `usuario_cedula` varchar(20) NOT NULL,
  `usuario_telefono` varchar(30) NOT NULL,
  `usuario_usuario` varchar(20) NOT NULL,
  `usuario_clave` varchar(255) NOT NULL,
  `usuario_correo` varchar(40) NOT NULL,
  `usuario_status` tinyint(1) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_nombre`, `usuario_apellido`, `usuario_cedula`, `usuario_telefono`, `usuario_usuario`, `usuario_clave`, `usuario_correo`, `usuario_status`, `rol_id`) VALUES
('adm_6a56fab470e5f', 'admi', 'admi', '1718196', '04123332222', 'admi.admi', '$2y$10$P.fJdZAQZaZf0hyoDDqP1u0vD/6Oh6/IS.m8E0GcKKTpNgfmrllzS', 'admi@example.com', 1, 1),
('adm_6a5847a191a8a', 'representante', 'representante', '33225588', '262253333', 'representante', '$2y$10$IBoXJ0p0tXDC90oznxZ6MuiZxH3vGhuxBTELxBXw0Rg3xTdFNiMIi', 'representante@example.com', 0, 3),
('adm_6a5870b278367', 'Juan', 'Castillo', '22558877', '04243697882', 'sffsssfff', '$2y$10$xLnkWVluv1wWnUp78aMnaumvZFUHufBOv6l5p/s7SssG5/K3rkds2', 'dfgdgdfg@hhh.com', 0, 1),
('adm_6a8370afbd3b3', 'luis', 'diaz', '336622255', '51515515151515', 'luis.diaz', '$2y$10$qRzoQY7rEGIdfI34NU8rAuVYX/iJlDY40h4yY3KSNbyReNq.MoGCi', 'luis@gmail.com', 1, 1),
('adm_6a8370fbc338b', 'luis', 'Castillo', '99663322', '3332626262', 'luis.castillo', '$2y$10$jsmvhgSlnssZ/juWTgoUretXtGWuWBJpwAkix6WsVky.DBdBwLmvy', 'luis@gmail.com', 1, 1),
('adm_6a83728405473', 'pedrito', 'Castillo', '99666885', '04123701222', 'pedrito', '$2y$10$gYljJ6yvzrnkOUlZ1SX1wOpa1YoVg..CeCx1Ck5G5v/oKqMXapBUO', 'pedr@gmail.com', 0, 1),
('adm_6a837490b4d06', 'representante', 'representante', '11447722', '04123701222', 'fgfggf', '$2y$10$7Z2MaQJU.ojbWGUVAAOBEOUs7KSbjHUqIOIZRDFEav/b.uXpmuRGi', 'fgfgfgfgfg@ggg.com', 1, 1),
('adm_6a87918213730', 'Enmanuel', 'Castillo', '36262622', '04123701222', 'enmanuelcq', '$2y$10$TqxMoi.Evftg0VZ70YXP7OAyZ4x3FazrGDGtkz.h0g33H0DaZ.u6K', 'secretari@example.com', 1, 1),
('ent_6a56fbbf78870', 'entrenador', 'entrenador', '1718199', '04123701221', 'entrenador.entrenado', '$2y$10$FsaSt2N4dK9qC5o2tGXDyuciWqgpm8biYVKQX4UBtaA3M0xoImAH6', 'entrenador@example.com', 1, 3),
('ent_6a879541b7b29', 'miguel', 'perez', '1111332', '2622511515', 'miguel.mi', '$2y$10$znJQpA1p8DLd7tIaZZCFX.cdcMXkgnWqdCfhdxLOh14LZPZpxdINW', 'saassas@example.com', 1, 3),
('sec_6a5700c52c8c9', 'secretaria', 'secretaria', '124578960', '04123332222', 'secretaria.1', '$2y$10$pG2N0w90FdhilHUU.Zx48.K6FaC/CrzAR.gddy4Y5ORFzzqo0t40W', 'secretaria@example.com', 1, 2),
('sec_6a585cc9d00b9', 'Juan', 'Perez', '11111111', '0412-9876543', 'jose.perez', '$2y$10$06y.p.HrBAXzEypeeBQmzu/sHqXCZCARB67zxmM0ePP6Vj8Sq0k5K', 'saassas@example.com', 0, 1),
('sec_6a58fdf76c348', 'miguel', 'perez', '33221166', '15151515151', 'miguel.miguel', '$2y$10$hjiLzK1xnsi1nM/JZDVHRe2x2PZvxyiWsEu2R/goqV82LeeMedE4S', 'miguel@example.com', 0, 2),
('sec_6a58ffe3b8d05', 'secretaria', 'Castillo', '33665522', '0011442222', 'dfgdfgdfg', '$2y$10$BKl43ZqGKhPj9E7Lunv3L.OGlqsqT43pc.giWC77RTcaY3mew/stC', 'clara18medin@gnail.com', 0, 2),
('sec_6a59005ca2504', 'luis', 'diaz', '22668844', '1515151515', 'iiijijiji', '$2y$10$S01Dd5f29MKTvqzsmxlFWeiPD5Hr8uUe.1PJS5fx0WnnIDeKgQGqq', 'djiijdf@example.com', 0, 2),
('sec_6a5901a872d31', 'pedrito', 'perez', '12345699', '014725896', 'pedro..a', '$2y$10$witt9FwN2.ZfCkGjQPnrnezuHYEI5El07M0HNUFnq85HVxqou3Q/G', 'pedro@example.com', 0, 2),
('sec_6a59035c31ba8', 'jusus', 'lopez', '12154578', '14722588', 'jesus..', '$2y$10$HoR2gb.4tlTg2F9UsQ6oj.V8HEaguahZCTGXdh1NJH2aD6sW6bUFS', 'jessus@example.com', 0, 2),
('sec_6a59072d18387', 'jose', 'Castillo', '014722333', '014725833', 'asasasasas', '$2y$10$7nXWGQSAqY464c37PX9eGenBm7DihTEO1YX3EvCmJ5Zq.BXhn4XnK', 'assaaasas@example.com', 0, 2),
('sec_6a5957cfc90c9', 'silvana', 'silvana', '369852147', '0101047744', 'silvana', '$2y$10$uaZkDmgpvOGePWQhUSF.a.uL1VDGh2w7RaUwgUpeQvH0OkSSPuweG', 'silvana@example.com', 1, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atleta`
--
ALTER TABLE `atleta`
  ADD PRIMARY KEY (`Atleta_id`),
  ADD KEY `Uniforme_id` (`Uniforme_id`);

--
-- Indices de la tabla `atleta_representante`
--
ALTER TABLE `atleta_representante`
  ADD PRIMARY KEY (`AR_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `cedula_representante` (`cedula_representante`);

--
-- Indices de la tabla `cambios`
--
ALTER TABLE `cambios`
  ADD PRIMARY KEY (`cambio_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `partido_id` (`partido_id`),
  ADD KEY `Atleta_sale_id` (`Atleta_sale_id`);

--
-- Indices de la tabla `campo`
--
ALTER TABLE `campo`
  ADD PRIMARY KEY (`campo_id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `categoria_atleta`
--
ALTER TABLE `categoria_atleta`
  ADD PRIMARY KEY (`anio_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `categoria_entrenamiento`
--
ALTER TABLE `categoria_entrenamiento`
  ADD PRIMARY KEY (`convocatoria_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `entrenamiento_id` (`entrenamiento_id`);

--
-- Indices de la tabla `colegio`
--
ALTER TABLE `colegio`
  ADD PRIMARY KEY (`colegio_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `convocado`
--
ALTER TABLE `convocado`
  ADD PRIMARY KEY (`convocado_id`),
  ADD UNIQUE KEY `uk_convocado` (`partido_id`,`Atleta_id`),
  ADD KEY `fk_conv_atleta` (`Atleta_id`);

--
-- Indices de la tabla `cuota_mensual`
--
ALTER TABLE `cuota_mensual`
  ADD PRIMARY KEY (`cuota_id`),
  ADD KEY `Atleta_id` (`Atleta_id`);

--
-- Indices de la tabla `desempe??o_entrenamiento`
--
ALTER TABLE `desempe??o_entrenamiento`
  ADD PRIMARY KEY (`desempenio_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `entrenamiento_id` (`entrenamiento_id`);

--
-- Indices de la tabla `entrenamiento`
--
ALTER TABLE `entrenamiento`
  ADD PRIMARY KEY (`entrenamiento_id`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `usuario_cedula_2` (`usuario_cedula`);

--
-- Indices de la tabla `equipo_rival`
--
ALTER TABLE `equipo_rival`
  ADD PRIMARY KEY (`equipo_id`);

--
-- Indices de la tabla `goles`
--
ALTER TABLE `goles`
  ADD PRIMARY KEY (`gol_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `partido_id` (`partido_id`);

--
-- Indices de la tabla `grado`
--
ALTER TABLE `grado`
  ADD PRIMARY KEY (`grado_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `colegio_id` (`colegio_id`);

--
-- Indices de la tabla `hobbies`
--
ALTER TABLE `hobbies`
  ADD PRIMARY KEY (`hobbie_id`);

--
-- Indices de la tabla `hobbie_atleta`
--
ALTER TABLE `hobbie_atleta`
  ADD PRIMARY KEY (`hobbie_atleta_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `hobbie_id` (`hobbie_id`);

--
-- Indices de la tabla `incidencia`
--
ALTER TABLE `incidencia`
  ADD PRIMARY KEY (`incidencia_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `partido_id` (`partido_id`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`noticia_id`),
  ADD KEY `usuario_creador` (`usuario_creador`);

--
-- Indices de la tabla `operacion`
--
ALTER TABLE `operacion`
  ADD PRIMARY KEY (`operacion_id`),
  ADD UNIQUE KEY `unique_referencia` (`tipo_pago_id`,`banco`,`referencia`),
  ADD KEY `pago_id` (`pago_id`),
  ADD KEY `tipo_pago_id` (`tipo_pago_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`pago_id`),
  ADD KEY `cedula_representante` (`cedula_representante`);

--
-- Indices de la tabla `pago_cuota`
--
ALTER TABLE `pago_cuota`
  ADD PRIMARY KEY (`pago_cuota_id`),
  ADD KEY `pago_id` (`pago_id`),
  ADD KEY `cuota_id` (`cuota_id`);

--
-- Indices de la tabla `partido`
--
ALTER TABLE `partido`
  ADD PRIMARY KEY (`partido_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `equipo_id` (`equipo_id`),
  ADD KEY `campo_id` (`campo_id`);

--
-- Indices de la tabla `partido_tarjetas`
--
ALTER TABLE `partido_tarjetas`
  ADD PRIMARY KEY (`p_t_id`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `partido_id` (`partido_id`),
  ADD KEY `tarjeta_id` (`tarjeta_id`);

--
-- Indices de la tabla `prueba_balon_aereo`
--
ALTER TABLE `prueba_balon_aereo`
  ADD PRIMARY KEY (`id_balon_aereo`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_conduccion`
--
ALTER TABLE `prueba_conduccion`
  ADD PRIMARY KEY (`id_conduccion`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_definicion`
--
ALTER TABLE `prueba_definicion`
  ADD PRIMARY KEY (`id_definicion`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_fuerza`
--
ALTER TABLE `prueba_fuerza`
  ADD PRIMARY KEY (`pruebaf_id`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_pases`
--
ALTER TABLE `prueba_pases`
  ADD PRIMARY KEY (`id_pases`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_recepcion_balon`
--
ALTER TABLE `prueba_recepcion_balon`
  ADD PRIMARY KEY (`id_recepcion`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_resistencia`
--
ALTER TABLE `prueba_resistencia`
  ADD PRIMARY KEY (`id_resistencia`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_salto`
--
ALTER TABLE `prueba_salto`
  ADD PRIMARY KEY (`pruebaS_id`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prueba_velocidad`
--
ALTER TABLE `prueba_velocidad`
  ADD PRIMARY KEY (`pruebav_id`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `Atleta_id` (`Atleta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `representante`
--
ALTER TABLE `representante`
  ADD PRIMARY KEY (`cedula_representante`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `salud`
--
ALTER TABLE `salud`
  ADD PRIMARY KEY (`salud_id`),
  ADD KEY `Atleta_id` (`Atleta_id`);

--
-- Indices de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`tarjeta_id`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`tipo_pago_id`);

--
-- Indices de la tabla `uniforme`
--
ALTER TABLE `uniforme`
  ADD PRIMARY KEY (`Uniforme_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `usuario_cedula_2` (`usuario_cedula`),
  ADD KEY `usuario_cedula` (`usuario_cedula`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atleta_representante`
--
ALTER TABLE `atleta_representante`
  MODIFY `AR_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT de la tabla `cambios`
--
ALTER TABLE `cambios`
  MODIFY `cambio_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campo`
--
ALTER TABLE `campo`
  MODIFY `campo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categoria_atleta`
--
ALTER TABLE `categoria_atleta`
  MODIFY `anio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `colegio`
--
ALTER TABLE `colegio`
  MODIFY `colegio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `convocado`
--
ALTER TABLE `convocado`
  MODIFY `convocado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `cuota_mensual`
--
ALTER TABLE `cuota_mensual`
  MODIFY `cuota_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `equipo_rival`
--
ALTER TABLE `equipo_rival`
  MODIFY `equipo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `goles`
--
ALTER TABLE `goles`
  MODIFY `gol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `grado`
--
ALTER TABLE `grado`
  MODIFY `grado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `hobbies`
--
ALTER TABLE `hobbies`
  MODIFY `hobbie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `hobbie_atleta`
--
ALTER TABLE `hobbie_atleta`
  MODIFY `hobbie_atleta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT de la tabla `incidencia`
--
ALTER TABLE `incidencia`
  MODIFY `incidencia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `noticia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `operacion`
--
ALTER TABLE `operacion`
  MODIFY `operacion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `pago_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pago_cuota`
--
ALTER TABLE `pago_cuota`
  MODIFY `pago_cuota_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `partido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `partido_tarjetas`
--
ALTER TABLE `partido_tarjetas`
  MODIFY `p_t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `prueba_balon_aereo`
--
ALTER TABLE `prueba_balon_aereo`
  MODIFY `id_balon_aereo` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prueba_conduccion`
--
ALTER TABLE `prueba_conduccion`
  MODIFY `id_conduccion` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prueba_definicion`
--
ALTER TABLE `prueba_definicion`
  MODIFY `id_definicion` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `prueba_pases`
--
ALTER TABLE `prueba_pases`
  MODIFY `id_pases` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `prueba_recepcion_balon`
--
ALTER TABLE `prueba_recepcion_balon`
  MODIFY `id_recepcion` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prueba_resistencia`
--
ALTER TABLE `prueba_resistencia`
  MODIFY `id_resistencia` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `salud`
--
ALTER TABLE `salud`
  MODIFY `salud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  MODIFY `tarjeta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `uniforme`
--
ALTER TABLE `uniforme`
  MODIFY `Uniforme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atleta`
--
ALTER TABLE `atleta`
  ADD CONSTRAINT `atleta_ibfk_1` FOREIGN KEY (`Uniforme_id`) REFERENCES `uniforme` (`Uniforme_id`);

--
-- Filtros para la tabla `atleta_representante`
--
ALTER TABLE `atleta_representante`
  ADD CONSTRAINT `atleta_representante_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `atleta_representante_ibfk_2` FOREIGN KEY (`cedula_representante`) REFERENCES `representante` (`cedula_representante`);

--
-- Filtros para la tabla `cambios`
--
ALTER TABLE `cambios`
  ADD CONSTRAINT `cambios_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `cambios_ibfk_2` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`partido_id`),
  ADD CONSTRAINT `cambios_ibfk_3` FOREIGN KEY (`Atleta_sale_id`) REFERENCES `atleta` (`Atleta_id`);

--
-- Filtros para la tabla `categoria_atleta`
--
ALTER TABLE `categoria_atleta`
  ADD CONSTRAINT `categoria_atleta_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `categoria_atleta_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `categoria_entrenamiento`
--
ALTER TABLE `categoria_entrenamiento`
  ADD CONSTRAINT `categoria_entrenamiento_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `categoria_entrenamiento_ibfk_2` FOREIGN KEY (`entrenamiento_id`) REFERENCES `entrenamiento` (`entrenamiento_id`);

--
-- Filtros para la tabla `convocado`
--
ALTER TABLE `convocado`
  ADD CONSTRAINT `fk_conv_atleta` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_conv_partido` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`partido_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cuota_mensual`
--
ALTER TABLE `cuota_mensual`
  ADD CONSTRAINT `cuota_mensual_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`);

--
-- Filtros para la tabla `desempe??o_entrenamiento`
--
ALTER TABLE `desempe??o_entrenamiento`
  ADD CONSTRAINT `desempe??o_entrenamiento_ibfk_1` FOREIGN KEY (`entrenamiento_id`) REFERENCES `entrenamiento` (`entrenamiento_id`),
  ADD CONSTRAINT `desempe??o_entrenamiento_ibfk_2` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`);

--
-- Filtros para la tabla `goles`
--
ALTER TABLE `goles`
  ADD CONSTRAINT `goles_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `goles_ibfk_2` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`partido_id`);

--
-- Filtros para la tabla `grado`
--
ALTER TABLE `grado`
  ADD CONSTRAINT `grado_ibfk_1` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`colegio_id`),
  ADD CONSTRAINT `grado_ibfk_2` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`);

--
-- Filtros para la tabla `hobbie_atleta`
--
ALTER TABLE `hobbie_atleta`
  ADD CONSTRAINT `hobbie_atleta_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `hobbie_atleta_ibfk_2` FOREIGN KEY (`hobbie_id`) REFERENCES `hobbies` (`hobbie_id`);

--
-- Filtros para la tabla `incidencia`
--
ALTER TABLE `incidencia`
  ADD CONSTRAINT `incidencia_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `incidencia_ibfk_2` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`partido_id`);

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`usuario_creador`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `operacion`
--
ALTER TABLE `operacion`
  ADD CONSTRAINT `operacion_ibfk_1` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`pago_id`),
  ADD CONSTRAINT `operacion_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipo_pago` (`tipo_pago_id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cedula_representante`) REFERENCES `representante` (`cedula_representante`);

--
-- Filtros para la tabla `pago_cuota`
--
ALTER TABLE `pago_cuota`
  ADD CONSTRAINT `fk_pc_cuota` FOREIGN KEY (`cuota_id`) REFERENCES `cuota_mensual_old` (`cuota_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pc_pago` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`pago_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `partido_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `partido_ibfk_2` FOREIGN KEY (`campo_id`) REFERENCES `campo` (`campo_id`),
  ADD CONSTRAINT `partido_ibfk_3` FOREIGN KEY (`equipo_id`) REFERENCES `equipo_rival` (`equipo_id`);

--
-- Filtros para la tabla `partido_tarjetas`
--
ALTER TABLE `partido_tarjetas`
  ADD CONSTRAINT `partido_tarjetas_ibfk_1` FOREIGN KEY (`tarjeta_id`) REFERENCES `tarjetas` (`tarjeta_id`),
  ADD CONSTRAINT `partido_tarjetas_ibfk_2` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`partido_id`),
  ADD CONSTRAINT `partido_tarjetas_ibfk_3` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`);

--
-- Filtros para la tabla `prueba_balon_aereo`
--
ALTER TABLE `prueba_balon_aereo`
  ADD CONSTRAINT `prueba_balon_aereo_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_balon_aereo_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_conduccion`
--
ALTER TABLE `prueba_conduccion`
  ADD CONSTRAINT `prueba_conduccion_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_conduccion_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_definicion`
--
ALTER TABLE `prueba_definicion`
  ADD CONSTRAINT `prueba_definicion_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_definicion_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_fuerza`
--
ALTER TABLE `prueba_fuerza`
  ADD CONSTRAINT `prueba_fuerza_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_fuerza_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_pases`
--
ALTER TABLE `prueba_pases`
  ADD CONSTRAINT `prueba_pases_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_pases_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_recepcion_balon`
--
ALTER TABLE `prueba_recepcion_balon`
  ADD CONSTRAINT `prueba_recepcion_balon_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_recepcion_balon_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_resistencia`
--
ALTER TABLE `prueba_resistencia`
  ADD CONSTRAINT `prueba_resistencia_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_resistencia_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_salto`
--
ALTER TABLE `prueba_salto`
  ADD CONSTRAINT `prueba_salto_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_salto_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `prueba_velocidad`
--
ALTER TABLE `prueba_velocidad`
  ADD CONSTRAINT `prueba_velocidad_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`),
  ADD CONSTRAINT `prueba_velocidad_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`);

--
-- Filtros para la tabla `salud`
--
ALTER TABLE `salud`
  ADD CONSTRAINT `salud_ibfk_1` FOREIGN KEY (`Atleta_id`) REFERENCES `atleta` (`Atleta_id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

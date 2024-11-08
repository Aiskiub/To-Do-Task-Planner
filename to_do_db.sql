-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 06:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `to_do_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `actividad`
--

CREATE TABLE `actividad` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_ejecucion` date DEFAULT NULL,
  `hora_ejecucion` time(6) DEFAULT NULL,
  `prioridad_id` int(11) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `importante` tinyint(1) DEFAULT 0,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `actividad`
--

INSERT INTO `actividad` (`id`, `titulo`, `descripcion`, `fecha_ejecucion`, `hora_ejecucion`, `prioridad_id`, `estado_id`, `importante`, `usuario_id`) VALUES
(9, 'Cocaina', 'consumir', '2024-11-15', '00:00:00.000000', 1, 1, 1, 2),
(10, 'Chupar penes', 'Lo que me encanta', '2024-11-10', '00:00:00.000000', 1, 1, 1, 3),
(11, 'Meter Visio', 'eta vaina es seria goku', '2024-12-20', '14:20:00.000000', 1, 1, 0, 3),
(12, 'Prostitutas', 'negras', '2024-12-24', '00:35:00.000000', 1, 1, 0, 3),
(13, 'dasdas', 'gtg', '2024-11-09', '12:00:00.000000', 1, 1, 1, 3),
(14, 'Ser millonario', 'billullo', '2024-11-15', '12:00:00.000000', 2, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `actividad_categoria`
--

CREATE TABLE `actividad_categoria` (
  `actividad_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `actividad_categoria`
--

INSERT INTO `actividad_categoria` (`actividad_id`, `categoria_id`) VALUES
(13, 5),
(14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Personal'),
(2, 'Work'),
(3, 'Shopping'),
(4, 'Health'),
(5, 'Education'),
(6, 'Family'),
(7, 'Projects'),
(8, 'Diversión');

-- --------------------------------------------------------

--
-- Table structure for table `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estado`
--

INSERT INTO `estado` (`id`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'En Progreso'),
(3, 'Completado');

-- --------------------------------------------------------

--
-- Table structure for table `prioridad`
--

CREATE TABLE `prioridad` (
  `id` int(11) NOT NULL,
  `nivel` varchar(50) NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prioridad`
--

INSERT INTO `prioridad` (`id`, `nivel`, `color`) VALUES
(1, 'Baja', '#28a745'),
(2, 'Media', '#ffc107'),
(3, 'Alta', '#dc3545');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `tipodocumento` enum('CEDULA','PASAPORTE') NOT NULL,
  `documento` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `correo`, `telefono`, `tipodocumento`, `documento`) VALUES
(1, 'Daniel', 'Lopez', 'danilq139@gmail.com', '11111111', 'CEDULA', '1089098501'),
(2, 'Julian', 'Vasquez', 'minai@gg.com', '0000', 'CEDULA', '1088240743'),
(3, 'Samuel', 'Acevedo', 's.a@gmail.com', '313', 'CEDULA', '14567');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actividad_usuario` (`usuario_id`),
  ADD KEY `fk_actividad_prioridad` (`prioridad_id`),
  ADD KEY `fk_actividad_estado` (`estado_id`);

--
-- Indexes for table `actividad_categoria`
--
ALTER TABLE `actividad_categoria`
  ADD PRIMARY KEY (`actividad_id`,`categoria_id`),
  ADD KEY `fk_actcat_categoria` (`categoria_id`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prioridad`
--
ALTER TABLE `prioridad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actividad`
--
ALTER TABLE `actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prioridad`
--
ALTER TABLE `prioridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actividad`
--
ALTER TABLE `actividad`
  ADD CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`prioridad_id`) REFERENCES `prioridad` (`id`),
  ADD CONSTRAINT `actividad_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `fk_actividad_estado` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `fk_actividad_prioridad` FOREIGN KEY (`prioridad_id`) REFERENCES `prioridad` (`id`),
  ADD CONSTRAINT `fk_actividad_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `actividad_categoria`
--
ALTER TABLE `actividad_categoria`
  ADD CONSTRAINT `actividad_categoria_ibfk_1` FOREIGN KEY (`actividad_id`) REFERENCES `actividad` (`id`),
  ADD CONSTRAINT `actividad_categoria_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `fk_actcat_actividad` FOREIGN KEY (`actividad_id`) REFERENCES `actividad` (`id`),
  ADD CONSTRAINT `fk_actcat_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

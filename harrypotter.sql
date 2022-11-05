-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2022 a las 09:10:17
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `harrypotter`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `house`
--

CREATE TABLE `house` (
  `HouId` int(11) NOT NULL,
  `HouName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `house`
--

INSERT INTO `house` (`HouId`, `HouName`) VALUES
(1, 'Gryffindor'),
(2, 'Hufflepuff'),
(3, 'Ravenclaw'),
(4, 'Slytherin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hpcharacter`
--

CREATE TABLE `hpcharacter` (
  `CharId` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Sex` varchar(10) NOT NULL,
  `Birth` date DEFAULT NULL,
  `HouId` int(11) DEFAULT NULL,
  `Type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `hpcharacter`
--

INSERT INTO `hpcharacter` (`CharId`, `Name`, `Surname`, `Sex`, `Birth`, `HouId`, `Type`) VALUES
(1, 'Harry', 'Potter', 'Male', '1980-07-31', 1, 'student'),
(2, 'Hermione', 'Granger', 'Female', '1979-09-19', 1, 'student'),
(3, 'Ron', 'Weasley', 'Male', '1980-03-01', 1, 'student'),
(4, 'Severus', 'Snape', 'Male', '1960-01-09', 1, 'professor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pet`
--

CREATE TABLE `pet` (
  `PetId` int(11) NOT NULL,
  `PetName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pet`
--

INSERT INTO `pet` (`PetId`, `PetName`) VALUES
(1, 'Cat'),
(2, 'Rat'),
(3, 'Toad'),
(4, 'Owl');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `professor`
--

CREATE TABLE `professor` (
  `CharId` int(11) NOT NULL,
  `SubId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `professor`
--

INSERT INTO `professor` (`CharId`, `SubId`) VALUES
(4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student`
--

CREATE TABLE `student` (
  `CharId` int(11) NOT NULL,
  `PetId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `student`
--

INSERT INTO `student` (`CharId`, `PetId`) VALUES
(2, 1),
(3, 2),
(1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject`
--

CREATE TABLE `subject` (
  `SubId` int(11) NOT NULL,
  `SubName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `subject`
--

INSERT INTO `subject` (`SubId`, `SubName`) VALUES
(1, 'Charms'),
(2, 'Astronomy'),
(3, 'Defence Against the Dark Arts'),
(4, 'Herbology'),
(5, 'History of Magic'),
(6, 'Potions'),
(7, 'Transfiguration');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `house`
--
ALTER TABLE `house`
  ADD PRIMARY KEY (`HouId`);

--
-- Indices de la tabla `hpcharacter`
--
ALTER TABLE `hpcharacter`
  ADD PRIMARY KEY (`CharId`),
  ADD KEY `HouId` (`HouId`);

--
-- Indices de la tabla `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`PetId`);

--
-- Indices de la tabla `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`CharId`),
  ADD KEY `SubId` (`SubId`);

--
-- Indices de la tabla `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`CharId`),
  ADD KEY `PetId` (`PetId`);

--
-- Indices de la tabla `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`SubId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `house`
--
ALTER TABLE `house`
  MODIFY `HouId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `hpcharacter`
--
ALTER TABLE `hpcharacter`
  MODIFY `CharId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pet`
--
ALTER TABLE `pet`
  MODIFY `PetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `subject`
--
ALTER TABLE `subject`
  MODIFY `SubId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `hpcharacter`
--
ALTER TABLE `hpcharacter`
  ADD CONSTRAINT `hpcharacter_ibfk_1` FOREIGN KEY (`HouId`) REFERENCES `house` (`HouId`);

--
-- Filtros para la tabla `professor`
--
ALTER TABLE `professor`
  ADD CONSTRAINT `professor_ibfk_1` FOREIGN KEY (`SubId`) REFERENCES `subject` (`SubId`),
  ADD CONSTRAINT `professor_ibfk_2` FOREIGN KEY (`CharId`) REFERENCES `hpcharacter` (`CharId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`PetId`) REFERENCES `pet` (`PetId`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`CharId`) REFERENCES `hpcharacter` (`CharId`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

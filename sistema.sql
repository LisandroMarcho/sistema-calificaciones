-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2019 a las 20:19:27
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `idalumno` int(11) NOT NULL,
  `idcurso` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `ape` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`idalumno`, `idcurso`, `nom`, `ape`) VALUES
(1, 1, 'Lisandro', 'Marchionni'),
(2, 1, 'Juan Ale', 'Bravo'),
(3, 1, 'Luciano ', 'Bermejo'),
(4, 1, 'Yoel ', 'Alonso'),
(5, 1, 'Julián ', 'Quinteros Mansilla'),
(6, 1, 'Tomás ', 'Álvarez'),
(7, 1, 'Joaquín', 'Di Lello'),
(8, 1, 'Federico', 'Moure'),
(9, 1, 'Agustin', 'Gonzales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `idasistencia` int(11) NOT NULL,
  `idalumno` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `idhorario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` varchar(1) NOT NULL COMMENT 'p presente, t tarde, f falto, - sin clase',
  `observaciones` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`idasistencia`, `idalumno`, `idmateria`, `idhorario`, `fecha`, `estado`, `observaciones`) VALUES
(19, 1, 1, 1, '2019-09-23', 'p', ' '),
(20, 2, 1, 1, '2019-09-23', 'f', ' '),
(21, 3, 1, 1, '2019-09-23', 'p', ' '),
(22, 4, 1, 1, '2019-09-23', 'p', ' '),
(23, 5, 1, 1, '2019-09-23', 'p', ' '),
(24, 6, 1, 1, '2019-09-23', 'f', ' '),
(25, 7, 1, 1, '2019-09-23', 'p', ' '),
(26, 8, 1, 1, '2019-09-23', 'p', ' '),
(27, 9, 1, 1, '2019-09-23', 'p', ' '),
(28, 1, 1, 1, '2019-09-16', 'p', ' '),
(29, 2, 1, 1, '2019-09-16', 'a', ' '),
(30, 3, 1, 1, '2019-09-16', 'p', ' '),
(31, 4, 1, 1, '2019-09-16', 'p', ' '),
(32, 5, 1, 1, '2019-09-16', 'p', ' '),
(33, 6, 1, 1, '2019-09-16', 'p', ' '),
(34, 7, 1, 1, '2019-09-16', 'p', ' '),
(35, 8, 1, 1, '2019-09-16', 'p', ' '),
(36, 9, 1, 1, '2019-09-16', 'a', ' '),
(37, 1, 1, 1, '2019-09-30', 'p', ' '),
(38, 2, 1, 1, '2019-09-30', 'p', ' '),
(39, 3, 1, 1, '2019-09-30', 'p', ' '),
(40, 4, 1, 1, '2019-09-30', 'p', ' '),
(41, 5, 1, 1, '2019-09-30', 'p', ' '),
(42, 6, 1, 1, '2019-09-30', 'p', ' '),
(43, 7, 1, 1, '2019-09-30', 'p', ' '),
(44, 8, 1, 1, '2019-09-30', 'p', ' '),
(45, 9, 1, 1, '2019-09-30', 'p', ' ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `idcalificacion` int(11) NOT NULL,
  `idalumno` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `periodo` varchar(1) NOT NULL,
  `notauno` varchar(2) NOT NULL COMMENT '1-10 enteros',
  `notados` varchar(2) NOT NULL COMMENT '1-10 enteros',
  `notatres` varchar(2) NOT NULL COMMENT '1-10 enteros',
  `promedio` varchar(4) NOT NULL COMMENT '1-10 (2 decimales)',
  `asistencia` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idcurso` int(11) NOT NULL,
  `idescuela` int(11) NOT NULL,
  `anio` varchar(5) NOT NULL,
  `seccion` varchar(5) NOT NULL,
  `grupo` varchar(1) NOT NULL,
  `resolucion` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idcurso`, `idescuela`, `anio`, `seccion`, `grupo`, `resolucion`) VALUES
(1, 1, '5', '3', '', '55/42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas`
--

CREATE TABLE `escuelas` (
  `idescuela` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `escuelas`
--

INSERT INTO `escuelas` (`idescuela`, `nom`) VALUES
(1, 'E.E.S.T. Nº1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `idhorario` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `dia` varchar(10) NOT NULL COMMENT '1-5 (lunes a viernes)',
  `horario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`idhorario`, `idmateria`, `dia`, `horario`) VALUES
(1, 1, 'Lunes', '07:35hs a 12:00hs');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `idmateria` int(11) NOT NULL,
  `idcurso` int(11) NOT NULL,
  `nom` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`idmateria`, `idcurso`, `nom`) VALUES
(1, 1, 'Lab. de Programación'),
(2, 1, 'Teleinformática');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiasprofesores`
--

CREATE TABLE `materiasprofesores` (
  `idmatprof` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `idprofesor` int(11) NOT NULL,
  `estado` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `materiasprofesores`
--

INSERT INTO `materiasprofesores` (`idmatprof`, `idmateria`, `idprofesor`, `estado`) VALUES
(1, 2, 1, '1'),
(2, 1, 1, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `idmesa` int(11) NOT NULL,
  `idalumno` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `periodo` varchar(3) NOT NULL,
  `nota` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones`
--

CREATE TABLE `observaciones` (
  `idobservacion` int(11) NOT NULL,
  `idhorario` int(11) NOT NULL,
  `observacion` varchar(200) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `idprofesor` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pass` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`idprofesor`, `nom`, `email`, `pass`) VALUES
(1, 'Sebastian Thomas', 'sat@gmail.com', '$2y$10$yoTT/rwwSBj0z73xdiCkwOrlm0wEn5i4zyHpJYGea6WBAHyNSw4Oi');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`idalumno`),
  ADD KEY `idcurso` (`idcurso`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `idalumno` (`idalumno`),
  ADD KEY `idmateria` (`idmateria`),
  ADD KEY `idhorario` (`idhorario`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`idcalificacion`),
  ADD KEY `idalumno` (`idalumno`),
  ADD KEY `idmateria` (`idmateria`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idcurso`),
  ADD KEY `idescuela` (`idescuela`);

--
-- Indices de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  ADD PRIMARY KEY (`idescuela`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`idhorario`),
  ADD KEY `idmateria` (`idmateria`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`idmateria`),
  ADD KEY `idcurso` (`idcurso`);

--
-- Indices de la tabla `materiasprofesores`
--
ALTER TABLE `materiasprofesores`
  ADD PRIMARY KEY (`idmatprof`),
  ADD KEY `idmateria` (`idmateria`),
  ADD KEY `idprofesor` (`idprofesor`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`idmesa`),
  ADD KEY `idalumno` (`idalumno`),
  ADD KEY `idmateria` (`idmateria`);

--
-- Indices de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`idobservacion`),
  ADD KEY `idhorario` (`idhorario`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`idprofesor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idalumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `idcalificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idcurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  MODIFY `idescuela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `idhorario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `idmateria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `materiasprofesores`
--
ALTER TABLE `materiasprofesores`
  MODIFY `idmatprof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `idmesa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `idprofesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`idalumno`) REFERENCES `alumnos` (`idalumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistencias_ibfk_3` FOREIGN KEY (`idhorario`) REFERENCES `horarios` (`idhorario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`idalumno`) REFERENCES `alumnos` (`idalumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`idescuela`) REFERENCES `escuelas` (`idescuela`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `materiasprofesores`
--
ALTER TABLE `materiasprofesores`
  ADD CONSTRAINT `materiasprofesores_ibfk_1` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `materiasprofesores_ibfk_2` FOREIGN KEY (`idprofesor`) REFERENCES `profesores` (`idprofesor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD CONSTRAINT `mesa_ibfk_1` FOREIGN KEY (`idalumno`) REFERENCES `alumnos` (`idalumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mesa_ibfk_2` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD CONSTRAINT `observaciones_ibfk_1` FOREIGN KEY (`idhorario`) REFERENCES `horarios` (`idhorario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

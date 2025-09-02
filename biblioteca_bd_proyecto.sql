-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2025 a las 04:46:02
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
-- Base de datos: `biblioteca_bd_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `nombre`) VALUES
(1, 'Ciencia Ficción'),
(2, 'Historia'),
(3, 'Biografía'),
(4, 'Filosofía'),
(5, 'Psicología'),
(6, 'Literatura Clásica'),
(7, 'Poesía'),
(8, 'Infantil'),
(9, 'Juvenil'),
(10, 'Novela Romántica'),
(11, 'Novela Policíaca'),
(12, 'Terror'),
(13, 'Fantasía'),
(14, 'Divulgación Científica'),
(15, 'Arte'),
(18, 'Hola');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejemplares`
--

CREATE TABLE `ejemplares` (
  `ejemplar_id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `no_copia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejemplares`
--

INSERT INTO `ejemplares` (`ejemplar_id`, `libro_id`, `estado`, `no_copia`) VALUES
(1, 24, 'No disponible', 1),
(2, 26, 'No disponible', 1),
(3, 27, 'Disponible', 1),
(4, 28, 'Disponible', 1),
(5, 29, 'Disponible', 1),
(6, 30, 'Disponible', 1),
(7, 31, 'Disponible', 1),
(8, 32, 'Disponible', 1),
(9, 33, 'Disponible', 1),
(10, 34, 'No disponible', 1),
(11, 35, 'Disponible', 1),
(12, 36, 'Disponible', 1),
(13, 37, 'No disponible', 1),
(14, 38, 'No disponible', 1),
(15, 39, 'Disponible', 1),
(16, 40, 'Disponible', 1),
(17, 41, 'No disponible', 1),
(18, 41, 'No disponible', 2),
(19, 41, 'No disponible', 3),
(20, 41, 'No disponible', 4),
(21, 41, 'No disponible', 5),
(22, 42, 'No disponible', 1),
(23, 42, 'No disponible', 2),
(24, 42, 'No disponible', 3),
(25, 42, 'No disponible', 4),
(26, 42, 'No disponible', 5),
(27, 42, 'No disponible', 6),
(28, 43, 'Disponible', 1),
(29, 43, 'Disponible', 2),
(30, 43, 'Disponible', 3),
(31, 43, 'Disponible', 4),
(32, 43, 'No disponible', 5),
(33, 44, 'Disponible', 1),
(34, 44, 'Disponible', 2),
(35, 44, 'Disponible', 3),
(36, 44, 'No Diponible', 4),
(37, 44, 'No disponible', 5),
(38, 45, 'No disponible', 1),
(39, 45, 'No disponible', 2),
(40, 45, 'No disponible', 3),
(41, 45, 'No disponible', 4),
(42, 45, 'No disponible', 5),
(43, 46, 'Disponible', 1),
(44, 47, 'No disponible', 1),
(45, 47, 'No disponible', 2),
(46, 47, 'No disponible', 3),
(47, 47, 'No disponible', 4),
(48, 47, 'No disponible', 5),
(49, 47, 'No disponible', 6),
(50, 48, 'Disponible', 1),
(51, 48, 'Disponible', 2),
(52, 48, 'Disponible', 3),
(53, 48, 'Disponible', 4),
(54, 48, 'Disponible', 5),
(55, 48, 'Disponible', 6),
(56, 48, 'Disponible', 7),
(57, 48, 'No disponible', 8),
(58, 48, 'No disponible', 9),
(59, 48, 'No disponible', 10),
(60, 49, 'Disponible', 1),
(61, 49, 'Disponible', 2),
(62, 49, 'Disponible', 3),
(63, 49, 'Disponible', 4),
(64, 50, 'No disponible', 1),
(65, 50, 'No disponible', 2),
(66, 50, 'No disponible', 3),
(67, 50, 'No disponible', 4),
(68, 50, 'No disponible', 5),
(69, 50, 'No disponible', 6),
(70, 50, 'No disponible', 7),
(71, 51, 'Disponible', 1),
(72, 51, 'Disponible', 2),
(73, 51, 'Disponible', 3),
(74, 51, 'Disponible', 4),
(75, 52, 'Disponible', 1),
(76, 52, 'Disponible', 2),
(77, 52, 'Disponible', 3),
(78, 52, 'Disponible', 4),
(79, 52, 'Disponible', 5),
(80, 52, 'Disponible', 6),
(81, 52, 'No disponible', 7),
(82, 52, 'No disponible', 8),
(83, 53, 'Disponible', 1),
(84, 54, 'Disponible', 1),
(85, 55, 'Disponible', 1),
(86, 55, 'Disponible', 2),
(87, 56, 'No disponible', 1),
(88, 56, 'No disponible', 2),
(89, 56, 'No disponible', 3),
(90, 56, 'No disponible', 4),
(91, 57, 'Disponible', 1),
(92, 58, 'Disponible', 1),
(93, 58, 'Disponible', 2),
(94, 59, 'Disponible', 1),
(95, 60, 'Disponible', 1),
(96, 61, 'No disponible', 1),
(97, 62, 'Disponible', 1),
(98, 63, 'Disponible', 1),
(99, 63, 'Disponible', 2),
(100, 64, 'Disponible', 1),
(101, 65, 'Disponible', 1),
(102, 66, 'Disponible', 1),
(103, 66, 'Disponible', 2),
(104, 66, 'Disponible', 3),
(105, 67, 'Disponible', 1),
(106, 68, 'Disponible', 1),
(107, 69, 'Disponible', 1),
(108, 69, 'Disponible', 2),
(109, 69, 'Disponible', 3),
(110, 70, 'No disponible', 1),
(111, 70, 'No disponible', 2),
(112, 70, 'No disponible', 3),
(113, 71, 'No disponible', 1),
(114, 72, 'Disponible', 1),
(115, 73, 'Disponible', 1),
(116, 73, 'Disponible', 2),
(117, 74, 'Disponible', 1),
(118, 74, 'Disponible', 2),
(119, 75, 'Disponible', 1),
(120, 76, 'Disponible', 1),
(121, 77, 'Disponible', 1),
(122, 78, 'No disponible', 1),
(123, 78, 'No disponible', 2),
(124, 78, 'No disponible', 3),
(125, 79, 'Disponible', 1),
(126, 80, 'Disponible', 1),
(127, 81, 'Disponible', 1),
(128, 81, 'Disponible', 2),
(129, 82, 'Disponible', 1),
(130, 82, 'Disponible', 2),
(131, 82, 'Disponible', 3),
(132, 83, 'No disponible', 1),
(133, 83, 'No disponible', 2),
(134, 84, 'Disponible', 1),
(135, 84, 'Disponible', 2),
(136, 84, 'No disponible', 3),
(137, 85, 'Disponible', 1),
(138, 86, 'Disponible', 1),
(139, 87, 'Disponible', 1),
(140, 88, 'Disponible', 1),
(141, 89, 'Disponible', 1),
(142, 90, 'Disponible', 1),
(143, 91, 'Disponible', 1),
(144, 92, 'Disponible', 1),
(145, 93, 'Disponible', 1),
(146, 94, 'Disponible', 1),
(147, 95, 'Disponible', 1),
(148, 96, 'Disponible', 1),
(149, 97, 'Disponible', 1),
(150, 98, 'Disponible', 1),
(151, 99, 'Disponible', 1),
(152, 100, 'Disponible', 1),
(153, 101, 'Disponible', 1),
(154, 102, 'Disponible', 1),
(155, 103, 'Disponible', 1),
(156, 104, 'Disponible', 1),
(157, 105, 'Disponible', 1),
(158, 106, 'Disponible', 1),
(159, 107, 'Disponible', 1),
(160, 108, 'Disponible', 1),
(161, 109, 'Disponible', 1),
(162, 110, 'Disponible', 1),
(163, 111, 'Disponible', 1),
(164, 112, 'Disponible', 1),
(165, 113, 'Disponible', 1),
(166, 114, 'Disponible', 1),
(167, 115, 'Disponible', 1),
(168, 116, 'Disponible', 1),
(169, 117, 'Disponible', 1),
(170, 118, 'Disponible', 1),
(171, 119, 'Disponible', 1),
(172, 120, 'Disponible', 1),
(173, 121, 'Disponible', 1),
(174, 122, 'Disponible', 1),
(175, 123, 'Disponible', 1),
(176, 124, 'Disponible', 1),
(177, 125, 'Disponible', 1),
(178, 126, 'Disponible', 1),
(179, 127, 'Disponible', 1),
(180, 128, 'Disponible', 1),
(181, 129, 'Disponible', 1),
(182, 130, 'Disponible', 1),
(183, 131, 'Disponible', 1),
(184, 132, 'Disponible', 1),
(185, 133, 'Disponible', 1),
(186, 134, 'Disponible', 1),
(187, 135, 'Disponible', 1),
(188, 136, 'Disponible', 1),
(189, 137, 'Disponible', 1),
(190, 138, 'Disponible', 1),
(191, 139, 'Disponible', 1),
(192, 140, 'Disponible', 1),
(193, 141, 'Disponible', 1),
(194, 142, 'Disponible', 1),
(195, 143, 'Disponible', 1),
(196, 144, 'Disponible', 1),
(197, 145, 'Disponible', 1),
(198, 146, 'Disponible', 1),
(199, 147, 'Disponible', 1),
(200, 148, 'Disponible', 1),
(201, 149, 'Disponible', 1),
(202, 150, 'Disponible', 1),
(203, 151, 'Disponible', 1),
(204, 152, 'Disponible', 1),
(205, 153, 'Disponible', 1),
(206, 154, 'Disponible', 1),
(207, 155, 'Disponible', 1),
(208, 156, 'Disponible', 1),
(209, 157, 'Disponible', 1),
(210, 158, 'Disponible', 1),
(211, 159, 'Disponible', 1),
(212, 160, 'Disponible', 1),
(213, 161, 'Disponible', 1),
(214, 162, 'Disponible', 1),
(215, 163, 'Disponible', 1),
(216, 164, 'Disponible', 1),
(217, 165, 'Disponible', 1),
(218, 166, 'Disponible', 1),
(219, 167, 'Disponible', 1),
(220, 168, 'Disponible', 1),
(221, 169, 'Disponible', 1),
(222, 170, 'Disponible', 1),
(223, 171, 'Disponible', 1),
(224, 172, 'Disponible', 1),
(225, 173, 'Disponible', 1),
(226, 174, 'Disponible', 1),
(227, 175, 'Disponible', 1),
(228, 176, 'Disponible', 1),
(229, 177, 'Disponible', 1),
(230, 178, 'Disponible', 1),
(231, 179, 'Disponible', 1),
(232, 180, 'Disponible', 1),
(233, 181, 'Disponible', 1),
(234, 182, 'Disponible', 1),
(235, 183, 'Disponible', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `libro_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `editorial` varchar(255) DEFAULT NULL,
  `cantidad_total` int(11) NOT NULL,
  `cantidad_disponibles` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`libro_id`, `titulo`, `autor`, `editorial`, `cantidad_total`, `cantidad_disponibles`, `estado`, `categoria_id`) VALUES
(24, 'Piel de león cuento para bailar', 'Del Amo, Monserrat', 'Editorial 9', 1, 0, 'Dañado', 8),
(26, 'El supertruco de Max', 'Hiriart, Berta', 'Editorial 4', 1, 0, 'Dañado', 8),
(27, 'El misterio del colibrí', 'Naró, Rodolfo', 'Editorial 2', 1, 1, 'Disponible', 8),
(28, 'El cocodrilo no sirve, es dragón', 'Hino Sosa, Francisco', 'Editorial 7', 1, 1, 'Disponible', 8),
(29, 'Alicia en el país de las maravillas', 'Carroll, Lewis', 'Editorial 8', 1, 1, 'Disponible', 8),
(30, 'El maravilloso mago de Oz', 'Baum, L Frank', 'Editorial 3', 1, 1, 'Disponible', 8),
(31, '¿Còmo podré decidir què mascota elegir?', 'Dr. Seus', 'Editorial 10', 1, 1, 'Disponible', 8),
(32, '¡Yo puedo leer con los ojos cerrados!', 'Dr. Seus', 'Editorial 5', 1, 1, 'Disponible', 8),
(33, '¡Hay un molillo en mi bolsillo!', 'Dr. Seus', 'Editorial 10', 1, 1, 'Disponible', 8),
(34, '¡Diez manzanas en mi cabeza!', 'Dr. Seus', 'Editorial 1', 1, 0, 'Dañado', 8),
(35, 'El gato ensombrerado ha regresado', 'Dr. Seus', 'Editorial 5', 1, 1, 'Disponible', 8),
(36, 'El misterio de huesópolis', 'Fromenal, Jean-Luc', 'Editorial 1', 1, 1, 'Disponible', 2),
(37, 'Viaje mágico de Chiloé', 'Schkolnik, Saúl', 'Editorial 5', 1, 0, 'Dañado', 8),
(38, 'Rosa y la banda de los solitarios', 'Gutman, Colas', 'Editorial 10', 1, 0, 'Dañado', 9),
(39, 'El enmascarado de lata', 'Mansour, Vivian', 'Editorial 8', 1, 1, 'Disponible', 8),
(40, 'Un domingo con los dinosaurios', 'Murail, Marie-Aude', 'Editorial 4', 1, 1, 'Disponible', 8),
(41, 'Bolita', 'M.B. Brozon', 'Editorial 7', 5, 0, 'Dañado', 8),
(42, 'El más pequeño se ha perdido', 'Lester Oliveros', 'Editorial 5', 6, 0, 'Dañado', 8),
(43, 'Apescar pensamientos', 'Beatrice Masini', 'Editorial 5', 5, 4, 'Disponible', 8),
(44, 'Sapo y Sepo son amigos', 'Arnold Lobel', 'Editorial 3', 5, 3, 'Disponible', 8),
(45, 'Sapo y Sepo un año entero', 'Arnold Lobel', 'Editorial 7', 5, 0, 'Dañado', 8),
(46, 'Sapo y Sepo inseparables', 'Arnold Lobel', 'Editorial 2', 1, 1, 'Disponible', 8),
(47, 'Los mejores amigos', 'Rachel Anderson', 'Editorial 5', 6, 0, 'Dañado', 8),
(48, '¿Qué es esto gigantesco?', 'Adela Basch', 'Editorial 1', 10, 7, 'Disponible', 8),
(49, 'La estupenda mamá de Roberta', 'Rosemary Wells', 'Editorial 8', 4, 4, 'Disponible', 8),
(50, 'La tortuga sabia y el mono entremetido', 'Ana María Machado', 'Editorial 9', 7, 0, 'Dañado', 8),
(51, 'Las aventuras de Brócole', 'Ana Pérez Zaldívar', 'Editorial 4', 4, 4, 'Disponible', 8),
(52, 'La escuela de los ángeles', 'Antonio Orlando Rodríguez', 'Editorial 7', 8, 6, 'Disponible', 8),
(53, 'Nuevas aventuras del ratoncito gris', 'Víctor Manuel Ramos', 'Editorial 2', 1, 1, 'Disponible', 8),
(54, 'Ya me llené de verduras', 'Anita Heald', 'Editorial 6', 1, 1, 'Disponible', 8),
(55, 'Clara y Clarisa', 'Roxana Méndez', 'Editorial 1', 2, 2, 'Disponible', 8),
(56, 'Epaminondas', 'Graciela Bialet', 'Editorial 9', 4, 0, 'Dañado', 8),
(57, 'Medias dulces', 'Ivar Da Coll', 'Editorial 3', 1, 1, 'Disponible', 8),
(58, 'Cuentos para salir al recreo', 'Margarita Mainé', 'Editorial 5', 2, 2, 'Disponible', 8),
(59, 'Santiaguito', 'Beatty Hannstein D Adams', 'Editorial 8', 1, 1, 'Disponible', 8),
(60, 'El secreto del bosque de acopan', 'Teresa G. De Coello', 'Editorial 2', 1, 1, 'Disponible', 8),
(61, 'Gigante pequeño', 'Andrés Guerrero', 'Editorial 7', 1, 0, 'Disponible', 8),
(62, 'Filemón el arrugado', 'Michael Ende', 'Editorial 6', 1, 1, 'Disponible', 8),
(63, '¡No es justo!', 'Michel Piquemal', 'Editorial 1', 2, 2, 'Disponible', 8),
(64, '¡Ya no he sido!', 'Michel Piquemal', 'Editorial 4', 1, 1, 'Disponible', 8),
(65, 'Los caballeros de la mesa de la cocina', 'Jon Scieszka', 'Editorial 3', 1, 1, 'Disponible', 8),
(66, 'Un concierto en la granja', 'George Orwell', 'Editorial 2', 1, 0, 'Disponible', 8),
(67, 'Raci busca casa', 'Begonia Oro', 'Editorial 5', 1, 0, 'Disponible', 8),
(68, 'Osita coco está triste', 'Paloma Sánchez Ibarzábal', 'Editorial 9', 2, 0, 'Dañado', 8),
(69, 'Simón miedoso', 'Paola Sánchez Ibarzábal', 'Editorial 8', 1, 1, 'Disponible', 8),
(70, 'Ataque de...', 'Alejandro Fernández de las Peñas', 'Editorial 7', 2, 2, 'Disponible', 8),
(71, 'Caperucita roja', 'Luis María Pescetti', 'Editorial 1', 1, 1, 'Disponible', 8),
(72, 'Jengo el malo', 'Alejandro Osorio', 'Editorial 3', 2, 0, 'Dañado', 8),
(73, 'Que pase el rey', 'Ana María Izurieta', 'Editorial 3', 4, 3, 'Disponible', 8),
(74, 'El riuiseñor y otros cuentos', 'Hans Christian Andersen', 'Editorial 7', 2, 0, 'Dañado', 8),
(75, 'Más historias de Franz', 'Christine Nöstlinger', 'Editorial 2', 3, 1, 'Disponible', 8),
(76, 'Una voz para Jacinta y otros cuentos infantiles', 'Mónica Lavín', 'Editorial 8', 4, 2, 'Disponible', 8),
(77, 'Con ojos de girasol', 'Francisco Morales Santos', 'Editorial 1', 5, 5, 'Disponible', 8),
(78, 'Cuentos para salir al recreo', 'Margarita Mainé', 'Editorial 6', 5, 0, 'Dañado', 8),
(79, 'La música de Paul', 'Lara Ríos', 'Editorial 4', 3, 2, 'Disponible', 8),
(80, 'Lugar secreto', 'Gloria Hernández', 'Editorial 10', 3, 1, 'Disponible', 8),
(81, 'El Santiaguito', 'Betty Hannstein de Adams', 'Editorial 9', 1, 1, 'Disponible', 8),
(82, 'La tormenta', 'Cynthia Rylant', 'Editorial 5', 2, 2, 'Disponible', 8),
(83, 'Nuevas aventuras del ratoncito gris', 'Victor Manuel Ramos', 'Editorial 6', 1, 1, 'Disponible', 8),
(84, 'Los caballeros de la mesa de la cocina', 'Jon Scieszka', 'Editorial 7', 2, 0, 'Dañado', 8),
(85, 'El regreso de la abeja haragana', 'Gilberto Rendón', 'Editorial 1', 1, 1, 'Disponible', 8),
(86, 'De por qué a Franz le dolió el estómago', 'Chrsitine Nöstlinger', 'Editorial 4', 1, 1, 'Disponible', 8),
(87, 'La auténtica Clementina', 'Sara Pennypacker', 'Editorial 2', 1, 1, 'Disponible', 8),
(88, 'Elías y la abuela que salió de un huevo', 'Iva Procházková', 'Editorial 3', 1, 0, 'Dañado', 8),
(89, 'Diecisiete fábulas del rey león', 'Jean Muzi', 'Editorial 5', 1, 1, 'Disponible', 8),
(90, 'Dos historias increíbles', 'Laurence Anholt', 'Editorial 8', 1, 1, 'Disponible', 8),
(91, 'Mi mascota es una bacteria', 'Rafael Barajas', 'Editorial 10', 1, 0, 'Dañado', 8),
(92, 'Mauro Ojos Brillantes', 'Maite Carranza', 'Editorial 7', 1, 1, 'Disponible', 8),
(93, 'Leyendas de la luna', 'Gloria Hernández', 'Editorial 4', 6, 3, 'Disponible', 8),
(94, 'Eliot e Isabela y la aventura en el río', 'Ingo Siegner', 'Editorial 6', 1, 1, 'Disponible', 8),
(95, 'El día que mamá perdió la paciencia', 'Belén Gopegui', 'Editorial 9', 1, 1, 'Disponible', 8),
(96, 'Los caballeros de la mesa de la cocina', 'Jon Scieszka', 'Editorial 2', 1, 0, 'Dañado', 8),
(97, 'Lágrimas de ángeles', 'Edna Iturralde', 'Editorial 1', 1, 1, 'Disponible', 8),
(98, 'El árbol que quiso volar como los pájaros', 'Julio Santizo Coronado', 'Editorial 8', 1, 1, 'Disponible', 8),
(99, 'Matilda', 'Roald Dahl', 'Editorial 5', 2, 2, 'Disponible', 8),
(100, 'Guillerno el niño que hablaba con el mar', 'Josué R. Álvarez', 'Editorial 3', 3, 0, 'Dañado', 8),
(101, 'Problema de dinosaurio', 'Dick King-Smith', 'Editorial 2', 1, 1, 'Disponible', 8),
(102, 'Cuentos de la tradición oral guatemalteca', 'Francisco Morales Santos', 'Editorial 7', 1, 1, 'Disponible', 8),
(103, 'Tortuguita se perdió', 'Margarita Londoño', 'Editorial 10', 1, 1, 'Disponible', 8),
(104, 'El cerdito menta', 'Tino', 'Editorial 1', 1, 0, 'Dañado', 8),
(105, 'El gato Mog', 'Joan Aiken', 'Editorial 9', 1, 1, 'Disponible', 8),
(106, 'Cinco enfados', 'Gabriela Keselman', 'Editorial 8', 1, 1, 'Disponible', 8),
(107, 'Leyendas de nuestra América', 'Ute Bergdolt', 'Editorial 5', 1, 1, 'Disponible', 8),
(108, 'El libro de todos los miedos y unos cuantos más', 'Alejandra Osorio', 'Editorial 4', 1, 0, 'Dañado', 8),
(109, 'La bella y la bestia', 'Marie Leprince de Beaumont', 'Editorial 3', 1, 1, 'Disponible', 8),
(110, 'Dori fantasmasMagori', 'Abby Harlon', 'Editorial 2', 1, 1, 'Disponible', 8),
(111, 'El sastrecillo valiente y otros cuentos', 'Jacob Ludwig, Karl Grimm y Wilhelm Karl Grimm', 'Editorial 6', 1, 1, 'Disponible', 8),
(112, 'Olivia', 'Falconer, Ian', 'Editorial 7', 1, 1, 'Disponible', 8),
(113, 'Olivia salva el circo', 'Falconer, Ian', 'Editorial 4', 1, 0, 'Dañado', 8),
(114, 'Olivia recibe la navidad', 'Falconer, Ian', 'Editorial 9', 1, 1, 'Disponible', 8),
(115, 'Olivia y el juguete desaparecido', 'Falconer, Ian', 'Editorial 10', 1, 1, 'Disponible', 8),
(116, 'Olivia y su banda', 'Falconer, Ian', 'Editorial 1', 1, 1, 'Disponible', 8),
(117, 'Olivia en Venecia', 'Falconer, Ian', 'Editorial 5', 1, 1, 'Disponible', 8),
(118, 'Olivia y las princesas', 'Falconer, Ian', 'Editorial 2', 1, 0, 'Dañado', 8),
(119, 'El leon y el ratón', 'Planeta Junior', 'Editorial 3', 1, 1, 'Disponible', 8),
(120, 'La cigarra y la hormiga', 'Planeta Junior', 'Editorial 7', 1, 1, 'Disponible', 8),
(121, 'La gallina de los huevos de oro', 'Planeta Junior', 'Editorial 1', 1, 1, 'Disponible', 8),
(122, 'La liebre y la tortuga', 'Planeta Junior', 'Editorial 4', 1, 1, 'Disponible', 8),
(123, 'Matias tiene cinco amigos', 'Martín del Campo, David', 'Editorial 6', 1, 0, 'Dañado', 8),
(124, 'Operacion Zoo', 'Ramos Revillas, Antonio', 'Editorial 5', 1, 1, 'Disponible', 8),
(125, 'Los niños no lloran, las niñas no juegan fut', 'Rocha, Ruth', 'Editorial 8', 1, 1, 'Disponible', 8),
(126, 'El regreso de los feos', 'Flores Lucía', 'Editorial 2', 1, 1, 'Disponible', 8),
(127, '100 adivinanzas orquestadas', 'López Suárez, Sergio', 'Editorial 9', 1, 0, 'Dañado', 8),
(128, 'Ahora que tengo 80', 'Llunell, Núria', 'Editorial 6', 1, 1, 'Disponible', 8),
(129, 'Piezas para niños', 'Ibargüengoitia, Jorge', 'Editorial 10', 1, 1, 'Disponible', 8),
(130, 'Una mochila de cuentos', 'Pérez, Luis Bernardo', 'Editorial 3', 1, 1, 'Disponible', 8),
(131, 'La improbable pero verdadera historia del mundo', 'Zepeda, Monique', 'Editorial 4', 1, 0, 'Dañado', 8),
(132, 'La paloma Palometa', 'Campos Adrados, Isabel', 'Editorial 5', 1, 1, 'Disponible', 8),
(133, 'La vuelta al mundo en cuatro animales', 'Arias Salgado, LIliana', 'Editorial 1', 1, 1, 'Disponible', 8),
(134, 'Corre y se va corriendo, loteria', 'Zepeda, Erika', 'Editorial 7', 1, 1, 'Disponible', 8),
(135, 'Cuando Lila quiso ir a la escuela', 'Solar, Francisca', 'Editorial 6', 1, 1, 'Disponible', 8),
(136, 'El libro de los animales', 'Espinoza, Santiago', 'Editorial 9', 1, 0, 'Dañado', 8),
(137, 'Moby Dick', 'Melville, Herman', 'Editorial 2', 1, 1, 'Disponible', 8),
(138, 'Romeo y Julieta', 'Shakespeare, William', 'Editorial 3', 1, 1, 'Disponible', 8),
(139, 'Colmillo Blanco', 'London, Jake', 'Editorial 8', 1, 1, 'Disponible', 8),
(140, 'El jardín secreto', 'Burnett, Frances', 'Editorial 4', 1, 1, 'Disponible', 8),
(141, 'Roberto está loco', 'Triunfo Arciniegas', 'Editorial 7', 1, 0, 'Dañado', 8),
(142, 'Cosas que pasan', 'Isol', 'Editorial 2', 1, 1, 'Disponible', 8),
(143, 'Irupé y Yaguareté', 'Ruiz Johnson, Mariana', 'Editorial 9', 1, 0, 'Dañado', 8),
(144, 'Cuentos para la escuela', 'Rodari, Gianni', 'Editorial 1', 1, 1, 'Disponible', 8),
(145, 'Pablo el artista', 'Kitamura, Satoshi', 'Editorial 6', 1, 0, 'Dañado', 8),
(146, 'Ripios y adivinanzas del mar', 'Del Paso, Fernando', 'Editorial 3', 1, 1, 'Disponible', 8),
(147, 'Cuenta ratones', 'Stoll Walsh, Ellen', 'Editorial 10', 1, 0, 'Dañado', 8),
(148, 'Yo no soy un conejo', 'Márquez, Pepe', 'Editorial 5', 1, 1, 'Disponible', 8),
(149, 'No me lo vas a creer', 'Molina, Alicia', 'Editorial 2', 1, 0, 'Dañado', 8),
(150, 'María està enamorada', 'Smadja, Brigitte', 'Editorial 8', 1, 1, 'Disponible', 8),
(151, 'Cómo cuidar un ángel', 'Nakagawa, Chihiro', 'Editorial 1', 1, 0, 'Dañado', 8),
(152, 'Más historias de Franz', 'Christine Nostlinger', 'Editorial 7', 1, 1, 'Disponible', 8),
(153, 'Lidía y yo ponemos la mesa', 'Dimiter Inkiow', 'Editorial 4', 1, 1, 'Disponible', 8),
(154, 'Nadie quiere jugar conmigo', 'Gabriela Keselman', 'Editorial 9', 1, 0, 'Dañado', 8),
(155, 'Yo y mi hermana Clara', 'Dimiter Inkiow', 'Editorial 6', 4, 2, 'Disponible', 8),
(156, 'De cómo decidí convertirme en hermano mayor', 'Dimiter Inkiow', 'Editorial 3', 1, 1, 'Disponible', 8),
(157, 'Queremos jugar', 'Carlos Rubio', 'Editorial 8', 1, 0, 'Dañado', 8),
(158, 'El mejor enemigo del mundo', 'María Fernanda Heredia', 'Editorial 5', 5, 3, 'Disponible', 8),
(159, 'Mil grullas', 'Elsa Bornemann', 'Editorial 2', 1, 1, 'Disponible', 8),
(160, 'Se vende mamá', 'Care Santos', 'Editorial 7', 1, 0, 'Dañado', 8),
(161, 'Luisa viaja en tren', 'Julia Mercedes Catilla', 'Editorial 1', 3, 2, 'Disponible', 8),
(162, 'Dori tiene una amiga de verdad', 'Abby Harlon', 'Editorial 4', 3, 0, 'Dañado', 8),
(163, 'Fredy el hámster', 'Dietlof Reiche', 'Editorial 9', 1, 1, 'Disponible', 8),
(164, 'El lugar más bonito del mundo', 'Ann Cameron', 'Editorial 6', 6, 3, 'Disponible', 8),
(165, 'Una voz para Jacinta y otros cuentos infantiles', 'Mónica Lavín', 'Editorial 3', 6, 0, 'Dañado', 8),
(166, 'Enfermedad se escribe con c', 'Edmée Pardo', 'Editorial 2', 2, 1, 'Disponible', 8),
(167, 'El misterio del mayordomo', 'Norman Huidobro', 'Editorial 10', 1, 1, 'Disponible', 8),
(168, 'Casi medio año!', 'M. B. Brozon', 'Editorial 7', 1, 0, 'Dañado', 8),
(169, 'Una historia de fútbol', 'José Roberto Torea', 'Editorial 5', 1, 1, 'Disponible', 8),
(170, 'EL viaje.com', 'Margarita Londoño', 'Editorial 4', 1, 0, 'Dañado', 8),
(171, 'Querido hijo: estás perdido', 'Jordi Sierra I Fabra', 'Editorial 8', 1, 1, 'Disponible', 8),
(172, 'La auténtica Clementina', 'Sara Pennypacker', 'Editorial 1', 1, 0, 'Dañado', 8),
(173, 'Amigo se escribe con H', 'María Fernanda Heredia', 'Editorial 6', 7, 3, 'Disponible', 8),
(174, 'Los superfósforos', 'Antonio Santa Ana', 'Editorial 9', 1, 1, 'Disponible', 8),
(175, 'Unas vacaciones de aventura', 'Jasmin Sosa', 'Editorial 2', 1, 0, 'Dañado', 8),
(176, 'El club del frijol', 'Marcela Castañeda', 'Editorial 10', 1, 1, 'Disponible', 8),
(177, 'No olvides ver el cielo', 'Lorena Flores', 'Editorial 4', 1, 0, 'Dañado', 8),
(178, 'Los meteoritos odiaban a los dinosaurios', 'Jorge Accame', 'Editorial 7', 5, 3, 'Disponible', 8),
(179, 'Viaje a las cavernas', 'Mary Pope Osborne', 'Editorial 3', 1, 1, 'Disponible', 8),
(180, 'El juego de arena', 'Uri Orlev', 'Editorial 1', 2, 1, 'Disponible', 8),
(181, 'La noche de los ninjas', 'Mary Pope Osbern', 'Editorial 5', 1, 0, 'Dañado', 8),
(182, 'Querido fantasma', 'Jacqueline Balcells Ana María Güiraldes', 'Editorial 6', 1, 1, 'Disponible', 8),
(183, 'El fantasma del tío Roco', 'Sharon Creech', 'Editorial 2', 1, 0, 'Dañado', 8),
(184, 'Como si fuese papá', 'Daniel Nesquens', 'Editorial 8', 1, 1, 'Disponible', 8),
(185, 'Mañanas de escuela', 'César Aristides', 'Editorial 4', 1, 0, 'Dañado', 8),
(186, 'Triala, tri la la', 'Gloria Hernández', 'Editorial 9', 1, 1, 'Disponible', 8);


-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `prestamo_id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `ejemplar_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_de_devolucion` date NOT NULL,
  `fecha_devuelto` date DEFAULT NULL,
  `estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`prestamo_id`, `libro_id`, `ejemplar_id`, `usuario_id`, `fecha_prestamo`, `fecha_de_devolucion`, `fecha_devuelto`, `estado`) VALUES
(1, 43, 32, 12, '2025-08-20', '2025-08-25', NULL, 'En proceso'),
(2, 44, 36, 27, '2025-08-21', '2025-08-26', NULL, 'En proceso'),
(3, 44, 37, 15, '2025-08-22', '2025-08-27', NULL, 'En proceso'),
(4, 48, 57, 9, '2025-08-20', '2025-08-25', NULL, 'En proceso'),
(5, 48, 58, 31, '2025-08-21', '2025-08-26', NULL, 'En proceso'),
(6, 48, 59, 6, '2025-08-22', '2025-08-27', '0000-00-00', 'En proceso'),
(7, 52, 81, 10, '2025-08-20', '2025-08-25', NULL, 'En proceso'),
(8, 52, 82, 29, '2025-08-21', '2025-08-26', NULL, 'En proceso'),
(9, 61, 96, 14, '2025-08-23', '2025-08-28', NULL, 'En proceso'),
(10, 27, 3, 6, '2025-08-28', '2025-08-29', '2025-08-29', 'Devuelto'),
(11, 29, 5, 6, '2025-08-28', '2025-08-30', '2025-08-28', 'Devuelto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `carne` int(11) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `password`, `carne`, `correo`, `rol`) VALUES
(4, 'Sofía del Valle', '827ccb0eea8a706c4c34a16891f84e7b', 20190075, '20190075@colegioscj.edu.gt', 'Administrador'),
(5, 'Emily Santizo', '827ccb0eea8a706c4c34a16891f84e7b', 20130082, '20130082@colegioscj.edu.gt', 'Bibliotecario'),
(6, 'Sophia Barillas', '827ccb0eea8a706c4c34a16891f84e7b', 20130303, '20130303@colegioscj.edu.gt', 'Alumno'),
(9, 'Camila Almeda', '827ccb0eea8a706c4c34a16891f84e7b', 20200003, '20200003@colegioscj.edu.gt', 'Alumno'),
(10, 'Marjorie Andrade', '827ccb0eea8a706c4c34a16891f84e7b', 20150054, '20150054@colegioscj.edu.gt', 'Alumno'),
(11, 'Paula Camas', '827ccb0eea8a706c4c34a16891f84e7b', 20140065, '20140065@colegioscj.edu.gt', 'Alumno'),
(12, 'María Reneé Chavez', '827ccb0eea8a706c4c34a16891f84e7b', 20140008, '20140008@colegioscj.edu.gt', 'Alumno'),
(13, 'Sophia Dieguez', '827ccb0eea8a706c4c34a16891f84e7b', 20210054, '20210054@colegioscj.edu.gt', 'Alumno'),
(14, 'Alejandra González', '827ccb0eea8a706c4c34a16891f84e7b', 20240051, '20240051@colegioscj.edu.gt', 'Alumno'),
(15, 'Amelia Guaré', '827ccb0eea8a706c4c34a16891f84e7b', 20240060, '20240060@colegioscj.edu.gt', 'Alumno'),
(16, 'Edna Guevara', '827ccb0eea8a706c4c34a16891f84e7b', 20160216, '20160216@colegioscj.edu.gt', 'Alumno'),
(17, 'Andrea Hernandez', '827ccb0eea8a706c4c34a16891f84e7b', 20130095, '20130095@colegioscj.edu.gt', 'Alumno'),
(18, 'Domenica Kou', '827ccb0eea8a706c4c34a16891f84e7b', 20120004, '20120004@colegioscj.edu.gt', 'Alumno'),
(19, 'Nathaly Mendez', '827ccb0eea8a706c4c34a16891f84e7b', 20240188, '20240188@colegioscj.edu.gt', 'Alumno'),
(20, 'Alison Méndez', '827ccb0eea8a706c4c34a16891f84e7b', 20180024, '20180024@colegioscj.edu.gt', 'Alumno'),
(21, 'Laura Morales', '827ccb0eea8a706c4c34a16891f84e7b', 20110159, '20110159@colegioscj.edu.gt', 'Alumno'),
(22, 'Sofia Navas', '827ccb0eea8a706c4c34a16891f84e7b', 20160241, '20160241@colegioscj.edu.gt', 'Alumno'),
(23, 'Cesia Ortíz', '827ccb0eea8a706c4c34a16891f84e7b', 20240157, '20240157@colegioscj.edu.gt', 'Alumno'),
(24, 'Lisa Osorio', '827ccb0eea8a706c4c34a16891f84e7b', 20120186, '20120186@colegioscj.edu.gt', 'Alumno'),
(25, 'Janeth Pérez', '827ccb0eea8a706c4c34a16891f84e7b', 20240053, '20240053@colegioscj.edu.gt', 'Alumno'),
(26, 'Heidy Portillo', '827ccb0eea8a706c4c34a16891f84e7b', 20150067, '20150067@colegioscj.edu.gt', 'Alumno'),
(27, 'Gabriela Sánchez', '827ccb0eea8a706c4c34a16891f84e7b', 20110010, '20110010@colegioscj.edu.gt', 'Alumno'),
(28, 'María Schwartz', '827ccb0eea8a706c4c34a16891f84e7b', 20180056, '20180056@colegioscj.edu.gt', 'Alumno'),
(29, 'Ana Lucía Sosa', '827ccb0eea8a706c4c34a16891f84e7b', 20110019, '20110019@colegioscj.edu.gt', 'Alumno'),
(30, 'Saramaría Táger', '827ccb0eea8a706c4c34a16891f84e7b', 20150026, '20150026@colegioscj.edu.gt', 'Alumno'),
(31, 'Diana Toledo', '827ccb0eea8a706c4c34a16891f84e7b', 20160144, '20160144@colegioscj.edu.gt', 'Alumno'),
(32, 'Stephanie Ulloa', '827ccb0eea8a706c4c34a16891f84e7b', 20230146, '20230146@colegioscj.edu.gt', 'Alumno'),
(33, 'Andrea Polanco', '827ccb0eea8a706c4c34a16891f84e7b', 20150236, '20150236@colegioscj.edu.gt', 'Alumno');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `ejemplares`
--
ALTER TABLE `ejemplares`
  ADD PRIMARY KEY (`ejemplar_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`libro_id`),
  ADD KEY `fk_libros_categorias` (`categoria_id`);



--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`prestamo_id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `ejemplar_id` (`ejemplar_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `ejemplares`
--
ALTER TABLE `ejemplares`
  MODIFY `ejemplar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `libro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;


--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `prestamo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ejemplares`
--
ALTER TABLE `ejemplares`
  ADD CONSTRAINT `ejemplares_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`libro_id`);

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `fk_libros_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`categoria_id`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`libro_id`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`ejemplar_id`) REFERENCES `ejemplares` (`ejemplar_id`),
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2012 at 01:12 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `adpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `description` varchar(300) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `user_id`, `name`, `description`, `created`, `modified`) VALUES
(1, 0, 'Proyecto APTIE', 'Evaluación de los proyectos disponibles para la materia APTIE', '2012-05-02 01:31:25', '2012-05-02 23:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations_objectives`
--

CREATE TABLE IF NOT EXISTS `evaluations_objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eval` int(11) NOT NULL,
  `id_obj` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `evaluations_objectives`
--

INSERT INTO `evaluations_objectives` (`id`, `id_eval`, `id_obj`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `evaluations_projects`
--

CREATE TABLE IF NOT EXISTS `evaluations_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eval` int(11) NOT NULL,
  `id_pro` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `evaluations_projects`
--

INSERT INTO `evaluations_projects` (`id`, `id_eval`, `id_pro`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE IF NOT EXISTS `objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `importance` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `objectives`
--

INSERT INTO `objectives` (`id`, `name`, `type`, `description`, `importance`) VALUES
(1, 'Apoya la cultura de administración de proyectos', 0, 'El proyecto, si aplicable, debe apoyar activamente la cultura de administración de proyectos', NULL),
(2, 'Utilización de TI', 1, 'El proyecto utiliza de manera activa las tecnologías de información', 80),
(3, 'Empresa con operaciones a nivel internacional', 0, 'En caso de que el proyecto sea realizado para una empresa, esta deberá contar con operaciones a nivel internacional.', NULL),
(4, 'Mejora de los procesos internos de los clientes', 1, 'El proyecto deberá aportar una mejora a los procesos internos del cliente', 80),
(5, 'No requiere recursos externos', 0, 'El proyecto sera realizado utilizando solo los recursos disponibles en la empresa', NULL),
(6, 'Desarrollo de un sistema', 1, 'Se desarrollara un nuevo sistema', 60),
(7, 'Reducir costos', 1, 'El proyecto ayudara a la reducción de costos', 70);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `description` varchar(300) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `user_id`) VALUES
(1, 'Sistema estratégico', 'Sistema de selección estratégica de proyectos', 0),
(2, 'Proceso AP', 'Manual que muestre las acciones que debe llevar a cabo el Administrador de Proyectos desde que inicia el proyecto hasta que lo cierra formalmente.', 0),
(3, 'Sistema de diagnóstico', 'Establecer una forma de evaluación inicial de los participantes prospecto al curso de Conceptos fundamentales para que entiendan la perspectiva y determinen áreas que requieren mayor estudio.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `projects_objectives`
--

CREATE TABLE IF NOT EXISTS `projects_objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pro` int(11) NOT NULL,
  `id_obj` int(11) NOT NULL,
  `id_eval` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `projects_objectives`
--

INSERT INTO `projects_objectives` (`id`, `id_pro`, `id_obj`, `id_eval`, `score`) VALUES
(1, 1, 1, 1, 100),
(2, 1, 3, 1, 100),
(3, 1, 5, 1, 100),
(4, 1, 2, 1, 100),
(5, 1, 4, 1, 100),
(6, 1, 6, 1, 50),
(7, 1, 7, 1, 100),
(8, 2, 1, 1, 100),
(9, 2, 3, 1, 100),
(10, 2, 5, 1, 100),
(11, 2, 2, 1, 0),
(12, 2, 4, 1, 100),
(13, 2, 6, 1, 0),
(14, 2, 7, 1, 0),
(15, 3, 1, 1, 100),
(16, 3, 3, 1, 0),
(17, 3, 5, 1, 100),
(18, 3, 2, 1, 100),
(19, 3, 4, 1, 50),
(20, 3, 6, 1, 50),
(21, 3, 7, 1, 50);

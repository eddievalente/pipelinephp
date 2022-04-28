-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.22-MariaDB


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema pipeline
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ pipeline;
USE pipeline;

--
-- Table structure for table `pipeline`.`gesh_pipeline`
--

DROP TABLE IF EXISTS `gesh_pipeline`;
CREATE TABLE `gesh_pipeline` (
  `id_pipeline` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordem` int(10) unsigned NOT NULL DEFAULT 0,
  `token` varchar(50) DEFAULT NULL,
  `id_stpipeline` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A',
  `pipeline` varchar(100) CHARACTER SET utf8 NOT NULL,
  `info` text CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id_pipeline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pipeline`.`gesh_pipeline`
--

/*!40000 ALTER TABLE `gesh_pipeline` DISABLE KEYS */;
/*!40000 ALTER TABLE `gesh_pipeline` ENABLE KEYS */;


--
-- Table structure for table `pipeline`.`gesh_pipeline_acao`
--

DROP TABLE IF EXISTS `gesh_pipeline_acao`;
CREATE TABLE `gesh_pipeline_acao` (
  `id_acao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pipeline` int(10) unsigned NOT NULL DEFAULT 0,
  `ordem` int(10) unsigned NOT NULL DEFAULT 0,
  `token` varchar(50) DEFAULT NULL,
  `acao` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `info` text CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id_acao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pipeline`.`gesh_pipeline_acao`
--

/*!40000 ALTER TABLE `gesh_pipeline_acao` DISABLE KEYS */;
/*!40000 ALTER TABLE `gesh_pipeline_acao` ENABLE KEYS */;


--
-- Table structure for table `pipeline`.`gesh_pipeline_task`
--

DROP TABLE IF EXISTS `gesh_pipeline_task`;
CREATE TABLE `gesh_pipeline_task` (
  `id_task` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pipeline` int(10) unsigned NOT NULL DEFAULT 0,
  `id_acao` int(10) unsigned NOT NULL DEFAULT 0,
  `ordem` int(10) unsigned NOT NULL DEFAULT 0,
  `token` varchar(50) DEFAULT NULL,
  `tarefa` varchar(100) CHARACTER SET utf8 NOT NULL,
  `progresso` int(3) unsigned NOT NULL DEFAULT 0,
  `dtinicio` date DEFAULT NULL,
  `dtentrega` date DEFAULT NULL,
  `indicador` int(10) unsigned NOT NULL DEFAULT 4,
  `instrucao` text CHARACTER SET utf8 DEFAULT NULL,
  `prioridade` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_task`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pipeline`.`gesh_pipeline_task`
--

/*!40000 ALTER TABLE `gesh_pipeline_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `gesh_pipeline_task` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

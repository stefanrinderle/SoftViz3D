SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `graph`
--

-- --------------------------------------------------------

--
-- Structure for table `InputDependency`
--

DROP TABLE IF EXISTS `InputDependency`;
CREATE TABLE IF NOT EXISTS `InputDependency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `out_id` int(10) unsigned NOT NULL,
  `in_id` int(10) unsigned NOT NULL,
  `parentId` int(10) unsigned NOT NULL,
  `counter` int(10) unsigned NOT NULL,
  `isVisible` tinyint(1) NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47932 ;

-- --------------------------------------------------------

--
-- Structure for table `InputTreeElement`
--

DROP TABLE IF EXISTS `InputTreeElement`;
CREATE TABLE IF NOT EXISTS `InputTreeElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `parentId` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `counter` int(10) unsigned NOT NULL,
  `metric1` int(11) DEFAULT NULL,
  `metric2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22655 ;

-- --------------------------------------------------------

--
-- Structure for table `Layout`
--

DROP TABLE IF EXISTS `Layout`;
CREATE TABLE IF NOT EXISTS `Layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `creationTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=148 ;

-- --------------------------------------------------------

--
-- Structure for table `LayoutBoxElement`
--

DROP TABLE IF EXISTS `LayoutBoxElement`;
CREATE TABLE IF NOT EXISTS `LayoutBoxElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layoutId` int(11) NOT NULL,
  `inputTreeElementId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `translation` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `transparency` float NOT NULL,
  `size` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80107 ;

-- --------------------------------------------------------

--
-- Structure for table `LayoutEdgeElement`
--

DROP TABLE IF EXISTS `LayoutEdgeElement`;
CREATE TABLE IF NOT EXISTS `LayoutEdgeElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layoutId` int(11) NOT NULL,
  `inputDependencyId` int(11) NOT NULL,
  `translation` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `lineWidth` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42051 ;

-- --------------------------------------------------------

--
-- Structure for table `LayoutEdgeSectionElement`
--

DROP TABLE IF EXISTS `LayoutEdgeSectionElement`;
CREATE TABLE IF NOT EXISTS `LayoutEdgeSectionElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `edgeId` int(11) NOT NULL,
  `layoutId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `translation` varchar(255) NOT NULL,
  `rotation` float NOT NULL,
  `length` float NOT NULL,
  `cylinderLength` float NOT NULL,
  `coneLength` float NOT NULL,
  `coneRadius` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=174892 ;

-- --------------------------------------------------------

--
-- Structure for table `Project`
--

DROP TABLE IF EXISTS `Project`;
CREATE TABLE IF NOT EXISTS `Project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file` longblob,
  `fileUpdateTime` datetime DEFAULT NULL,
  `inputTreeRootId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Structure for table `User`
--

DROP TABLE IF EXISTS `User`;
CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;
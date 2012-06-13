-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 13. Jun 2012 um 09:10
-- Server Version: 5.1.61-0+squeeze1
-- PHP-Version: 5.3.3-7+squeeze9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `allevo_dev`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `nested_set_id` int(10) unsigned NOT NULL DEFAULT '0',
  `nested_set_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `auth_user_id` int(32) unsigned DEFAULT '0',
  `gruppe` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `page_title` varchar(80) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text,
  `revision_id` int(10) unsigned NOT NULL DEFAULT '0',
  `owner` int(32) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `nested_sets_comment_id` int(11) NOT NULL COMMENT 'ID des kommentierten Nodes',
  PRIMARY KEY (`nested_set_id`,`revision_id`),
  FULLTEXT KEY `content` (`content`,`title`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `query_string` text CHARACTER SET utf8,
  `perm_user_id` int(32) unsigned DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `page_title` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text CHARACTER SET utf8,
  `comments` tinyint(5) NOT NULL DEFAULT '0',
  `summary` text CHARACTER SET utf8,
  `revision_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uuid` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`uuid`),
  KEY `revision_id` (`revision_id`),
  FULLTEXT KEY `search` (`content`,`title`,`summary`,`page_title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusiontable`
--

CREATE TABLE IF NOT EXISTS `fusiontable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_applications`
--

CREATE TABLE IF NOT EXISTS `liveuser_applications` (
  `application_id` int(11) DEFAULT '0',
  `application_define_name` char(32) DEFAULT NULL,
  UNIQUE KEY `application_id_idx` (`application_id`),
  UNIQUE KEY `define_name_i_idx` (`application_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_applications_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_applications_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_areas`
--

CREATE TABLE IF NOT EXISTS `liveuser_areas` (
  `area_id` int(11) DEFAULT '0',
  `application_id` int(11) DEFAULT '0',
  `area_define_name` char(32) DEFAULT NULL,
  UNIQUE KEY `area_id_idx` (`area_id`),
  UNIQUE KEY `define_name_i_idx` (`application_id`,`area_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_areas_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_areas_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_area_admin_areas`
--

CREATE TABLE IF NOT EXISTS `liveuser_area_admin_areas` (
  `area_id` int(11) DEFAULT '0',
  `perm_user_id` int(11) DEFAULT '0',
  UNIQUE KEY `id_i_idx` (`area_id`,`perm_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_grouprights`
--

CREATE TABLE IF NOT EXISTS `liveuser_grouprights` (
  `group_id` int(11) DEFAULT '0',
  `right_id` int(11) DEFAULT '0',
  `right_level` int(11) DEFAULT NULL,
  UNIQUE KEY `id_i_idx` (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_groups`
--

CREATE TABLE IF NOT EXISTS `liveuser_groups` (
  `group_id` int(11) DEFAULT '0',
  `group_type` int(11) DEFAULT NULL,
  `group_define_name` char(32) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `owner_group_id` int(11) DEFAULT NULL,
  UNIQUE KEY `group_id_idx` (`group_id`),
  UNIQUE KEY `define_name_i_idx` (`group_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_groups_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_groups_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_groupusers`
--

CREATE TABLE IF NOT EXISTS `liveuser_groupusers` (
  `perm_user_id` int(11) DEFAULT '0',
  `group_id` int(11) DEFAULT '0',
  UNIQUE KEY `id_i_idx` (`perm_user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_group_subgroups`
--

CREATE TABLE IF NOT EXISTS `liveuser_group_subgroups` (
  `group_id` int(11) DEFAULT '0',
  `subgroup_id` int(11) DEFAULT '0',
  UNIQUE KEY `id_i_idx` (`group_id`,`subgroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_perm_users`
--

CREATE TABLE IF NOT EXISTS `liveuser_perm_users` (
  `perm_user_id` int(11) DEFAULT '0',
  `auth_user_id` char(32) DEFAULT NULL,
  `auth_container_name` char(32) DEFAULT NULL,
  `perm_type` int(11) DEFAULT NULL,
  UNIQUE KEY `perm_user_id_idx` (`perm_user_id`),
  UNIQUE KEY `auth_id_i_idx` (`auth_user_id`,`auth_container_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_perm_users_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_perm_users_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_rights`
--

CREATE TABLE IF NOT EXISTS `liveuser_rights` (
  `right_id` int(11) DEFAULT '0',
  `area_id` int(11) DEFAULT '0',
  `right_define_name` char(32) COLLATE latin1_german1_ci DEFAULT NULL,
  `has_implied` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `right_id_idx` (`right_id`),
  UNIQUE KEY `define_name_i_idx` (`area_id`,`right_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_rights_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_rights_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=154 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_right_implied`
--

CREATE TABLE IF NOT EXISTS `liveuser_right_implied` (
  `right_id` int(11) DEFAULT '0',
  `implied_right_id` int(11) DEFAULT '0',
  UNIQUE KEY `id_i_idx` (`right_id`,`implied_right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_translations`
--

CREATE TABLE IF NOT EXISTS `liveuser_translations` (
  `translation_id` int(11) NOT NULL DEFAULT '0',
  `section_id` int(11) NOT NULL DEFAULT '0',
  `section_type` int(11) NOT NULL DEFAULT '0',
  `language_id` char(32) NOT NULL DEFAULT '',
  `name` char(32) DEFAULT NULL,
  `description` char(255) DEFAULT NULL,
  UNIQUE KEY `translations_translation_id_idx` (`translation_id`),
  UNIQUE KEY `translations_translation_i_idx` (`section_id`,`section_type`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_translations_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_translations_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_userrights`
--

CREATE TABLE IF NOT EXISTS `liveuser_userrights` (
  `perm_user_id` int(11) DEFAULT '0',
  `right_id` int(11) DEFAULT '0',
  `right_level` int(11) DEFAULT NULL,
  UNIQUE KEY `id_i_idx` (`perm_user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_users`
--

CREATE TABLE IF NOT EXISTS `liveuser_users` (
  `auth_user_id` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `handle` varchar(32) CHARACTER SET utf8 NOT NULL,
  `passwd` varchar(32) CHARACTER SET utf8 NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `owner_group_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `vorname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `nachname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `geschlecht` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `strasse` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `strassen_nummer` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `plz` int(8) unsigned DEFAULT NULL,
  `stadt` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `land` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `internet` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `tel` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `fax` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `mobile` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `bemerkungen` text CHARACTER SET utf8,
  `avatar` longblob,
  PRIMARY KEY (`auth_user_id`),
  UNIQUE KEY `handle` (`handle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `liveuser_users_seq`
--

CREATE TABLE IF NOT EXISTS `liveuser_users_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nested_set`
--

CREATE TABLE IF NOT EXISTS `nested_set` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `query_string` text CHARACTER SET utf8,
  `owner_user_id` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `owner_group_id` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_num` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `left_id` int(10) unsigned NOT NULL DEFAULT '0',
  `right_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rewrite` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'rewrite für htaccess zusammen mit apache2',
  `uuid` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `right` (`right_id`),
  KEY `left` (`left_id`),
  KEY `order` (`order_num`),
  KEY `level` (`level`),
  KEY `parent_id` (`parent_id`),
  KEY `right_left` (`id`,`parent_id`,`left_id`,`right_id`),
  FULLTEXT KEY `fulltext_conntent` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nested_set_content`
--

CREATE TABLE IF NOT EXISTS `nested_set_content` (
  `nested_set_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `query_string` text CHARACTER SET utf8,
  `auth_user_id` int(32) unsigned DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `page_title` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text CHARACTER SET utf8,
  `gallery` text CHARACTER SET utf8,
  `sidepictures` text CHARACTER SET utf8,
  `content2` text CHARACTER SET utf8 COMMENT 'extra container für content',
  `comments` tinyint(5) NOT NULL DEFAULT '0',
  `background_pic` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `head` text CHARACTER SET utf8,
  `summary` text CHARACTER SET utf8,
  `revision_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uuid` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`nested_set_id`,`revision_id`),
  KEY `created` (`timestamp`),
  KEY `modified` (`modified`),
  FULLTEXT KEY `search` (`content`,`title`,`content2`,`page_title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nested_set_content_revision`
--

CREATE TABLE IF NOT EXISTS `nested_set_content_revision` (
  `auto_id` int(255) NOT NULL AUTO_INCREMENT,
  `nested_set_id` int(10) unsigned NOT NULL DEFAULT '0',
  `auth_user_id` int(32) unsigned NOT NULL DEFAULT '0',
  `gruppe` varchar(11) COLLATE latin1_german1_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `page_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `query_string` text CHARACTER SET utf8,
  `content` text CHARACTER SET utf8,
  `gallery` text CHARACTER SET utf8,
  `sidepictures` text CHARACTER SET utf8,
  `content2` text CHARACTER SET utf8 COMMENT 'extra container für content',
  `comments` tinyint(5) NOT NULL DEFAULT '0',
  `keywords` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `background_pic` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `head` text CHARACTER SET utf8,
  `summary` text CHARACTER SET utf8,
  `revision_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uuid` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`auto_id`),
  FULLTEXT KEY `content2` (`content2`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=379 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nested_set_content_seq`
--

CREATE TABLE IF NOT EXISTS `nested_set_content_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4288 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nested_set_id_seq`
--

CREATE TABLE IF NOT EXISTS `nested_set_id_seq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=748 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nested_set_locks`
--

CREATE TABLE IF NOT EXISTS `nested_set_locks` (
  `lockID` char(32) NOT NULL DEFAULT '',
  `lockTable` char(32) NOT NULL DEFAULT '',
  `lockStamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lockID`,`lockTable`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table locks for comments';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `erstellt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `erscheinungs_datum` date NOT NULL DEFAULT '0000-00-00',
  `expire` date NOT NULL DEFAULT '0000-00-00',
  `news_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `news_content` text CHARACTER SET latin1 COLLATE latin1_german1_ci,
  `news_category` varchar(32) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT 'general',
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_objects`
--

CREATE TABLE IF NOT EXISTS `rampage_objects` (
  `object_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_name` varchar(255) NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`object_id`),
  UNIQUE KEY `rampage_objects_type_object_name` (`type_id`,`object_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_tagged`
--

CREATE TABLE IF NOT EXISTS `rampage_tagged` (
  `user_id` int(10) unsigned NOT NULL,
  `object_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`object_id`,`tag_id`),
  KEY `rampage_tagged_object_id` (`object_id`),
  KEY `rampage_tagged_tag_id` (`tag_id`),
  KEY `rampage_tagged_created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_tags`
--

CREATE TABLE IF NOT EXISTS `rampage_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `rampage_tags_tag_name` (`tag_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_tag_stats`
--

CREATE TABLE IF NOT EXISTS `rampage_tag_stats` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_types`
--

CREATE TABLE IF NOT EXISTS `rampage_types` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `rampage_objects_type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_users`
--

CREATE TABLE IF NOT EXISTS `rampage_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `rampage_users_user_name` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rampage_user_tag_stats`
--

CREATE TABLE IF NOT EXISTS `rampage_user_tag_stats` (
  `user_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`,`tag_id`),
  KEY `rampage_user_tag_stats_tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

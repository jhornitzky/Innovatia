-- phpMiniAdmin dump 1.5.091221
-- Datetime: 2011-05-31 23:23:00
-- Host: localhost
-- Database: innovation_works

/*!40030 SET NAMES utf8 */;
/*!40030 SET GLOBAL max_allowed_packet=16777216 */;

DROP TABLE IF EXISTS `Announcements`;
CREATE TABLE `Announcements` (
  `announcementId` int(12) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `userId` int(12) NOT NULL,
  PRIMARY KEY (`announcementId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Attachments`;
CREATE TABLE `Attachments` (
  `ideaId` int(12) DEFAULT NULL,
  `groupId` int(12) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `createdTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` mediumblob,
  `attachmentId` int(12) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `size` int(12) NOT NULL,
  `userId` int(12) NOT NULL,
  `path` varchar(250) NOT NULL,
  `isDp` tinyint(1) NOT NULL,
  PRIMARY KEY (`attachmentId`),
  KEY `attachmentGroupId` (`groupId`),
  KEY `attachmentIdeaId` (`ideaId`),
  CONSTRAINT `attachmentGroupId` FOREIGN KEY (`groupId`) REFERENCES `Groups` (`groupId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `attachmentIdeaId` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Comments`;
CREATE TABLE `Comments` (
  `commentId` int(12) NOT NULL AUTO_INCREMENT,
  `ideaId` int(12) DEFAULT NULL,
  `text` text NOT NULL,
  `userId` int(11) NOT NULL,
  `groupId` int(12) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`commentId`) USING BTREE,
  KEY `commentIdeaId` (`ideaId`),
  KEY `commentGroupId` (`groupId`),
  KEY `commentUserId` (`userId`),
  CONSTRAINT `commentGroupId` FOREIGN KEY (`groupId`) REFERENCES `Groups` (`groupId`) ON DELETE CASCADE,
  CONSTRAINT `commentIdeaId` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE,
  CONSTRAINT `commentUserId` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `FeatureEvaluation`;
CREATE TABLE `FeatureEvaluation` (
  `featureEvaluationId` int(12) NOT NULL AUTO_INCREMENT,
  `featureId` int(12) NOT NULL,
  `knowledgeCaptured` varchar(50) DEFAULT '0',
  `roleClarity` varchar(50) DEFAULT '0',
  `socialStructureAlignment` varchar(50) DEFAULT '0',
  `socialCapital` varchar(50) DEFAULT '0',
  `coordinationSupport` varchar(50) DEFAULT '0',
  `learningSupported` varchar(50) DEFAULT '0',
  `workPracticeImprovement` varchar(50) DEFAULT '0',
  `taskSupport` varchar(50) DEFAULT '0',
  `userId` int(12) DEFAULT NULL,
  `ideaFeatureEvaluationId` int(12) NOT NULL,
  `score` int(2) NOT NULL,
  PRIMARY KEY (`featureEvaluationId`),
  KEY `featureEvalFeatureId` (`featureId`),
  KEY `ideaFeatureEvaluationId` (`ideaFeatureEvaluationId`),
  CONSTRAINT `featureEvalFeatureId` FOREIGN KEY (`featureId`) REFERENCES `Features` (`featureId`) ON DELETE CASCADE,
  CONSTRAINT `ideaFeatureEvaluationId` FOREIGN KEY (`ideaFeatureEvaluationId`) REFERENCES `IdeaFeatureEvaluations` (`ideaFeatureEvaluationId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Features`;
CREATE TABLE `Features` (
  `featureId` int(12) NOT NULL AUTO_INCREMENT,
  `feature` text NOT NULL,
  `process` text,
  `communication` text,
  `knowledge` text,
  `relationships` text,
  `ideaId` int(12) NOT NULL,
  PRIMARY KEY (`featureId`),
  KEY `featureIdeaId` (`ideaId`),
  CONSTRAINT `featureIdeaId` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `GroupIdeas`;
CREATE TABLE `GroupIdeas` (
  `groupId` int(12) NOT NULL,
  `ideaId` int(12) NOT NULL,
  `canEdit` tinyint(1) NOT NULL,
  PRIMARY KEY (`groupId`,`ideaId`),
  KEY `groupIdeaIdeaId` (`ideaId`),
  CONSTRAINT `groupIdeaGroupId` FOREIGN KEY (`groupId`) REFERENCES `Groups` (`groupId`) ON DELETE CASCADE,
  CONSTRAINT `groupIdeaIdeaId` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `GroupUsers`;
CREATE TABLE `GroupUsers` (
  `groupId` int(12) NOT NULL,
  `userId` int(12) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupId`,`userId`),
  KEY `groupUserUserId` (`userId`),
  CONSTRAINT `groupUserGroupId` FOREIGN KEY (`groupId`) REFERENCES `Groups` (`groupId`) ON DELETE CASCADE,
  CONSTRAINT `groupUserUserId` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Groups`;
CREATE TABLE `Groups` (
  `groupId` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `tags` varchar(100) DEFAULT NULL,
  `description` text,
  `userId` int(12) NOT NULL,
  `lastUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdTime` datetime NOT NULL,
  PRIMARY KEY (`groupId`),
  KEY `groupUserId` (`userId`),
  CONSTRAINT `groupUserId` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1095 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `IdeaFeatureEvaluations`;
CREATE TABLE `IdeaFeatureEvaluations` (
  `ideaId` int(12) DEFAULT NULL,
  `ideaFeatureEvaluationId` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `userId` int(12) NOT NULL,
  `score` int(2) NOT NULL,
  `summary` text,
  PRIMARY KEY (`ideaFeatureEvaluationId`) USING BTREE,
  KEY `ideaFeatureEvalIdeaId` (`ideaId`),
  CONSTRAINT `ideaFeatureEvalIdeaId` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Ideas`;
CREATE TABLE `Ideas` (
  `ideaId` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `proposedService` text,
  `users` text,
  `artifact` text,
  `serviceDescription` text,
  `userId` int(12) NOT NULL,
  `lastUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `workBenefit` text,
  `businessGoal` text,
  `market` text,
  `assumptions` text,
  `stakeholders` text,
  `isPublic` tinyint(1) NOT NULL,
  `createdTime` datetime NOT NULL,
  PRIMARY KEY (`ideaId`),
  KEY `ideaUserId` (`userId`),
  CONSTRAINT `ideaUserId` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35551 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Notes`;
CREATE TABLE `Notes` (
  `noteId` int(12) NOT NULL AUTO_INCREMENT,
  `fromUserId` int(12) DEFAULT NULL,
  `toUserId` int(12) DEFAULT NULL,
  `noteText` text,
  `createdTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isRead` tinyint(1) NOT NULL,
  PRIMARY KEY (`noteId`),
  KEY `fromUserId` (`fromUserId`),
  KEY `toUserId` (`toUserId`),
  CONSTRAINT `noteToUserId` FOREIGN KEY (`toUserId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=355 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ReferenceData`;
CREATE TABLE `ReferenceData` (
  `referenceDataId` int(12) NOT NULL AUTO_INCREMENT,
  `value` varchar(200) NOT NULL,
  `key2` varchar(50) DEFAULT NULL,
  `key1` varchar(50) NOT NULL,
  `key3` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`referenceDataId`),
  KEY `refDataLookupKeys` (`key1`,`key2`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `RiskEvaluation`;
CREATE TABLE `RiskEvaluation` (
  `riskEvaluationId` int(12) NOT NULL AUTO_INCREMENT,
  `ideaId` int(12) NOT NULL,
  `marketValuation` varchar(50) DEFAULT '0',
  `conceptClarity` varchar(50) DEFAULT '0',
  `serviceCompetitiveness` varchar(50) DEFAULT '0',
  `buildEconomically` varchar(50) DEFAULT '0',
  `profitability` varchar(50) DEFAULT '0',
  `interest` varchar(50) DEFAULT '0',
  `groupId` int(12) DEFAULT '0',
  `userId` int(12) DEFAULT '0',
  `differentiation` varchar(50) DEFAULT '0',
  `score` int(2) NOT NULL,
  `createdTime` datetime NOT NULL,
  `lastUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`riskEvaluationId`) USING BTREE,
  KEY `riskEvalIdeaFk` (`ideaId`),
  CONSTRAINT `riskEvalIdeaFk` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Roles`;
CREATE TABLE `Roles` (
  `roleId` int(12) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `communication` text,
  `workType` text,
  `knowledgeNeeded` text,
  `innovation` text,
  `ideaId` int(12) NOT NULL,
  PRIMARY KEY (`roleId`),
  KEY `roleIdeaFk` (`ideaId`),
  CONSTRAINT `roleIdeaFk` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Selections`;
CREATE TABLE `Selections` (
  `selectionId` int(12) NOT NULL AUTO_INCREMENT,
  `ideaId` int(12) NOT NULL,
  `reason` text NOT NULL,
  `lastUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `createdTime` datetime NOT NULL,
  PRIMARY KEY (`selectionId`),
  KEY `selectIdeaFk` (`ideaId`),
  CONSTRAINT `selectIdeaFk` FOREIGN KEY (`ideaId`) REFERENCES `Ideas` (`ideaId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Tasks`;
CREATE TABLE `Tasks` (
  `taskId` int(12) NOT NULL AUTO_INCREMENT,
  `effort` int(3) DEFAULT NULL,
  `complete` varchar(3) NOT NULL DEFAULT '0',
  `createdTime` datetime NOT NULL,
  `lastUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `featureId` int(12) NOT NULL,
  `startDate` date DEFAULT NULL,
  `finishDate` date DEFAULT NULL,
  PRIMARY KEY (`taskId`),
  KEY `taskFeatureId` (`featureId`),
  CONSTRAINT `taskFeatureId` FOREIGN KEY (`featureId`) REFERENCES `Features` (`featureId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `userId` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `organization` varchar(20) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `interests` text,
  `lastUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isPublic` tinyint(1) NOT NULL DEFAULT '1',
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(1) NOT NULL DEFAULT '1',
  `createdTime` datetime NOT NULL,
  `isExternal` tinyint(1) NOT NULL DEFAULT '0',
  `cookie` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Views`;
CREATE TABLE `Views` (
  `viewId` int(12) NOT NULL AUTO_INCREMENT,
  `ideaId` int(12) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userId` int(12) NOT NULL,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`viewId`)
) ENGINE=MyISAM AUTO_INCREMENT=642 DEFAULT CHARSET=latin1;


-- phpMiniAdmin dump end

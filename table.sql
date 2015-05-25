
CREATE TABLE IF NOT EXISTS `qa_noOfResponses` (
  `pageId` int(11) NOT NULL,
  `numResponses` int(11) NOT NULL,
  `TScore` float NOT NULL,
  `IScore` float NOT NULL,
  `PScore` float NOT NULL,
  `SScore` float NOT NULL,
  `overallScore` float NOT NULL,
  PRIMARY KEY (`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `qa_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `completeFlag` int(11) NOT NULL,
  `answer` varchar(500) NOT NULL,
  `q` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


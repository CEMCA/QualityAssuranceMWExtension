
CREATE TABLE IF NOT EXISTS `qa_noOfResponses` (
  `pageId` int(11) NOT NULL,
  `number of responses` int(11) NOT NULL,
  `TScore` int(11) NOT NULL,
  `IScore` int(11) NOT NULL,
  `PScore` int(11) NOT NULL,
  `SScore` int(11) NOT NULL,
  `overallScore` int(11) NOT NULL,
  PRIMARY KEY (`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `qa_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `completeFlag` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `q` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `qa_answers` CHANGE `answer` `answer` VARCHAR( 500 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL ;
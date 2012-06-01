
--
-- Table structure for table `jos_clubreg_contact_details`
--

CREATE TABLE IF NOT EXISTS `jos_clubreg_contact_details` (
  `member_id` int(11) NOT NULL,
  `contact_detail` varchar(30) NOT NULL,
  `contact_value` varchar(512) NOT NULL,
  PRIMARY KEY (`member_id`,`contact_detail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contact Details';

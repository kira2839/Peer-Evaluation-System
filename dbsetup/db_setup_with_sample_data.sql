CREATE TABLE IF NOT EXISTS `student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `confirmation_code` varchar(255) DEFAULT NULL,
  `last_generated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_code_used` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `PK_STUDENT_EMAIL_ID` (`email_address`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `email_address`, `student_name`, `confirmation_code`, `last_generated_time`, `is_code_used`) VALUES
(1, 'smishra9@buffalo.edu', 'Sid', '178e3db0a346ccecf97c1a231b720cc9f2704791f04e8b21981559cf3dff84f9', '2019-07-04 19:19:03', 1),
(2, 'a@buffalo.edu', 'anne', NULL, '2019-06-27 18:15:53', 0),
(3, 'b@buffalo.edu', 'Alice', NULL, '2019-06-27 18:15:53', 0),
(4, 'gouthamt@buffalo.edu', 'Goutham', 'b37564a3885c5ea89f24a8cf2796087b08931ca549f9371d3406ad8189cc9929', '2019-07-02 00:49:13', 1),
(5, 'kuduvago@buffalo.edu', 'Karthik', '64839aa3c420b6d43220fc3b37075c45b2f686499b95f43fe53e8f14c75359c9', '2019-07-04 03:23:29', 1),
(6, 'c@buffalo.edu', 'Mallory', NULL, '2019-06-27 18:15:53', 0),
(7, 'priyanka@buffalo.edu', 'Priyanka', '3d439991a3ce1bebbfe85a14c037f06e020a6dfd07e571b042ebfa9afa18ee2c', '2019-06-29 17:05:58', 1);

CREATE TABLE IF NOT EXISTS `student_group` (
  `group_id` int(10) unsigned NOT NULL,
  `fk_student_id` int(10) unsigned NOT NULL DEFAULT '0',
  `course_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`,`fk_student_id`,`course_name`),
  UNIQUE KEY `PK_STUDENT_ID_UNIQUE` (`fk_student_id`,`course_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_group`
--

INSERT INTO `student_group` (`group_id`, `fk_student_id`, `course_name`) VALUES
(1, 1, 'CSE542'),
(4, 1, 'CSE543'),
(5, 1, 'CSE544'),
(2, 1, 'CSE600'),
(1, 1, 'CSE611'),
(1, 2, 'CSE542'),
(3, 2, 'CSE543'),
(3, 2, 'CSE600'),
(1, 3, 'CSE542'),
(5, 3, 'CSE544'),
(3, 4, 'CSE543'),
(2, 4, 'CSE600'),
(4, 5, 'CSE543'),
(5, 6, 'CSE544'),
(1, 7, 'CSE611');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_group`
--
ALTER TABLE `student_group`
  ADD CONSTRAINT `FK_STUDENT_GROUP_ID` FOREIGN KEY (`fk_student_id`) REFERENCES `student` (`id`);

CREATE TABLE IF NOT EXISTS `evaluation_meaning` (
  `key_name` varchar(255) NOT NULL DEFAULT '',
  `value_0` varchar(255) DEFAULT NULL,
  `value_1` varchar(255) DEFAULT NULL,
  `value_2` varchar(255) DEFAULT NULL,
  `value_3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `evaluation_meaning`
--

INSERT INTO `evaluation_meaning` (`key_name`, `value_0`, `value_1`, `value_2`, `value_3`) VALUES
('Leadership', 'Rarely takes leadership, does not collaborate but sometimes assists teammates', 'Occasional leadership, generally collaborates and assists teammates', 'Leads when necessary, willing to collaborate and assist teammates', 'Takes leadership, always collaborates and helps teammates'),
('Participation', 'Often unprepared, misses meetings and rarely participates in them', 'Occasionally misses meetings, somewhat prepared but offers unclear ideas ', 'Mostly participates in meetings, comes prepared and offers useful ideas in them', 'Always participates in meetings, comes prepared to and expresses clear ideas'),
('Professionalism', 'Often discourteous or openly critical of teammates and does not listen to other views', 'Not always considerate towards teammates but appreciates their view but ignores it', 'Mostly courteous to and values teammates views and willing to consider them', 'Always courteous to and values views of teammates and considers them'),
('Quality', 'Mostly does not commit to shared documents and others are required to fix their work', 'Occasionally does not commit to shared documents and others are required to fix their work ', 'Often commits to shared docs but occasionally someone needs to fix their work', 'Frequently commits to shared docs with their work rarely needing to be fixed by others'),
('Role', 'Does not willingly team roles nor completes work assigned', 'Usually accepts team roles & completes work sometimes', 'Accepts team roles & often completes work', 'Accepts team roles & always completes work');

/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : localhost:3306
 Source Schema         : o6u_onlineq

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 13/08/2020 16:37:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent` int(11) NULL DEFAULT NULL,
  `instructorID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `instructorID`(`instructorID`) USING BTREE,
  INDEX `parent`(`parent`) USING BTREE,
  CONSTRAINT `course_ibfk_1` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `course_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for group_invitations
-- ----------------------------
DROP TABLE IF EXISTS `group_invitations`;
CREATE TABLE `group_invitations`  (
  `groupID` int(11) NULL DEFAULT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  UNIQUE INDEX `code`(`code`) USING BTREE,
  INDEX `groupID`(`groupID`) USING BTREE,
  CONSTRAINT `group_invitations_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTest` int(11) NULL DEFAULT NULL,
  `settingID` int(11) NULL DEFAULT NULL,
  `instructorID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `instructorID`(`instructorID`) USING BTREE,
  INDEX `settingID`(`settingID`) USING BTREE,
  INDEX `groups_ibfk_2`(`assignedTest`) USING BTREE,
  CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`assignedTest`) REFERENCES `test` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `groups_ibfk_3` FOREIGN KEY (`settingID`) REFERENCES `test_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for groups_has_students
-- ----------------------------
DROP TABLE IF EXISTS `groups_has_students`;
CREATE TABLE `groups_has_students`  (
  `groupID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `joinDate` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  UNIQUE INDEX `my_unique_key`(`groupID`, `studentID`) USING BTREE,
  INDEX `groups_has_students_ibfk_2`(`studentID`) USING BTREE,
  CONSTRAINT `groups_has_students_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `groups_has_students_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for instructor
-- ----------------------------
DROP TABLE IF EXISTS `instructor`;
CREATE TABLE `instructor`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_expire` timestamp(0) NULL DEFAULT NULL,
  `suspended` int(11) NOT NULL DEFAULT 0,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of instructor
-- ----------------------------
INSERT INTO `instructor` VALUES (7, 'System Administrator', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '', NULL, NULL, 0, 1);

-- ----------------------------
-- Table structure for instructor_invitations
-- ----------------------------
DROP TABLE IF EXISTS `instructor_invitations`;
CREATE TABLE `instructor_invitations`  (
  `code` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mails
-- ----------------------------
DROP TABLE IF EXISTS `mails`;
CREATE TABLE `mails`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resultID` int(11) NULL DEFAULT NULL,
  `studentID` int(11) NULL DEFAULT NULL,
  `instructorID` int(11) NULL DEFAULT NULL,
  `sends_at` timestamp(0) NULL DEFAULT NULL,
  `sent` tinyint(1) NULL DEFAULT 0,
  `type` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `resultID`(`resultID`) USING BTREE,
  INDEX `instructorID`(`instructorID`) USING BTREE,
  INDEX `studentID`(`studentID`) USING BTREE,
  CONSTRAINT `mails_ibfk_1` FOREIGN KEY (`resultID`) REFERENCES `result` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mails_ibfk_2` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mails_ibfk_3` FOREIGN KEY (`studentID`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` int(1) NULL DEFAULT NULL COMMENT '0 - MCQ / 1 - T/F /2- COMPLETE/',
  `points` int(11) NOT NULL DEFAULT 1,
  `difficulty` tinyint(1) NULL DEFAULT 1,
  `isTrue` tinyint(1) NOT NULL DEFAULT 1,
  `instructorID` int(11) NOT NULL,
  `courseID` int(11) NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `question_ibfk_1`(`instructorID`) USING BTREE,
  INDEX `question_ibfk_2`(`courseID`) USING BTREE,
  CONSTRAINT `question_ibfk_1` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `question_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 176 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for question_answers
-- ----------------------------
DROP TABLE IF EXISTS `question_answers`;
CREATE TABLE `question_answers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionID` int(11) NULL DEFAULT NULL,
  `answer` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `matchAnswer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `isCorrect` tinyint(1) NULL DEFAULT 1,
  `points` int(2) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `answers_ibfk_1`(`questionID`) USING BTREE,
  INDEX `matchAnswer`(`matchAnswer`) USING BTREE,
  CONSTRAINT `question_answers_ibfk_1` FOREIGN KEY (`questionID`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 904 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for result
-- ----------------------------
DROP TABLE IF EXISTS `result`;
CREATE TABLE `result`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `groupID` int(11) NULL DEFAULT NULL,
  `settingID` int(11) NULL DEFAULT NULL,
  `startTime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endTime` timestamp(0) NULL DEFAULT NULL,
  `isTemp` tinyint(1) NOT NULL DEFAULT 1,
  `hostname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ipaddr` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `testID_2`(`testID`, `studentID`) USING BTREE,
  INDEX `result_ibfk_2`(`studentID`) USING BTREE,
  INDEX `settingID`(`settingID`) USING BTREE,
  INDEX `groupID`(`groupID`) USING BTREE,
  CONSTRAINT `result_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `result_ibfk_3` FOREIGN KEY (`testID`) REFERENCES `test` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `result_ibfk_4` FOREIGN KEY (`settingID`) REFERENCES `test_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `result_ibfk_5` FOREIGN KEY (`groupID`) REFERENCES `groups` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for result_answers
-- ----------------------------
DROP TABLE IF EXISTS `result_answers`;
CREATE TABLE `result_answers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resultID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `answerID` int(11) NULL DEFAULT NULL,
  `isTrue` tinyint(1) NULL DEFAULT NULL,
  `textAnswer` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `points` int(3) NULL DEFAULT -1,
  `isCorrect` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `FK_result_answers_result`(`resultID`) USING BTREE,
  INDEX `FK_result_answers_question`(`questionID`) USING BTREE,
  INDEX `answerID`(`answerID`) USING BTREE,
  CONSTRAINT `FK_result_answers_result` FOREIGN KEY (`resultID`) REFERENCES `result` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `result_answers_ibfk_1` FOREIGN KEY (`answerID`) REFERENCES `question_answers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `result_answers_ibfk_2` FOREIGN KEY (`questionID`) REFERENCES `question` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 454 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_expire` timestamp(0) NULL DEFAULT NULL,
  `suspended` tinyint(1) NULL DEFAULT 0,
  `sessionID` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for students_has_tests
-- ----------------------------
DROP TABLE IF EXISTS `students_has_tests`;
CREATE TABLE `students_has_tests`  (
  `studentID` int(11) NULL DEFAULT NULL,
  `testID` int(11) NULL DEFAULT NULL,
  `settingID` int(11) NULL DEFAULT NULL,
  UNIQUE INDEX `StudentID`(`studentID`, `testID`) USING BTREE,
  INDEX `students_has_tests_ibfk_1`(`studentID`) USING BTREE,
  INDEX `students_has_tests_ibfk_2`(`testID`) USING BTREE,
  INDEX `students_has_tests_ibfk_3`(`settingID`) USING BTREE,
  CONSTRAINT `students_has_tests_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `students_has_tests_ibfk_2` FOREIGN KEY (`testID`) REFERENCES `test` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `students_has_tests_ibfk_3` FOREIGN KEY (`settingID`) REFERENCES `test_settings` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tempquestions
-- ----------------------------
DROP TABLE IF EXISTS `tempquestions`;
CREATE TABLE `tempquestions`  (
  `resultID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `rand` int(4) NULL DEFAULT NULL,
  UNIQUE INDEX `resultID`(`resultID`, `questionID`) USING BTREE,
  INDEX `quest`(`questionID`) USING BTREE,
  CONSTRAINT `tempquestions_ibfk_1` FOREIGN KEY (`resultID`) REFERENCES `result` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `courseID` int(11) NULL DEFAULT NULL,
  `deleted` tinyint(1) NULL DEFAULT 0,
  `instructorID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `instructorID`(`instructorID`) USING BTREE,
  INDEX `courseID`(`courseID`) USING BTREE,
  CONSTRAINT `test_ibfk_1` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `test_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for test_invitations
-- ----------------------------
DROP TABLE IF EXISTS `test_invitations`;
CREATE TABLE `test_invitations`  (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `testID` int(11) NULL DEFAULT NULL,
  `settingID` int(11) NULL DEFAULT NULL,
  `used` tinyint(1) NULL DEFAULT 0,
  `useLimit` int(11) NULL DEFAULT NULL,
  `instructorID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `instructorID`(`instructorID`) USING BTREE,
  INDEX `settingID`(`settingID`) USING BTREE,
  INDEX `test_invitations_ibfk_1`(`testID`) USING BTREE,
  CONSTRAINT `test_invitations_ibfk_1` FOREIGN KEY (`testID`) REFERENCES `test` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `test_invitations_ibfk_3` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `test_invitations_ibfk_4` FOREIGN KEY (`settingID`) REFERENCES `test_settings` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for test_random_questions
-- ----------------------------
DROP TABLE IF EXISTS `test_random_questions`;
CREATE TABLE `test_random_questions`  (
  `testID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `questionsCount` int(11) NOT NULL,
  `difficulty` int(1) NULL DEFAULT 1,
  UNIQUE INDEX `testID_2`(`testID`, `courseID`, `difficulty`) USING BTREE,
  INDEX `testID`(`testID`) USING BTREE,
  INDEX `courseID`(`courseID`) USING BTREE,
  CONSTRAINT `test_random_questions_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `test_random_questions_ibfk_2` FOREIGN KEY (`testID`) REFERENCES `test` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for test_settings
-- ----------------------------
DROP TABLE IF EXISTS `test_settings`;
CREATE TABLE `test_settings`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startTime` datetime(0) NULL DEFAULT NULL,
  `endTime` datetime(0) NULL DEFAULT NULL,
  `duration` int(3) NULL DEFAULT NULL,
  `random` tinyint(255) NULL DEFAULT NULL,
  `prevQuestion` int(1) NULL DEFAULT NULL,
  `viewAnswers` tinyint(1) NULL DEFAULT NULL,
  `releaseResult` int(1) NULL DEFAULT 1,
  `sendToStudent` tinyint(1) NULL DEFAULT NULL,
  `sendToInstructor` tinyint(1) NULL DEFAULT NULL,
  `passPercent` int(3) NULL DEFAULT NULL,
  `instructorID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `instructorID`(`instructorID`) USING BTREE,
  CONSTRAINT `test_settings_ibfk_1` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 76 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tests_has_questions
-- ----------------------------
DROP TABLE IF EXISTS `tests_has_questions`;
CREATE TABLE `tests_has_questions`  (
  `testID` int(11) NULL DEFAULT NULL,
  `questionID` int(11) NULL DEFAULT NULL,
  `rand` int(4) NULL DEFAULT NULL,
  UNIQUE INDEX `my_unique_key`(`testID`, `questionID`) USING BTREE,
  INDEX `tests_has_questions_ibfk_2`(`questionID`) USING BTREE,
  CONSTRAINT `tests_has_questions_ibfk_1` FOREIGN KEY (`testID`) REFERENCES `test` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tests_has_questions_ibfk_2` FOREIGN KEY (`questionID`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Function structure for checkAnswer
-- ----------------------------
DROP FUNCTION IF EXISTS `checkAnswer`;
delimiter ;;
CREATE  FUNCTION `checkAnswer`(`resID` INT, `qID` INT) RETURNS tinyint(1)
BEGIN
    DECLARE RES INT;
		IF ((select type from question where id = qID) = 0 || (select type from question where id = qID) = 3) THEN
			(select count(*) INTO RES from (
			SELECT answerID From result_answers ra WHERE resultID = resID AND questionID = qID
			AND answerID IN (SELECT id FROM question_answers where isCorrect and questionID = ra.questionID)
			) as t
			HAVING COUNT(*) = (SELECT COUNT(*) FROM question_answers where questionID = qID AND isCorrect));
			IF RES > 0 THEN
				RETURN TRUE;    
			ELSE
				RETURN FALSE;                           
			END IF;
		ELSEIF ((select type from question where id = qID) = 2) THEN
			(SELECT COUNT(*) INTO RES From result_answers RA WHERE resultID = resID AND questionID = qID
			and textAnswer IN (SELECT answer FROM question_answers where questionID = RA.questionID));		
			IF RES > 0 THEN
				RETURN TRUE;    
			ELSE
				RETURN FALSE;                           
			END IF;
		ELSE
			RETURN FALSE;       
		END IF;
	END
;;
delimiter ;

-- ----------------------------
-- Function structure for generateGroupInvites
-- ----------------------------
DROP FUNCTION IF EXISTS `generateGroupInvites`;
delimiter ;;
CREATE  FUNCTION `generateGroupInvites`(`groupID` int,`count` int,pf varchar(50)) RETURNS int(11)
BEGIN
  DECLARE i INT DEFAULT 0;
  WHILE i < count DO
    INSERT INTO group_invitations(groupID,`code`) VALUES (
      groupID,CONCAT(COALESCE(pf,''),CRC32(CONCAT(NOW(), RAND())))
    );
    SET i = i + 1;
  END WHILE;
	RETURN 0;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for generateInstructorInvites
-- ----------------------------
DROP PROCEDURE IF EXISTS `generateInstructorInvites`;
delimiter ;;
CREATE  PROCEDURE `generateInstructorInvites`(IN `count` INT)
BEGIN
  DECLARE i INT DEFAULT 0;
  WHILE i < count DO
    INSERT INTO instructor_invitations(`code`) VALUES (
      CRC32(CONCAT(NOW(), RAND()))
    );
    SET i = i + 1;
  END WHILE;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for getQuestionRightAnswers
-- ----------------------------
DROP FUNCTION IF EXISTS `getQuestionRightAnswers`;
delimiter ;;
CREATE  FUNCTION `getQuestionRightAnswers`(`qid` INT) RETURNS varchar(255) CHARSET utf8
BEGIN
DECLARE C VARCHAR(255);
DECLARE qtype INT;
SET qtype = (select type from question where id = qid);
IF (qtype = 1) THEN
SELECT 'True' INTO C FROM question WHERE id = qID AND isTrue = 1;
	IF C IS NULL THEN
	SET C = 'False';
	END IF;
ELSEIF (qtype = 2) THEN
SELECT GROUP_CONCAT(answer SEPARATOR ', ') into C FROM question_answers
WHERE questionID = qid
GROUP BY questionID;

ELSEIF (qtype = 4) THEN
SELECT GROUP_CONCAT(CONCAT(answer, ' => ', matchAnswer) ORDER BY id SEPARATOR ', ') into C FROM question_answers
WHERE questionID = qid
GROUP BY questionID;
ELSE
SELECT GROUP_CONCAT(answer SEPARATOR ', ') into C FROM question_answers
WHERE questionID = qid AND isCorrect
GROUP BY questionID;
END IF;
RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for getQuestionsInTest
-- ----------------------------
DROP FUNCTION IF EXISTS `getQuestionsInTest`;
delimiter ;;
CREATE  FUNCTION `getQuestionsInTest`(`tID` INT) RETURNS int(11)
BEGIN
DECLARE C INT(11);
SELECT ((SELECT count(*) FROM tests_has_questions WHERE testID = tID) + COALESCE((SELECT SUM(questionsCount) FROM test_random_questions WHERE testID = tID),0)) INTO C;
   IF (C IS NULL) THEN
      SET C = 0;
   END IF;


RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for getResultGivenAnswers
-- ----------------------------
DROP FUNCTION IF EXISTS `getResultGivenAnswers`;
delimiter ;;
CREATE  FUNCTION `getResultGivenAnswers`(`rid` INT, `qid` INT) RETURNS varchar(255) CHARSET utf8
BEGIN
DECLARE C VARCHAR(255);
DECLARE qtype INT;
SET qtype = (select type from question where id = qID);
IF (qtype = 1) THEN
	SELECT "True" INTO C FROM result_answers WHERE questionID = qid AND resultID = rid AND isTrue = 1;

	SELECT "False" INTO C FROM result_answers WHERE questionID = qid AND resultID = rid AND isTrue = 0;
ELSEIF (qtype = 4) THEN 
SELECT GROUP_CONCAT(CONCAT(answer, ' => ', textAnswer) ORDER BY a.id SEPARATOR ', ') INTO C FROM result_answers ra
INNER JOIN question_answers a
ON a.id = ra.answerID
WHERE ra.questionID = qid AND ra.resultID = rid;
ELSEIF (qtype = 2 || qtype = 5) THEN 
SELECT textAnswer INTO C FROM result_answers WHERE questionID = qid AND resultID = rid;
ELSE
SELECT GROUP_CONCAT(answer SEPARATOR ', ') INTO C FROM result_answers ra
INNER JOIN question_answers a
ON a.id = ra.answerID
WHERE ra.questionID = qid AND ra.resultID = rid;
END IF;
RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for getResultGrade
-- ----------------------------
DROP FUNCTION IF EXISTS `getResultGrade`;
delimiter ;;
CREATE  FUNCTION `getResultGrade`(`rid` INT) RETURNS int(11)
BEGIN
DECLARE C INT(11);
SELECT SUM(points) INTO C
FROM (
SELECT CASE (SELECT type from question where id = questionID) WHEN 4 THEN
(SELECT SUM(points) FROM question_answers qa WHERE qa.questionID = ra.questionID) 
ELSE 
(SELECT SUM(points) FROM question q WHERE q.id = ra.questionID) 
END AS points from result_answers ra where resultID = rid and isCorrect GROUP BY questionID) as t;


   IF (C IS NULL) THEN
      SET C = 0;
   END IF;


RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for getResultMaxGrade
-- ----------------------------
DROP FUNCTION IF EXISTS `getResultMaxGrade`;
delimiter ;;
CREATE  FUNCTION `getResultMaxGrade`(`rid` INT) RETURNS int(11)
BEGIN
DECLARE C INT(11);
SELECT SUM(points) INTO C
FROM (SELECT CASE (SELECT type FROM question WHERE id = ra.questionID) 
WHEN 4 THEN
(SELECT SUM(points) FROM question_answers WHERE questionID = ra.questionID) 
ELSE 
(SELECT SUM(points) FROM question q WHERE q.id = ra.questionID) 
END points
FROM result_answers ra
WHERE resultID = rid
GROUP BY questionID) AS T;
   IF (C IS NULL) THEN
      SET C = 0;
   END IF;


RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for getStudentTests
-- ----------------------------
DROP PROCEDURE IF EXISTS `getStudentTests`;
delimiter ;;
CREATE  PROCEDURE `getStudentTests`(IN `studID` INT)
BEGIN
			SET @ct :=  convert_tz(now(),@@session.time_zone,'+02:00');
			SELECT t.id,t.name,g.name groupName,i.name instructor,ts.endTime,ts.id settingID,
			CASE WHEN (@ct BETWEEN ts.startTime AND ts.endTime) THEN 'Available'
			WHEN @ct < ts.startTime THEN 'Not Started Yet'
			when @ct > ts.endTime THEN 'Finished'
			ELSE 'Not Available'
			END AS status
			from groups g
      inner join groups_has_students gs
      on gs.studentID = studID and g.id = gs.groupID
      inner join test t
      on t.id = g.assignedTest
      inner join test_settings ts
      on ts.id = g.settingID
      inner join instructor i
      on i.id = t.instructorID
			WHERE t.id NOT IN (SELECT testID from result where studentID = gs.studentID);
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for getTestByCode
-- ----------------------------
DROP PROCEDURE IF EXISTS `getTestByCode`;
delimiter ;;
CREATE  PROCEDURE `getTestByCode`(IN `code` varchar(100))
BEGIN
	SELECT t.id,t.name,c.name category,i.name instructor,ts.endTime,ti.settingID,ts.passPercent,ts.duration,ts.random,ts.startTime,ts.sendToStudent,getQuestionsInTest(t.id) questions from test_invitations ti
      inner join test t 
      on t.id = ti.testID
      LEFT join test_settings ts
      on ts.id = ti.settingID
      inner join category c
      on c.id = t.categoryID
      inner join instructor i
      on i.id = t.instructorID
			where ti.id = AES_DECRYPT(UNHEX(code), 'O6U');

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for getTestById
-- ----------------------------
DROP PROCEDURE IF EXISTS `getTestById`;
delimiter ;;
CREATE  PROCEDURE `getTestById`(IN `studID` INT,IN `tID` INT)
BEGIN
      SELECT t.id,t.name,c.name category,i.name instructor,getQuestionsInTest(t.id) questions,ts.startTime,ts.duration,ts.passPercent,ts.endTime,ts.id settingID,ts.random from groups g
      inner join groups_has_students gs
      on gs.studentID = studID and g.id = gs.groupID
      inner join test t
      on t.id = g.assignedTest
      inner join test_settings ts
      on ts.id = g.settingID
      inner join category c
      on c.id = t.categoryID
      inner join instructor i
      on i.id = t.instructorID
      where (convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN ts.startTime AND ts.endTime)
      AND t.id NOT IN (SELECT testID from result where studentID = gs.studentID) AND t.id = tID;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for getTestGrade
-- ----------------------------
DROP FUNCTION IF EXISTS `getTestGrade`;
delimiter ;;
CREATE  FUNCTION `getTestGrade`(`tid` INT) RETURNS int(11)
BEGIN
DECLARE C INT(11);
SELECT SUM(points) INTO C
FROM (
SELECT 
		CASE (SELECT type FROM question WHERE id = thq.questionID) 
		WHEN 4 THEN
		(SELECT SUM(points) FROM question_answers WHERE questionID = thq.questionID) 
		ELSE 
		(SELECT SUM(points) FROM question q WHERE q.id = thq.questionID) 
		END points
FROM tests_has_questions thq
WHERE testID = tid
GROUP BY questionID) AS T;
   IF (C IS NULL) THEN
      SET C = 0;
   END IF;


RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for InsertRandomRules
-- ----------------------------
DROP PROCEDURE IF EXISTS `InsertRandomRules`;
delimiter ;;
CREATE  PROCEDURE `InsertRandomRules`(IN `studID` INT, IN `tID` INT, IN `cid` INT,IN `diff` INT, IN `lim` INT)
INSERT INTO tempquestions(resultID, questionID,rand)
SELECT (SELECT MAX(id) FROM result WHERE studentID = studID) AS resultID, id,(select floor(0+ RAND() * 10000)) FROM question q
WHERE NOT EXISTS (SELECT 1 FROM tests_has_questions WHERE testID = tID AND questionID = q.id)
AND !deleted 
AND courseID = cid 
AND difficulty = diff LIMIT lim
;;
delimiter ;

-- ----------------------------
-- Function structure for Result_CorrectQuestions
-- ----------------------------
DROP FUNCTION IF EXISTS `Result_CorrectQuestions`;
delimiter ;;
CREATE  FUNCTION `Result_CorrectQuestions`(`rid` INT) RETURNS int(11)
BEGIN
DECLARE C INT(11);
select count(*) INTO C from (select questionID from result_answers where resultID = rid  GROUP BY questionID 
HAVING CASE (SELECT type from question where id = questionID) WHEN 4 THEN 
MAX(isCorrect) = 1 ELSE MIN(isCorrect) = 1 END) t;
IF (C IS NULL) THEN
      SET C = 0;
   END IF;

RETURN C;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for Result_getQuestionsAnswers
-- ----------------------------
DROP PROCEDURE IF EXISTS `Result_getQuestionsAnswers`;
delimiter ;;
CREATE  PROCEDURE `Result_getQuestionsAnswers`(IN `rid` INT)
select 
 DISTINCT q.id,q.question,q.`type`,
getResultGivenAnswers(ra.resultID,ra.questionID) AS GivenAnswers,
getQuestionRightAnswers(q.id) AS CorrectAnswers,
checkAnswer(ra.id,q.id) AS RightQuestion,
q.points * isCorrect AS points

from result_answers ra 
LEFT JOIN question q
on q.id = ra.questionID
where resultID = rid
;;
delimiter ;

-- ----------------------------
-- Function structure for Result_WrongQuestions
-- ----------------------------
DROP FUNCTION IF EXISTS `Result_WrongQuestions`;
delimiter ;;
CREATE  FUNCTION `Result_WrongQuestions`(`rid` INT) RETURNS int(11)
BEGIN
DECLARE C INT(11);
select count(*) INTO C from (
select questionID from result_answers where resultID = rid  GROUP BY questionID 
HAVING CASE (SELECT type from question where id = questionID) WHEN 4 THEN 
MAX(isCorrect) = 0 ELSE MIN(isCorrect) = 0 END) t;
IF (C IS NULL) THEN
      SET C = 0;
   END IF;
RETURN C;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table result_answers
-- ----------------------------
DROP TRIGGER IF EXISTS `as`;
delimiter ;;
CREATE TRIGGER `as` BEFORE INSERT ON `result_answers` FOR EACH ROW BEGIN
		DECLARE qtype INT;
		DECLARE qpoints INT;
    SET qtype = (SELECT type FROM question where id = NEW.questionID);
		SET qpoints = (SELECT points from question WHERE id = NEW.questionID);
    IF(qtype = 1) THEN
			IF NEW.isTrue = (SELECT isTrue from question where id = NEW.questionID) THEN
			SET NEW.isCorrect = 1;
			SET NEW.points = qpoints;
			ELSE
			SET NEW.isCorrect = 0;
			SET NEW.points = 0;
			END IF;
		ELSEIF(qtype = 5) THEN
			IF NEW.textAnswer = '' THEN
			SET NEW.isCorrect = 0;
			SET NEW.points = 0;
			END IF;
		ELSEIF(qtype = 4) THEN
			IF (NEW.textAnswer = (SELECT matchAnswer from question_answers where id = NEW.answerID)) THEN
				SET NEW.isCorrect = 1;
				SET NEW.points = (SELECT points FROM question_answers where id = NEW.answerID);
			ELSE
				SET NEW.isCorrect = 0;
				SET NEW.points = 0;
			END IF;
    END IF;
    END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;

<?php

class result extends dbh{

    public function getAll(){
        if($_SESSION['mydata']->isAdmin){
          $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                               (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                  getResultGrade(r.id) AS FinalGrade,
                                  getResultMaxGrade(r.id) TestDegree
                                   FROM result r
                                   INNER JOIN test t
                                      ON t.id = r.testID
                                   INNER JOIN student s
                                      ON s.id = r.studentID
                                   group by t.id, r.id
                                   order by r.endTime DESC;");
        }else{
          $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                               (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                  getResultGrade(r.id) AS FinalGrade,
                                  getResultMaxGrade(r.id) TestDegree
                                   FROM result r
                                   INNER JOIN test t
                                      ON t.id = r.testID
                                   INNER JOIN student s
                                      ON s.id = r.studentID
                                   WHERE t.instructorID = :aid and !r.isTemp and getResultMaxGrade(r.id) > 0
                                   group by t.id, r.id
                                   order by r.endTime DESC;");
            $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        }
          $stmt->execute();
          $result=$stmt->fetchAll(PDO::FETCH_OBJ);
          return $result;
    }
    public function getUnsubmitted(){
          $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                               (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                  getResultGrade(r.id) AS FinalGrade,
                                  getResultMaxGrade(r.id) TestDegree
                                   FROM result r
                                   INNER JOIN test t
                                      ON t.id = r.testID
                                   INNER JOIN student s
                                      ON s.id = r.studentID
                                   WHERE t.instructorID = :aid and !r.isTemp and getResultMaxGrade(r.id) = 0
                                   group by t.id, r.id
                                   order by r.endTime DESC;");
          $stmt->bindparam(":aid",$_SESSION['mydata']->id);
          $stmt->execute();
          $result=$stmt->fetchAll(PDO::FETCH_OBJ);
          return $result;
    }
    public function getAllByLink($linkID){
        $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                             (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                getResultGrade(r.id) AS FinalGrade,
                                getResultMaxGrade(r.id) TestDegree
                                 FROM result r
                                 INNER JOIN test t
                                    ON t.id = r.testID
                                 INNER JOIN student s
                                    ON s.id = r.studentID
                                 WHERE t.instructorID = :aid
                                 and !r.isTemp
                                 and t.id = (select testID from test_invitations ti where ti.id = :linkID)
                                 and r.settingID = (select settingID from test_invitations ti where ti.id = :linkID)
                                 and getResultMaxGrade(r.id) > 0
                                 group by t.id, r.id
                                 order by r.endTime DESC;");
          $stmt->bindparam(":linkID",$linkID);
          $stmt->bindparam(":aid",$_SESSION['mydata']->id);
          $stmt->execute();
          $result=$stmt->fetchAll(PDO::FETCH_OBJ);
          return $result;
    }
    public function getTestResults($testID){
        $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                             (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                getResultGrade(r.id) AS FinalGrade,
                                getResultMaxGrade(r.id) TestDegree
                                 FROM result r
                                 INNER JOIN test t
                                    ON t.id = r.testID
                                 INNER JOIN student s
                                    ON s.id = r.studentID
                                 WHERE t.instructorID = :aid and !r.isTemp and t.id = :testID and getResultMaxGrade(r.id) > 0
                                 group by t.id, r.id
                                 order by r.endTime DESC;");
          $stmt->bindparam(":testID",$testID);
          $stmt->bindparam(":aid",$_SESSION['mydata']->id);
          $stmt->execute();
          $result=$stmt->fetchAll(PDO::FETCH_OBJ);
          return $result;
    }
    public function getGroupResults($groupID){
        $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                             (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                getResultGrade(r.id) AS FinalGrade,
                                getResultMaxGrade(r.id) TestDegree
                                 FROM result r
                                 INNER JOIN test t
                                    ON t.id = r.testID
                                 INNER JOIN student s
                                    ON s.id = r.studentID
                                 WHERE t.instructorID = :aid and !r.isTemp and r.groupID = :groupID
                                 group by t.id, r.id
                                 order by r.endTime DESC;");
          $stmt->bindparam(":groupID",$groupID);
          $stmt->bindparam(":aid",$_SESSION['mydata']->id);
          $stmt->execute();
          $result=$stmt->fetchAll(PDO::FETCH_OBJ);
          return $result;
    }
    public function getByID($rid){
        $stmt = $this->connect()->prepare("SELECT r.id,t.name AS testName,t.id as testID,r.startTime,r.endTime,ts.endTime as testEnd,
                       TIMESTAMPDIFF(MINUTE,r.startTime,r.endTime) as resultDuration,ipaddr,hostname,
                       ts.passPercent,ts.duration AS testDuration,
                       s.name AS studentName,s.id as studentID, s.email as studentMail,s.phone as studentPhone,
                       (select count(DISTINCT (questionID)) from result_answers where resultID = r.id) AS Questions,
                       Result_CorrectQuestions(r.id) AS RightQuestions,
                       Result_WrongQuestions(r.id) AS WrongQuestions,
                       getResultGrade(r.id) AS FinalGrade,
                       getResultMaxGrade(r.id) TestDegree
                       FROM result r
                        INNER JOIN test t
                           ON t.id = r.testID
												 INNER JOIN student s
												 on s.id = r.studentID
                        LEFT JOIN test_settings ts
                           ON ts.id = r.settingID
                           WHERE r.id = :rid
                           group by t.id, r.id");
        $stmt->bindparam(":rid",$rid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result[0];
    }
    public function getResultAnswers($rid){
        $stmt = $this->connect()->prepare("SELECT q.id,q.question,type,q.isTrue,
             CASE type WHEN 4 THEN TRIM(round(SUM(ra.points),1))+0
             ELSE TRIM(round(MIN(ra.points),1))+0 END AS points
             from result_answers ra
             LEFT JOIN question q
             on q.id = ra.questionID
             where resultID = :rid
             GROUP BY q.id;");
        $stmt->bindparam(":rid",$rid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getCorrectAnswers($qid){
        $stmt = $this->connect()->prepare("SELECT * from question_answers WHERE questionID = :qid and isCorrect ORDER BY id;");
        $stmt->bindparam(":qid",$qid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getGivenAnswers($rid,$qid){
        $stmt = $this->connect()->prepare("SELECT answer,textAnswer,ra.isCorrect,TRIM(ra.points)+0 points,isTrue from result_answers ra
         LEFT JOIN question_answers qa
         on qa.id = ra.answerID
         WHERE ra.questionID = :qid and ra.resultID = :rid ORDER BY qa.id;");
        $stmt->bindparam(":qid",$qid);
        $stmt->bindparam(":rid",$rid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function questionsNeedsReview(){
        $stmt = $this->connect()->prepare("SELECT ra.id,s.name StudentName,s.id StudentID,q.question,ra.textAnswer,q.points from result_answers ra
              INNER JOIN student s
              on s.id = (select studentID FROM result where id= ra.resultID)
              INNER JOIN question q
              on q.id = ra.questionID
              where ra.points < 0  and q.instructorID = :instructorID");
        $stmt->bindparam(":instructorID",$_SESSION['mydata']->id);
        $stmt->execute();

        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getQuestionReport($qid){
        $stmt = $this->connect()->prepare("CALL `Question_getQuestionReport`(:aid);");
        $stmt->execute(array(":aid"=>$qid));
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result[0];
    }

    public function acceptAnswer($id,$accept = 0,$points = 0){
      $stmt=$this->connect()->prepare("UPDATE result_answers SET
                                        isCorrect = :accept,
                                        points = :points
                                        WHERE id=:id");
      $stmt->bindparam(":id",$id);-
      $stmt->bindparam(":accept",$accept);
      $stmt->bindparam(":points",$points);
      $stmt->execute();
    }





}

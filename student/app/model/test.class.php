<?php
require_once 'dbh.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/student/app/controller/function.php';

class test extends dbh{

  function getMyTests(){
      $stmt = $this->connect()->prepare("SELECT t.id,t.name,g.name groupName,i.name instructor,ts.endTime,ts.id settingID,
			CASE WHEN (convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN ts.startTime AND ts.endTime) THEN 'Available'
			WHEN convert_tz(now(),@@session.time_zone,'+02:00') < ts.startTime THEN 'Not Started Yet'
			when convert_tz(now(),@@session.time_zone,'+02:00') > ts.endTime THEN 'Missed'
			ELSE 'Not Available'
			END AS status
			from groups g
      inner join groups_has_students gs
      on gs.studentID = :studID and g.id = gs.groupID
      inner join test t
      on t.id = g.assignedTest
      inner join test_settings ts
      on ts.id = g.settingID
      inner join instructor i
      on i.id = t.instructorID
			WHERE t.id NOT IN (SELECT testID from result where studentID = gs.studentID);");
      $stmt->bindparam(":studID",$_SESSION['student']->id);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }

  function getTest($tid){
      $stmt = $this->connect()->prepare("SELECT
      t.id,t.name,c.name course,
      i.name instructor,getQuestionsInTest(t.id) questions,
      ts.startTime,ts.duration,ts.passPercent,ts.endTime,
      ts.prevQuestion,ts.id settingID,ts.random,g.id groupID
       from groups g
      inner join groups_has_students gs
      on gs.studentID = :studID and g.id = gs.groupID
      inner join test t
      on t.id = g.assignedTest
      inner join test_settings ts
      on ts.id = g.settingID
      inner join course c
      on c.id = t.courseID
      inner join instructor i
      on i.id = t.instructorID
      where (convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN ts.startTime AND ts.endTime)
      AND t.id NOT IN (SELECT testID from result where studentID = gs.studentID) AND t.id = :tID;");
      $stmt->bindparam(":studID",$_SESSION['student']->id);
      $stmt->bindparam(":tID",$tid);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      return $result[0];
  }
  function getTestByCode($code){
      $stmt = $this->connect()->prepare("SELECT t.id,t.name,c.name course,
        i.name instructor,ts.endTime,ti.settingID,ts.prevQuestion,
        ts.passPercent,ts.duration,ts.random,ts.startTime,ts.sendToStudent,ts.releaseResult,null groupID,
        getQuestionsInTest(t.id) questions from test_invitations ti
              inner join test t
              on t.id = ti.testID
              LEFT join test_settings ts
              on ts.id = ti.settingID
              inner join course c
              on c.id = t.courseID
              inner join instructor i
              on i.id = t.instructorID
        			where ti.id = AES_DECRYPT(UNHEX(:code), 'O6U');");
      $stmt->bindparam(":code",$code);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      return $result[0];
  }

  public function checkTestTaken(){
      $stmt = $this->connect()->prepare("SELECT id FROM result where testID = :testID AND studentID = :studentID");
      $stmt->bindparam(":testID",$_SESSION['CurrentTest']->id);
      $stmt->bindparam(":studentID",$_SESSION['student']->id);
      $stmt->execute();
      $result=$stmt->rowCount();
      if($result > 0){
              return true;
      }else{
              return false;
      }
    }

    function getActiveTest(){
        $stmt = $this->connect()->prepare("SELECT t.id,t.name,ts.passPercent,ts.endTime,ts.duration,ts.viewAnswers,ts.prevQuestion,
    		getQuestionsInTest(t.id) as questions,
        (CASE WHEN ((ts.duration * 60) - TIMESTAMPDIFF(SECOND,r.startTime,convert_tz(now(),@@session.time_zone,'+02:00'))) < TIMESTAMPDIFF(SECOND,convert_tz(now(),@@session.time_zone,'+02:00'),ts.endTime) THEN
        ((ts.duration * 60) - TIMESTAMPDIFF(SECOND,r.startTime,convert_tz(now(),@@session.time_zone,'+02:00')))
        ELSE
        TIMESTAMPDIFF(SECOND,convert_tz(now(),@@session.time_zone,'+02:00'),ts.endTime)
        END) remainingTime
        FROM test t
    		 INNER JOIN result r
         ON r.testID = t.id AND r.isTemp AND r.studentID = :studID
				 INNER JOIN test_settings ts
         ON ts.id = r.settingID
         WHERE  r.isTemp");
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        if(!empty($result)){
        if ($result[0]->remainingTime < 0){
          $this->FinishTest();
          return false;
        }else{
          return $result[0];
        }
      }else{
        return false;
      }
    }
    function checkActiveTest(){
        $stmt = $this->connect()->prepare("select testID as id from result where studentID = :studID and isTemp
        and ((convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN startTime AND endTime) OR ISNULL(endTime))");
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        if(isset($result[0]->id))
          return $result[0]->id;
        else
          return 0;
    }


function getMyQuestions(){
    $stmt = $this->connect()->prepare("SELECT q.id, question, type, isTrue, instructorID, courseID, deleted, points,difficulty
                  FROM tempquestions temp
                  JOIN question q
                  ON q.id = temp.questionID
                  WHERE resultID = (SELECT MAX(id) FROM result WHERE studentID =:studID AND isTemp) order by temp.rand;");
     $stmt->bindparam(":studID",$_SESSION['student']->id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $r = array();
    $finalArray = array();
    foreach($result as $item){
      $r['id'] = $item['id'];
      $r['question'] = $item['question'];
      $r['type'] = $item['type'];
      $r['difficulty'] = $item['difficulty'];
      $r['isTrue'] = $item['isTrue'];
      $r['instructorID'] = $item['instructorID'];
      $r['courseID'] = $item['courseID'];
      $r['deleted'] = $item['deleted'];
      $r['points'] = $item['points'];
      $r['answers'] = $this->getQuestionAnswers($r['id']);
      $r['matches'] = $this->getQuestionMatches($r['id']);
      array_push($finalArray,$r);
    }
    return json_encode($finalArray);
}
function getQuestionAnswers($qid){
    $stmt = $this->connect()->prepare("SELECT id,questionID,answer
                                 FROM question_answers  where questionID = :id ORDER BY RAND();");
    $stmt->bindparam(":id",$qid);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function getQuestionMatches($qid){
    $stmt = $this->connect()->prepare("SELECT matchAnswer
                                 FROM question_answers  where questionID = :id ORDER BY RAND();");
    $stmt->bindparam(":id",$qid);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

  function getLastResult(){
      $stmt = $this->connect()->prepare("SELECT MAX(id) as id FROM result where studentID = :StudentID");
      $stmt->bindparam(":StudentID",$_SESSION['student']->id);
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $results[0];
  }
  public function submitAnswers($q)
   {
    try
      {
        $id = $this->getLastResult()->id;
        $sql = 'INSERT INTO result_answers(resultID, questionID, answerID, isTrue,textAnswer) VALUES ';
        foreach($q as $answer){
          $sql .= '('. $id .',';
          $sql .= $answer['questionID'] . ',';
          $sql .= (($answer['answerID'])? $answer['answerID']:'null') . ',';
          $sql .= (($answer['isTrue'])? 1:0) . ',';
          $sql .= (($answer['textAnswer'])? '"' . $answer['textAnswer'] . '"':'null') . '),';
        }
        $query = rtrim($sql,",");
        $query .= '; INSERT INTO result_answers(resultID, questionID, answerID, isTrue,textAnswer) VALUES
        SELECT resultID,questionID,null,null,null from tempquestions where resultID = ' . $id . '
        and questionID NOT IN (SELECT questionID FROM result_answers where resultID = '. $id .')';
         $stmt = $this->connect()->prepare($query);
         $stmt->execute();
         return 'success';
      }
    catch(PDOException $e)
      {
         return $e->getMessage();
      }
   }
   public function reviewAnswers(){
       $stmt = $this->connect()->prepare("UPDATE result_answers
       set isCorrect = checkAnswer(resultID,questionID),
       points = CASE isCorrect WHEN 1 THEN (select points from question where id = questionID)
			 ELSE 0 END
       where resultID = (select max(id) from result where studentID = :studentID)
			 AND (select type from question where id = questionID) IN (0,2,3);");
       $stmt->bindparam(":studentID",$_SESSION['student']->id);
       $stmt->execute();
       return 1;
   }
    function insertRandomRule($tid,$course,$difficulty,$limit){
        $stmt = $this->connect()->prepare("CALL InsertRandomRules(:studID,:tID,:course,:diff,:lim)");
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->bindparam(":tID",$tid);
        $stmt->bindparam(":course",$course);
        $stmt->bindparam(":diff",$difficulty);
        $stmt->bindparam(":lim",$limit);
        $stmt->execute();
    }
    function InitiateRandoms(){
        $stmt = $this->connect()->prepare("SELECT * FROM test_random_questions where testID = :tid");
        $stmt->bindparam(":tid",$_SESSION['CurrentTest']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($result as $item){
          $this->insertRandomRule($item->testID,$item->courseID,$item->difficulty,$item->questionsCount);
        }
    }
    function InitiateFixed(){
        $stmt = $this->connect()->prepare("Delete from tempquestions where resultID IN (SELECT MAX(id) FROM result WHERE studentID = :studID and isTemp);
              INSERT INTO tempquestions(resultID, questionID)
              SELECT (SELECT MAX(id) FROM result WHERE studentID = :studID and isTemp) AS resultID, questionID
              FROM tests_has_questions where testID = :tid;");
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->bindparam(":tid",$_SESSION['CurrentTest']->id);
        $stmt->execute();
    }
    function InitiateFixed_Random(){
        $stmt = $this->connect()->prepare("Delete from tempquestions where resultID IN (SELECT MAX(id) FROM result WHERE studentID = :studID);
        INSERT INTO tempquestions(resultID, questionID,rand)
        SELECT (SELECT MAX(id) FROM result WHERE studentID = :studID and isTemp) AS resultID, questionID,(select floor(0+ RAND() * 10000))
        FROM tests_has_questions where testID = :tid");
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->bindparam(":tid",$_SESSION['CurrentTest']->id);
        $stmt->execute();
    }
    function InitiateTest(){
        $group = (($_SESSION['CurrentTest']->groupID) ? $_SESSION['CurrentTest']->groupID : null);
        $stmt = $this->connect()->prepare("INSERT INTO result(testID,studentID,groupID,settingID,startTime)
                                            VALUES (:tid,:studID,:groupID,:settingID,convert_tz(now(),@@session.time_zone,'+02:00'));");
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->bindparam(":tid",$_SESSION['CurrentTest']->id);
        $stmt->bindparam(":settingID",$_SESSION['CurrentTest']->settingID);
        $stmt->bindparam(":groupID",$group);
        $stmt->execute();
        return 1;
    }

    public function FinishTest()
     {
      try
        {
           $ip_address = getClientIP();
           $hostname = gethostbyaddr($ip_address);
           $stmt = $this->connect()->prepare("UPDATE result
              SET isTemp = 0,
               endTime = convert_tz(now(),@@session.time_zone,'+02:00'),
               hostname = :hostname,
               ipaddr = :ipaddr
             where studentID = :studID and isTemp ORDER BY `id` DESC LIMIT 1;
             DELETE FROM tempquestions where resultID = (SELECT MAX(id) from result where studentID = :studID);");
           $stmt->bindparam(":studID",$_SESSION['student']->id);
           $stmt->bindparam(":hostname",$hostname);
           $stmt->bindparam(":ipaddr",$ip_address);
           $stmt->execute();
           return true;
        }
      catch(PDOException $e)
        {
           return $e->getMessage();
        }
     }
     public function getMyResults(){
         $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,r.studentID,r.endTime,ts.releaseResult,
                              (select name from instructor where id = t.instructorID) AS Instructor,
                              getResultGrade(r.id) AS FinalGrade,
                              getResultMaxGrade(r.id) TestDegree
                              FROM result r
                              INNER JOIN test t
                              ON t.id = r.testID
                              INNER JOIN test_settings ts
                              ON ts.id = r.settingID
                              WHERE r.studentID = :sid
                              group by t.id, r.id
                              order by r.endTime DESC");
         $stmt->bindparam(":sid",$_SESSION['student']->id);
         $stmt->execute();
         $result=$stmt->fetchAll(PDO::FETCH_OBJ);
         return $result;
     }
     public function getMyResult($rid){
       $stmt = $this->connect()->prepare("SELECT r.id,t.name AS testName,t.id as testID,r.startTime,r.endTime,ts.endTime as testEnd,releaseResult,
                      TIMESTAMPDIFF(MINUTE,r.startTime,r.endTime) as resultDuration,
                      ts.passPercent,ts.duration AS testDuration,
                      (select name from instructor where id = t.instructorID) AS Instructor,
                      (select count(DISTINCT (questionID)) from result_answers where resultID = r.id) AS Questions,
                      Result_CorrectQuestions(r.id) AS RightQuestions,
                      Result_WrongQuestions(r.id) AS WrongQuestions,
                      getResultGrade(r.id) AS FinalGrade,
                      getResultMaxGrade(r.id) TestDegree
                      from result r
                      inner join test_settings ts
                      on r.settingID = ts.id
                      inner join test t
                      on r.testID = t.id
                      WHERE r.id = :rid and r.studentID = :sid");
       $stmt->bindparam(":sid",$_SESSION['student']->id);
       $stmt->bindparam(":rid",$rid);
       $stmt->execute();
       $result=$stmt->fetchAll(PDO::FETCH_OBJ);
       return $result[0];
     }
     public function getFinishedResult(){
       $stmt = $this->connect()->prepare("SELECT r.id,t.name AS testName,t.id as testID,r.startTime,r.endTime,ts.endTime as testEnd,releaseResult,
                      TIMESTAMPDIFF(MINUTE,r.startTime,r.endTime) as resultDuration,
                      ts.passPercent,ts.duration AS testDuration,
                      (select name from instructor where id = t.instructorID) AS Instructor,
                      (select count(DISTINCT (questionID)) from result_answers where resultID = r.id) AS Questions,
                      Result_CorrectQuestions(r.id) AS RightQuestions,
                      Result_WrongQuestions(r.id) AS WrongQuestions,
                      getResultGrade(r.id) AS FinalGrade,
                      getResultMaxGrade(r.id) TestDegree
                      from result r
                      inner join test_settings ts
                      on r.settingID = ts.id
                      inner join test t
                      on r.testID = t.id
                      WHERE r.id = (SELECT max(id) from result where studentID = :sid) and r.studentID = :sid");
       $stmt->bindparam(":sid",$_SESSION['student']->id);
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
     public function canViewResults($rid){
       $stmt = $this->connect()->prepare("SELECT (select viewAnswers from test_settings where id = r.settingID) AS viewAnswers from result r where id = :id;");
       $stmt->bindparam(":id",$rid);
       $stmt->execute();
       $result=$stmt->fetchAll(PDO::FETCH_OBJ);
       return $result[0]->viewAnswers;
       }
   public function checkCode($code){
       $stmt = $this->connect()->prepare("SELECT id from test_invitations where id =  AES_DECRYPT(UNHEX(:code), 'O6U') and used < useLimit");
       $stmt->bindparam(":code",$code);
       $stmt->execute();
       $result=$stmt->rowCount();
       if($result > 0){
               return true;
       }else{
               return false;
       }
     }

   public function checktestStatus($code){
       $stmt = $this->connect()->prepare("SELECT (CASE WHEN (convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN ts.startTime AND ts.endTime) THEN
            		1
            	ELSE
            		0
            END) AS status
            from test_invitations ti
            inner join test_settings ts
            on ts.id = ti.settingID where ti.id = AES_DECRYPT(UNHEX(:code), 'O6U')");
       $stmt->bindparam(":code",$code);
       $stmt->execute();
       $result=$stmt->fetchAll(PDO::FETCH_OBJ);
       if(!empty($result))
          return $result[0]->status;
       else
          return 0;
     }


     public function testAlreadyTaken($code){
         $stmt = $this->connect()->prepare("SELECT * from result where testID = (SELECT testID from test_invitations where id = AES_DECRYPT(UNHEX(:code), 'O6U')) AND StudentID = :studID");
         $stmt->bindparam(":code",$code);
         $stmt->bindparam(":studID",$_SESSION['student']->id);
         $stmt->execute();
         $result=$stmt->rowCount();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
       }

       public function sendResultMails(){
           $stmt = $this->connect()->prepare("INSERT into mails(resultID,sends_at,type)
            select r.id,convert_tz(now(),@@session.time_zone,'+02:00'),2 from result r
            inner join test_settings ts
            on r.settingID = ts.id
            where studentID = :studID
            and sendToStudent
            and releaseResult
            order by id desc Limit 1;
            insert into mails(resultID,sends_at,type)
            select r.id,convert_tz(now(),@@session.time_zone,'+02:00'),3 from result r
            inner join test_settings ts
            on r.settingID = ts.id
            where studentID = :studID
            and sendToInstructor
            order by id desc Limit 1;");
           $stmt->bindparam(":studID",$_SESSION['student']->id);
           $stmt->execute();
       }
}

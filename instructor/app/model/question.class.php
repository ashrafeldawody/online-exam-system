<?php

class question extends dbh{
    public function getAll($courseID = null){
        if($courseID == null){
        $stmt = $this->connect()->prepare("SELECT id, question, type,isTrue,CASE q.type when 4 then (select sum(qa.points) from question_answers qa where qa.questionID = q.id)
        ELSE q.points END as points,difficulty,
                      (Select name from course where id = courseID) as course
                      FROM question q WHERE instructorID = :aid and deleted = 0");
        }else{
          $stmt = $this->connect()->prepare("SELECT id, question, type,isTrue,CASE q.type when 4 then (select sum(qa.points) from question_answers qa where qa.questionID = q.id)
          ELSE q.points END as points,difficulty,
                        (Select name from course where id = courseID) as course
                        FROM question q WHERE instructorID = :aid and !deleted and courseID = :cID");
            $stmt->bindparam(":cID",$courseID);
        }
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);

        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getDeleted(){
        $stmt = $this->connect()->prepare("SELECT id, question, type,isTrue,
        CASE q.type when 4 then (select sum(qa.points) from question_answers qa where qa.questionID = q.id)
        ELSE q.points END as points,difficulty,
        (Select name from course where id = courseID) as course,
        (Select count(*) from result_answers where questionID = q.id) as used
        FROM question q WHERE instructorID = :aid and deleted");
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getByCourse($courseID){
        $stmt = $this->connect()->prepare("SELECT id, question, type,isTrue,CASE q.type when 4 then (select sum(qa.points) from question_answers qa where qa.questionID = q.id)
        ELSE q.points END as points,difficulty,
        (Select name from course where id = courseID) as course
        FROM question q WHERE instructorID = :aid and !deleted AND courseID = :cID");
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        $stmt->bindparam(":cID",$courseID);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getByID($id){
        $stmt = $this->connect()->prepare("SELECT id, question, type,isTrue,CASE q.type when 4 then (select sum(qa.points) from question_answers qa where qa.questionID = q.id)
        ELSE q.points END as points,difficulty,
        (SELECT count(*) FROM result_answers where questionID = :qid) inResults,
        (Select name from course where id = courseID) as course,courseID
        FROM question q WHERE instructorID = :aid and id = :qid");
        $stmt->bindparam(":qid",$id);
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result[0];
    }
    public function getLastQuestion(){
        $stmt = $this->connect()->prepare("SELECT * FROM question where instructorID = :aid ORDER BY id DESC LIMIT 0, 1;");
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result[0];
    }
    public function getQuestionReport($qid){
        $stmt = $this->connect()->prepare("SELECT q.id, q.question, q.type, q.isTrue,q.difficulty,
                  c.name as course,
                  COUNT(c.id) as inTests,
                  count(DISTINCT(ra.resultID)) as inResults,
                  (select count(DISTINCT resultID) from result_answers where questionID = q.id and isCorrect) as rightAnswers,
                  (select count(DISTINCT resultID) from result_answers where questionID = q.id and !isCorrect) as wrongAnswers
                  FROM question q
                  INNER JOIN course c
                  ON c.id = q.courseID
                  INNER JOIN result_answers ra
                  ON ra.questionID = q.id
                  WHERE q.id = :qid;");
        $stmt->bindparam(":qid",$qid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result[0];
    }
    public function duplicateQuestion($qid){
        $stmt = $this->connect()->prepare("INSERT INTO question(question, type, points, difficulty, isTrue, instructorID, courseID, deleted)
        SELECT question, type, points, difficulty, isTrue, :instID, courseID, deleted from question where id = :qid;
        INSERT INTO question_answers(questionID, answer, matchAnswer, isCorrect, points)
        SELECT (SELECT MAX(id) FROM question where instructorID = :instID), answer, matchAnswer, isCorrect, points FROM question_answers where questionID = :qid;
        SELECT MAX(id) as id FROM question where instructorID = :instID;");
        $stmt->bindparam(":qid",$qid);
        $stmt->bindparam(":instID",$_SESSION['mydata']->id);
        $stmt->execute();
    }
    public function getQuestionAnswers($qid){
        $stmt = $this->connect()->prepare("SELECT * FROM question_answers where questionID = :id");
        $stmt->bindparam(":id",$qid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function setQuestionDelete($id){
      $stmt=$this->connect()->prepare("UPDATE question SET
                                        deleted = 1
                                        WHERE id=:id;
                                        DELETE FROM tests_has_questions where questionID = :id;");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
    }
    public function restoreQuestion($id){
      $stmt=$this->connect()->prepare("UPDATE question SET
                                        deleted = 0
                                        WHERE id=:id ");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
    }

    public function pDeleteQuestion($qid){
      $stmt=$this->connect()->prepare("DELETE FROM question WHERE id = :qid and instructorID = :aid ");
      $stmt->bindparam(":qid",$qid);
      $stmt->bindparam(":aid",$_SESSION['mydata']->id);
      $stmt->execute();
    }
    public function insertQuestion($question,$type,$course,$isTrue,$points,$difficulty)
     {
      try
        {
           $stmt = $this->connect()->prepare("INSERT INTO question(question, type, instructorID, courseID,isTrue,points,difficulty)
                    VALUES (:question,:type,:aid,:cid,:isTrue,:points,:difficulty);");
           $stmt->bindparam(":question",$question);
           $stmt->bindparam(":type",$type);
           $stmt->bindparam(":aid",$_SESSION['mydata']->id);
           $stmt->bindparam(":cid",$course);
           $stmt->bindparam(":isTrue",$isTrue);
           $stmt->bindparam(":points",$points);
           $stmt->bindparam(":difficulty",$difficulty);
           $stmt->execute();
           return true;
        }
      catch(PDOException $e)
        {
           echo $e->getMessage();
           return false;
        }
     }
     public function insertAnswersToLast($answer,$isCorrect,$matchAnswer = null,$points = 1)
     {
      try
        {
           $stmt = $this->connect()->prepare("INSERT INTO question_answers(questionID,answer,isCorrect,matchAnswer,points)
           VALUES ((select max(id) from question where instructorID = :aid),:answer,:isCorrect,:matchAnswer,:points);");
           $stmt->bindparam(":matchAnswer",$matchAnswer);
           $stmt->bindparam(":answer",$answer);
           $stmt->bindparam(":isCorrect",$isCorrect);
           $stmt->bindparam(":points",$points);
           $stmt->bindparam(":aid",$_SESSION['mydata']->id);
           $stmt->execute();
           return true;
        }
      catch(PDOException $e)
        {
           echo $e->getMessage();
           return false;
        }
     }
     public function insertAnswers($qid,$answer,$isCorrect,$matchAnswer = null,$points = 1)
     {
      try
        {
           $stmt = $this->connect()->prepare("INSERT INTO question_answers(questionID,answer,isCorrect,matchAnswer,points)
           VALUES (:qid,:answer,:isCorrect,:matchAnswer,:points);");
           $stmt->bindparam(":qid",$qid);
           $stmt->bindparam(":answer",$answer);
           $stmt->bindparam(":isCorrect",$isCorrect);
           $stmt->bindparam(":matchAnswer",$matchAnswer);
           $stmt->bindparam(":points",$points);
           $stmt->execute();
           return true;
        }
      catch(PDOException $e)
        {
           echo $e->getMessage();
           return false;
        }
     }
     public function updateQuestion($id,$question,$course,$points,$difficulty)
      {
       try
         {
            $stmt = $this->connect()->prepare("UPDATE question
              SET question = :question,
              courseID = :cid,
              points = :points,
              difficulty = :difficulty
              where id = :qid;");
            $stmt->bindparam(":question",$question);
            $stmt->bindparam(":points",$points);
            $stmt->bindparam(":difficulty",$difficulty);
            $stmt->bindparam(":cid",$course);
            $stmt->bindparam(":qid",$id);
            $stmt->execute();
            return true;
         }
       catch(PDOException $e)
         {
            echo $e->getMessage();
            return false;
         }
      }

       public function updateAnswer($ansID,$answer,$isCorrect,$matchAnswer,$points = 1)
        {
         try
           {
              $stmt = $this->connect()->prepare("UPDATE question_answers
                SET answer = :answer,
                isCorrect = :isCorrect,
                matchAnswer = :matchAnswer,
                points = :points
                where id = :id;");
              $stmt->bindparam(":answer",$answer);
              $stmt->bindparam(":isCorrect",$isCorrect);
              $stmt->bindparam(":matchAnswer",$matchAnswer);
              $stmt->bindparam(":points",$points);
              $stmt->bindparam(":id",$ansID);
              $stmt->execute();
              return true;
           }
         catch(PDOException $e)
           {
              echo $e->getMessage();
              return false;
           }
        }
        public function updateTF($qID,$isCorrect)
         {
          try
            {
               $stmt = $this->connect()->prepare("UPDATE question
                 SET isTrue = :isTrue
                 where id = :id;");
               $stmt->bindparam(":isTrue",$isCorrect);
               $stmt->bindparam(":id",$qID);
               $stmt->execute();
               return true;
            }
          catch(PDOException $e)
            {
               echo $e->getMessage();
               return false;
            }
         }
     public function deleteAnswer($id){
       $stmt=$this->connect()->prepare("DELETE FROM question_answers WHERE id = :id;");
       $stmt->bindparam(":id",$id);
       $stmt->execute();
     }
}

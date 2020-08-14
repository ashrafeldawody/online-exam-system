<?php

class report extends dbh{
  public function questionAnswersStats($questionID,$type){
    if($type == 0)
      $stmt = $this->connect()->prepare("SELECT (select answer from question_answers where id = ra.answerID) answer,
          count(answerID) as c,max(isCorrect) isCorrect
          from result_answers ra
          WHERE ra.questionID = :questionID group BY answerID order by c DESC");
    elseif($type == 1)
      $stmt = $this->connect()->prepare("select (CASE isTrue when 0 then 'False' ELSE 'True' END) answer,count(*) c,max(isCorrect) isCorrect from result_answers WHERE questionID = :questionID group BY isTrue order by c DESC");
    else
      $stmt = $this->connect()->prepare("select textAnswer answer,count(*) c,max(isCorrect) isCorrect from result_answers WHERE questionID = :questionID AND textAnswer IS NOT NULL group BY textAnswer order by c DESC");
    $stmt->bindparam(":questionID",$questionID);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getQuestionsInTest($testID){
      $stmt = $this->connect()->prepare("SELECT DISTINCT q.id,question,q.type,q.isTrue,CASE q.type when 4 then (select sum(qa.points) from question_answers qa where qa.questionID = q.id)
      ELSE q.points END as points from result_answers ra
        JOIN result r
        ON ra.resultID = r.id
        JOIN question q
        ON ra.questionID = q.id
        where testID = :testID");
      $stmt->bindparam(":testID",$testID);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
  }
  public function getQuestionReport($qID){
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
          WHERE q.id = :qID;");
      $stmt->bindparam(":qID",$qID);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result[0];
  }
}

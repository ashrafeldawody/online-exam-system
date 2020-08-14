<?php

class mail extends dbh{

  public function getUnsentMails(){
      $stmt = $this->connect()->query("SELECT * FROM mails where !sent");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function mailSent($id){
      $stmt = $this->connect()->prepare("DELETE FROM mails where id = :id");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
  }
  public function getStudentToken($id){
      $stmt = $this->connect()->prepare("SELECT name,email,password_token from student
        where id = :id and token_expire > NOW()");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
      $result = $stmt->fetch();
      if(!empty($result))
        return $result;
      else {
        return false;
      }
  }
  public function getInstructorToken($id){
      $stmt = $this->connect()->prepare("SELECT name,email,password_token from instructor
        where id = :id and token_expire > NOW()");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
      $result = $stmt->fetch();
      if(!empty($result))
        return $result;
      else {
        return false;
      }
  }
  public function getResult($rid){
      $stmt = $this->connect()->prepare("SELECT r.id,t.name AS testName,
                    s.name AS studentName,s.id as studentID, s.email as studentMail,
                    getResultGrade(r.id) AS FinalGrade,
                    getTestGrade(r.id) TestDegree,
                    i.name instructorName, i.email instructorMail
                    FROM result r
                    INNER JOIN test t
                    ON t.id = r.testID
                    INNER JOIN student s
                    on s.id = r.studentID
                    INNER JOIN instructor i
                    on i.id = t.instructorID
                    WHERE r.id = :rid
                    group by t.id, r.id");
      $stmt->bindparam(":rid",$rid);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      return $result[0];
  }


}

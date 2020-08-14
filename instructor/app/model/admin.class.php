<?php

class admin extends dbh{

  public function getAllInstructors(){
      $stmt = $this->connect()->query("SELECT * FROM instructor where !isAdmin");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getAllStudents(){
      $stmt = $this->connect()->query("SELECT * FROM student");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getUnregistered(){
      $stmt = $this->connect()->query("SELECT * FROM student WHERE password is null");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getInvitations(){
      $stmt = $this->connect()->query("SELECT * FROM instructor_invitations");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function generateInvitations($count){
      $stmt = $this->connect()->prepare("CALL generateInstructorInvites(:count);");
      $stmt->bindparam(":count",$count);
      $stmt->execute();
  }
  public function deleteInvitations(){
      $stmt = $this->connect()->prepare("DELETE FROM instructor_invitations");
      $stmt->execute();
  }
  public function getStudentResults($studentID){
        $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                             (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                getResultGrade(r.id) AS FinalGrade,
                                getResultMaxGrade(r.id) TestDegree
                                 FROM result r
                                 INNER JOIN test t
                                    ON t.id = r.testID
                                 INNER JOIN student s
                                    ON s.id = r.studentID
                                 WHERE r.studentID = :studentID and !r.isTemp
                                 group by t.id, r.id
                                 order by r.endTime DESC;");
        $stmt->bindparam(":studentID",$studentID);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
  }
  public function suspendStudent($studentID){
      $stmt = $this->connect()->prepare("UPDATE student SET suspended = 1,sessionID = NULL where id = :studentID");
      $stmt->bindparam(":studentID",$studentID);
      $stmt->execute();
      return true;
  }
  public function activateStudent($studentID){
      $stmt = $this->connect()->prepare("UPDATE student SET suspended = 0 where id = :studentID");
      $stmt->bindparam(":studentID",$studentID);
      $stmt->execute();
      return true;
  }
  public function suspendInstructor($instructorID){
      $stmt = $this->connect()->prepare("UPDATE instructor SET suspended = 1 where id = :instructorID");
      $stmt->bindparam(":instructorID",$instructorID);
      $stmt->execute();
      return true;
  }
  public function activateInstructor($instructorID){
      $stmt = $this->connect()->prepare("UPDATE instructor SET suspended = 0 where id = :instructorID");
      $stmt->bindparam(":instructorID",$instructorID);
      $stmt->execute();
      return true;
  }
  public function importStudents($values)
  {
      try {
          $sql = "INSERT IGNORE student(id,name) VALUES " . $values;
          $stmt = $this->connect()->prepare($sql);
          $stmt->execute();
          return true;
      } catch (PDOException $e) {
          echo $e->getMessage();
          return false;
      }
  }
  public function addStudent($id,$name)
  {
      try {
          $sql = "INSERT INTO student(id,name) VALUES(:id,:name)";
          $stmt = $this->connect()->prepare($sql);
          $stmt->bindparam(":id",$id);
          $stmt->bindparam(":name",$name);
          $stmt->execute();
          return true;
      } catch (PDOException $e) {
          echo $e->getMessage();
          return false;
      }
  }
  public function getUnsentMails(){
      $stmt = $this->connect()->query("SELECT * FROM mails where !sent");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
}

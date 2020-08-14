<?php

class student extends dbh{

  public function getMyStudents(){
      $stmt = $this->connect()->prepare("SELECT DISTINCT s.id,s.`name`,s.email,s.phone,s.suspended from result r
                    inner join student s
                    on r.studentID = s.id
                    inner join test t
                    on t.id = r.testID AND t.instructorID = :instID");
      $stmt->bindparam(":instID",$_SESSION['mydata']->id);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getAllIDs(){
      $stmt = $this->connect()->prepare("SELECT id from student");
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getUnregistered(){
      $stmt = $this->connect()->prepare("SELECT * from student where password IS NULL");
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getStudentResults($studentID){
        $stmt = $this->connect()->prepare("SELECT r.id,r.testID,t.name AS testName,s.name AS studentName,r.studentID,r.startTime,r.endTime,
                             (select name from student where id = r.studentID) AS student,ipaddr,hostname,
                                getResultGrade(r.id) AS FinalGrade,
                                getResultMaxGrade(r.id) TestDegree
                                 FROM result r
                                 INNER JOIN test t
                                    ON t.id = r.testID and t.instructorID = :instID
                                 INNER JOIN student s
                                    ON s.id = r.studentID
                                 WHERE r.studentID = :studentID and !r.isTemp
                                 group by t.id, r.id
                                 order by r.endTime DESC;");
        $stmt->bindparam(":instID",$_SESSION['mydata']->id);
        $stmt->bindparam(":studentID",$studentID);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
  }
  public function addStudents($students)
  {
      try {
          $sql = 'INSERT IGNORE INTO student(id) VALUES';
          foreach($students as $studentID)
          $sql .= ' ('. $studentID . '),';
          $sql = rtrim($sql,',');
          $stmt = $this->connect()->prepare($sql);
          $stmt->execute();
          return true;
      } catch (PDOException $e) {
          echo $e->getMessage();
          return false;
      }
  }
  public function deleteStudent($id){
      $stmt = $this->connect()->prepare("DELETE FROM student where id = :id");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
  }

}

<?php
class group extends dbh{
    public function getAll(){
        $stmt = $this->connect()->prepare("SELECT gp.id, name,
          (select count(*) from groups_has_students sg where sg.groupID = gp.id) as members,
          (select name from test where id = gp.assignedTest) as assignedTest,gp.assignedTest testID,
					(CASE WHEN (convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN ts.startTime AND ts.endTime) THEN 1 ELSE 0 END) as isActive,
          ts.startTime,ts.endTime,ts.duration,ts.viewAnswers,gp.instructorID as instructor
					FROM groups gp
					LEFT JOIN test_settings ts
					on ts.id = gp.settingID
          where gp.instructorID = :instID");
        $stmt->bindparam(":instID",$_SESSION['mydata']->id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;

    }
    public function getByID($id){
      $stmt = $this->connect()->prepare("SELECT gp.id, name,
      (select count(*) from groups_has_students sg where sg.groupID = gp.id) as members,
      (select name from test where id = gp.assignedTest) as assignedTest,gp.assignedTest testID,
      (CASE WHEN (convert_tz(now(),@@session.time_zone,'+02:00') BETWEEN ts.startTime AND ts.endTime) THEN
          1 ELSE 0 END) as isActive,
      ts.startTime,ts.endTime,ts.duration,ts.random,ts.sendToStudent,ts.releaseResult,ts.passPercent,ts.sendToInstructor,ts.viewAnswers,gp.instructorID as instructor
      FROM groups gp
      LEFT JOIN test_settings ts
      on ts.id = gp.settingID
      where gp.instructorID = :instID and gp.id = :gID");
            $stmt->bindparam(":gID",$id);
            $stmt->bindparam(":instID",$_SESSION['mydata']->id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result[0];
    }
    public function getMembers($id){
        $stmt = $this->connect()->prepare("select id,name,email,phone,(CASE WHEN s.password is null || s.password = '' THEN 0 ELSE 1 END) registered,joinDate from groups_has_students
        inner join student s
        on s.id = studentID where groupID = :gid");
        $stmt->bindparam(":gid",$id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getInvitations($groupID){
        $stmt = $this->connect()->prepare("SELECT (SELECT name from groups where id = :groupID) as name,groupID,code from group_invitations where groupID = :groupID");
        $stmt->bindparam(":groupID",$groupID);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function generateInvitations($groupID,$count,$prefix){
        $stmt = $this->connect()->prepare("SELECT generateGroupInvites(:groupID,:count,:pf);");
        $stmt->bindparam(":groupID",$groupID);
        $stmt->bindparam(":count",$count);
        $stmt->bindparam(":pf",$prefix);
        $stmt->execute();
    }
    public function deleteInvitations($groupID){
        $stmt = $this->connect()->prepare("DELETE FROM group_invitations where groupID = :groupID");
        $stmt->bindparam(":groupID",$groupID);
        $stmt->execute();
    }
    public function deleteOneInvite($code){
        $stmt = $this->connect()->prepare("DELETE FROM group_invitations where code = :code");
        $stmt->bindparam(":code",$code);
        $stmt->execute();
    }
    public function delete($id){
        $stmt = $this->connect()->prepare("DELETE FROM groups WHERE id = :gID and instructorID = :instID");
        $stmt->bindparam(":gID",$id);
        $stmt->bindparam(":instID",$_SESSION['mydata']->id);
        $stmt->execute();
    }
    public function insert($name)
     {
      try
        {
           $stmt = $this->connect()->prepare("INSERT INTO groups(name,instructorID)
                                                        VALUES(:name,:instructorID)");
           $stmt->bindparam(":name",$name);
           $stmt->bindparam(":instructorID",$_SESSION['mydata']->id);
           $stmt->execute();
           return true;
        }
      catch(PDOException $e)
        {
           echo $e->getMessage();
           return false;
        }
     }
     public function update($id,$name)
      {
       try
         {
            $stmt = $this->connect()->prepare("UPDATE groups SET name = :name where id = :id and instructorID = :aid;");
            $stmt->bindparam(":name",$name);
            $stmt->bindparam(":id",$id);
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
      public function addMembers($groupID,$students)
      {
          try {
              $sql = 'INSERT IGNORE INTO groups_has_students(groupID,studentID,joinDate) VALUES';
              foreach($students as $studentID)
              $sql .= ' (' . $groupID . ','. $studentID . ',convert_tz(now(),@@session.time_zone,"+02:00")),';
              $sql = rtrim($sql,',');
              $stmt = $this->connect()->prepare($sql);
              $stmt->execute();
              return true;
          } catch (PDOException $e) {
              echo $e->getMessage();
              return false;
          }
      }
    public function removeMember($groupID,$studentID){
          $stmt = $this->connect()->prepare("DELETE FROM groups_has_students
            WHERE studentID = :studentID and groupID = :groupID");
          $stmt->bindparam(":groupID",$groupID);
          $stmt->bindparam(":studentID",$studentID);
          $stmt->execute();
     }
     public function checkName($name){
         $stmt = $this->connect()->prepare("SELECT * FROM groups WHERE name= :name and instructorID = :aid");
         $stmt->bindparam(":name",$name);
         $stmt->bindparam(":aid",$_SESSION['mydata']->id);
         $stmt->execute();
         $result = $stmt->fetchColumn();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
     }
}

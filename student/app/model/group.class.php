<?php
require_once 'dbh.class.php';
class group extends dbh{


  function getMyGroups(){
      $stmt = $this->connect()->prepare("SELECT g.id,g.`name`,
        (select name from instructor where id = g.instructorID) instructor,gs.joinDate
        from groups_has_students gs
        inner join groups g
        ON gs.groupID = g.id
        where gs.studentID = :studID");
      $stmt->bindparam(":studID",$_SESSION['student']->id);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function checkCode($code){
      $stmt = $this->connect()->prepare("select * from group_invitations where code = :code");
      $stmt->bindparam(":code",$code);
      $stmt->execute();
      $result=$stmt->rowCount();
      if($result > 0){
              return true;
      }else{
              return false;
      }
    }
    public function alreadyMemeber($code){
        $stmt = $this->connect()->prepare("select * from groups_has_students where groupID = (SELECT groupID from group_invitations where code = :code) and studentID = :studID");
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

    public function joinGroup($code){
        $stmt = $this->connect()->prepare("insert into groups_has_students (GroupID,studentID,joinDate)
                                select groupID,:studID,convert_tz(now(),@@session.time_zone,'+02:00') from group_invitations where code = :code;
                                DELETE FROM group_invitations where code = :code");
        $stmt->bindparam(":code",$code);
        $stmt->bindparam(":studID",$_SESSION['student']->id);
        $stmt->execute();
        $result=$stmt->rowCount();
        return 1;
      }
      public function leaveGroup($groupID){
          $stmt = $this->connect()->prepare("Delete From groups_has_students where groupID = :groupID and studentID = :studID");
          $stmt->bindparam(":groupID",$groupID);
          $stmt->bindparam(":studID",$_SESSION['student']->id);
          $stmt->execute();
          return 1;
        }

}

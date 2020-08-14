<?php

class course extends dbh
{
    public function getAllParents()
    {
        $stmt = $this->connect()->prepare("
      select id,name,parent,instructorID,
      (select count(*) from question where courseID = c.id and !deleted) as questions,
      (select count(*) from course where parent = c.id) as childs,
      (select count(*) from test where courseID = c.id) as tests
      from course c where instructorID = :aid and parent IS NULL;");
      $stmt->bindparam(":aid",$_SESSION['mydata']->id);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getAllChilds($parentid)
    {
        $stmt = $this->connect()->prepare("
        select id,name,parent,instructorID,
        (select count(*) from question where courseID = c.id and !deleted) as questions,
        (select count(*) from test where courseID = c.id) as tests
        from course c where instructorID = :aid and parent = :prt;");
        $stmt->bindparam(":prt",$parentid);
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function noCourses(){
        $stmt = $this->connect()->prepare("SELECT id FROM course where instructorID = :aid and parent IS NOT NULL ");
        $stmt->bindparam(":aid",$_SESSION['mydata']->id);
        $stmt->execute();
        $result=$stmt->rowCount();
        if($result == 0){
                return true;
        }else{
                return false;
        }
      }
    public function delete($cid){
      $stmt=$this->connect()->prepare("DELETE FROM course
                                        WHERE id=:cid and instructorID = :aid ");
      $stmt->bindparam(":cid",$cid);
      $stmt->bindparam(":aid",$_SESSION['mydata']->id);

      $stmt->execute();
    }
    public function insert($name,$course)
     {
      try
        {
           $stmt = $this->connect()->prepare("INSERT INTO course(name,parent,instructorID)
                                              VALUES(:name,:parent, :instructorID)");
           $stmt->bindparam(":name",$name);
           $stmt->bindparam(":parent",$course);
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
     public function update($id,$name,$parent)
      {
       try
         {
            $stmt = $this->connect()->prepare("UPDATE course SET name = :name,parent = :parent where id = :id;");
            $stmt->bindparam(":name",$name);
            $stmt->bindparam(":parent",$parent);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            return true;
         }
       catch(PDOException $e)
         {
            echo $e->getMessage();
            return false;
         }
      }
      public function checkName($name,$parent){
          $stmt = $this->connect()->prepare("SELECT * FROM course
                                            WHERE name= :name
                                            and (parent= :parent or ISNULL(parent))
                                            and instructorID = :aid");
          $stmt->bindparam(":name",$name);
          $stmt->bindparam(":parent",$parent);
          $stmt->bindparam(":aid",$_SESSION['mydata']->id);
          $stmt->execute();
          $result = $stmt->fetchColumn();
          if($result > 0){
                  return true;
          }else{
                  return false;
          }

      }
      public function TopicsExists(){
          $stmt = $this->connect()->prepare("SELECT count(*) count from course where parent IS NOT NULL and instructorID = :instructorID");
          $stmt->bindparam(":instructorID",$_SESSION['mydata']->id);
          $stmt->execute();
          $result=$stmt->fetchAll(PDO::FETCH_OBJ);
          if($result[0]->count > 0){
                  return true;
          }else{
                  return false;
          }
      }

}

<?php
require_once 'dbh.class.php';

class student extends dbh {
  public function login($id,$password){
      $stmt = $this->connect()->prepare("SELECT id,password from student where id = :id and password = :password;");
      $stmt->bindparam(":id",$id);
      $stmt->bindparam(":password",$password);
      $stmt->execute();
      $result=$stmt->rowCount();
      if($result > 0){
              return true;
      }else{
              return false;
      }
    }
  public function checkIDforRegister($id){
      $stmt = $this->connect()->prepare("SELECT id,(CASE WHEN password is null || password = '' then 1 ELSE 0 END) nullpass from student where id = :id;");
      $stmt->bindparam(":id",$id);
      $stmt->execute();
      $result=$stmt->fetchAll(PDO::FETCH_OBJ);
      if(!empty($result)){
        return $result[0];
      }else{
        return false;
      }
    }
    public function checkSession($id)
      {
          $session = session_id();
         $stmt = $this->connect()->prepare("SELECT sessionID FROM student where id = :id and sessionID = :sessionID;");
         $stmt->bindparam(":id",$id);
         $stmt->bindparam(":sessionID",$session);
         $stmt->execute();
         $result=$stmt->rowCount();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
      }
    public function setSession($id)
      {
          $session = session_id();
         $stmt = $this->connect()->prepare("UPDATE student SET sessionID = :sessionID where id = :id;");
         $stmt->bindparam(":id",$id);
         $stmt->bindparam(":sessionID",$session);
         $stmt->execute();
      }
    public function register($id,$password,$email,$phone)
     {
      try
        {
           $stmt = $this->connect()->prepare("UPDATE student
             SET email = :email,
             phone = :phone,
             password = :password
             WHERE id = :id");
           $stmt->bindparam(":id",$id);
           $stmt->bindparam(":password",$password);
           $stmt->bindparam(":email",$email);
           $stmt->bindparam(":phone",$phone);
           $stmt->execute();
           return true;
        }
      catch(PDOException $e)
        {
           echo $e->getMessage();
           return false;
        }
     }

     public function checkEmail($email){
         $stmt = $this->connect()->prepare("SELECT id FROM student where email = :email");
         $stmt->bindparam(":email",$email);
         $stmt->execute();
         $result=$stmt->rowCount();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
       }
     public function checkPhone($phone){
         $stmt = $this->connect()->prepare("SELECT id FROM student where phone = :phone");
         $stmt->bindparam(":phone",$phone);
         $stmt->execute();
         $result=$stmt->rowCount();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
       }
     public function checkID($id){
         $stmt = $this->connect()->prepare("SELECT id FROM student where id = :id and password is null");
         $stmt->bindparam(":id",$id);
         $stmt->execute();
         $result=$stmt->rowCount();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
       }
     public function checkPasswordToken($email,$token){
         $stmt = $this->connect()->prepare("SELECT 1 FROM student WHERE password_token = :token AND email = :email AND token_expire > NOW()");
         $stmt->bindparam(":token",$token);
         $stmt->bindparam(":email",$email);
         $stmt->execute();
         $result=$stmt->rowCount();
         if($result > 0){
                 return true;
         }else{
                 return false;
         }
       }
     public function updateInfo($email,$phone)
      {
       try
         {
            $stmt = $this->connect()->prepare("UPDATE student
              SET email = :email,
              phone = :phone
              WHERE id = :id");
            $stmt->bindparam(":id",$_SESSION['student']->id);
            $stmt->bindparam(":email",$email);
            $stmt->bindparam(":phone",$phone);
            $stmt->execute();
            return true;
         }
       catch(PDOException $e)
         {
            echo $e->getMessage();
            return false;
         }
      }
      public function updatePassword($email,$password)
        {
           try
           {
              $stmt=$this->connect()->prepare("UPDATE student
                        SET password= :password,
                        password_token = null,
                        token_expire = null
                        WHERE email = :email;");
              $stmt->bindparam(":password",$password);
              $stmt->bindparam(":email",$email);
              $stmt->execute();

              return true;
           }
             catch(PDOException $e)
           {
              echo $e->getMessage();
              return false;
           }
        }
        public function generatePasswordToken($email,$token)
         {
           try
             {
                $stmt = $this->connect()->prepare("UPDATE student
                SET password_token = :token,
                    token_expire = DATE_ADD(now(), INTERVAL 30 MINUTE)
                where email = :email;
                insert into mails(studentID,sends_at,type)
                select id,convert_tz(now(),@@session.time_zone,'+02:00'),0 from student where email = :email");
                $stmt->bindparam(":email",$email);
                $stmt->bindparam(":token",$token);
                $stmt->execute();
                return true;
             }
           catch(PDOException $e)
             {
                echo $e->getMessage();
                return false;
             }
         }
    public function getByID($id)
      {
         $stmt = $this->connect()->prepare("SELECT * FROM student where id = :id");
         $stmt->bindparam(":id",$id);
         $stmt->execute();
         $result=$stmt->fetchAll(PDO::FETCH_OBJ);
         return $result[0];
      }


}

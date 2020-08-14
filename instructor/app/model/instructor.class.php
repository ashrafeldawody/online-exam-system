<?php

class instructor extends dbh{

  public function getAll(){
      $stmt = $this->connect()->query("SELECT id,name, password, email, phone, isAdmin FROM instructor");
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      return $result;
  }
  public function getByEmail($email)
   {
    $stmt = $this->connect()->prepare("SELECT id,name, password, email, phone, isAdmin FROM instructor where email = :email");
    $stmt->bindparam(":email",$email);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_OBJ);
    return $result[0];
   }
   public function checkEmail($email){
       $stmt = $this->connect()->prepare("SELECT id FROM instructor where email = :email");
       $stmt->bindparam(":email",$email);
       $stmt->execute();
       $result=$stmt->rowCount();
       if($result > 0){
               return true;
       }else{
               return false;
       }
     }
       public function login($email,$password){
           $stmt = $this->connect()->prepare("select email,password from instructor where email = :email and password = :password and !suspended;");
           $stmt->bindparam(":email",$email);
           $stmt->bindparam(":password",$password);
           $stmt->execute();
           $result=$stmt->rowCount();
           if($result > 0){
                   return true;
           }else{
                   return false;
           }
         }
       public function checkAccount($id){
           $stmt = $this->connect()->prepare("select 1 from instructor where id = :id and !suspended;");
           $stmt->bindparam(":id",$id);
           $stmt->execute();
           $result=$stmt->rowCount();
           if($result > 0){
                   return true;
           }else{
                   return false;
           }
         }

         public function register($name,$password,$email,$phone,$invite = null)
          {
           try
             {
               if($invite != null){
                $stmt = $this->connect()->prepare("DELETE FROM instructor_invitations WHERE code = :code;
                                                  INSERT INTO instructor(name,password,email,phone)
                                                  VALUES(:name, :password, :email, :phone);");
                                                  $stmt->bindparam(":code",$invite);
                }else{
                  $stmt = $this->connect()->prepare("INSERT INTO instructor(name,password,email,phone)
                                                  VALUES(:name, :password, :email, :phone);");
                }
                $stmt->bindparam(":name",$name);
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
          public function updateInfo($name,$email,$phone)
           {
            try
              {
                 $stmt = $this->connect()->prepare("UPDATE instructor SET name = :name,email = :email, phone = :phone
                   WHERE id = :id");
                 $stmt->bindparam(":id",$_SESSION['mydata']->id);
                 $stmt->bindparam(":name",$name);
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
                  $stmt=$this->connect()->prepare("UPDATE instructor
                            SET password= :password
                            WHERE email= :email;");
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
          public function resetPassword($email,$password)
            {
               try
               {
                  $stmt=$this->connect()->prepare("UPDATE instructor
                            SET password= :password,
                            password_token = null,
                            token_expire = null
                            WHERE email= :email;");
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
                    $stmt = $this->connect()->prepare("UPDATE instructor
                      SET password_token = :token,
                          token_expire = DATE_ADD(now(), INTERVAL 30 MINUTE)
                          where email = :email;
                    INSERT INTO mails(instructorID,sends_at,type)
                    select id,convert_tz(now(),@@session.time_zone,'+02:00'),1 from instructor where email = :email");
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
              public function isValidReset($email,$token){
                $stmt = $this->connect()->prepare("SELECT 1 FROM instructor WHERE password_token = :token AND email = :email AND token_expire > NOW()");
                $stmt->bindparam(":token",$token);
                $stmt->bindparam(":email",$email);
                $stmt->execute();
                $result = $stmt->rowCount();
                if($result > 0){
                        return 1;
                }else{
                        return 0;
                }
            }
              public function isValidInvite($code){
                $stmt = $this->connect()->prepare("SELECT * FROM instructor_invitations WHERE code = :code");
                $stmt->bindparam(":code",$code);
                $stmt->execute();
                $result = $stmt->rowCount();
                if($result > 0){
                        return true;
                }else{
                        return false;
                }
            }
}

<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once 'vendor/autoload.php';
include_once 'app/model/dbh.class.php';
include_once 'app/model/mail.class.php';
$domain = 'www.o6uexam.online';
$_mail = new mail;
$mails = $_mail->getUnsentMails();
          foreach($mails as $m){
            if($m->type == 0){
              $resetData = $_mail->getStudentToken($m->studentID);
              $to = $resetData['email'];
              $token = $resetData['password_token'];
              $name = $resetData['name'];
              $subject = 'Reset Your Password - O6U EXAM SYSTEM';
              $resetLink = 'http://' . $domain . '/student/?login&email=' . $to . '&token=' . $token;
              $website = 'http://' . $domain;
              $template = file_get_contents(dirname(__FILE__) . '/mails/resetPassword.html');

			        $template = str_replace('{{resetLink}}', $resetLink, $template);
			        $template = str_replace('{{website}}', $website, $template);

              sendMail($to, $name,$subject,$template);
              $_mail->mailSent($m->id);
            }elseif($m->type == 1){
              $resetData = $_mail->getInstructorToken($m->instructorID);
              $to = $resetData['email'];
              $token = $resetData['password_token'];
              $name = $resetData['name'];
              $subject = 'Reset Your Password - O6U EXAM SYSTEM';
			        $resetLink = 'http://' . $domain . '/instructor/?reset&email=' . $to . '&token=' . $token;
              $website = 'http://' . $domain;
              $template = file_get_contents(dirname(__FILE__) . '/mails/resetPassword.html');
      			  $template = str_replace('{{resetLink}}', $resetLink, $template);
      			  $template = str_replace('{{website}}', $website, $template);
              sendMail($to, $name,$subject,$template);
              $_mail->mailSent($m->id);
            }elseif($m->type == 2){
        		$result = $_mail->getResult($m->resultID);
            $percent = ($result->TestDegree > 0)?round((($result->FinalGrade / $result->TestDegree) * 100)) : 0;
              $name = 'Student';
              $subject = 'Result Details - O6U EXAM SYSTEM';
              if($percent > 50){
              $message = 'Passed ' . $result->studentName . ', You have passed the Test';
              $header = 'Passed..';
              }
              else{
              $message = 'Unfortunately ' . $result->studentName . ', You did\'t pass the Test';
              $header = 'Failed..';
              }
              $resultLink = 'http://' . $domain . '/student/?results&id=' . $result->id;
              $percent = 'Your Result is: ' . $percent . '%';
              $website = 'http://' . $domain;
              $template = file_get_contents(dirname(__FILE__) . '/mails/result.html');
              $template = str_replace('{{resultLink}}', $resultLink, $template);
      			  $template = str_replace('{{percent}}', $percent, $template);
      			  $template = str_replace('{{header}}', $header, $template);
      			  $template = str_replace('{{message}}', $message, $template);
      			  $template = str_replace('{{website}}', $website, $template);
              sendMail($result->studentMail, $result->studentName,$subject,$template);
              $_mail->mailSent($m->id);
            }elseif($m->type == 3){
              $result = $_mail->getResult($m->resultID);
              $percent = ($result->TestDegree > 0)?round((($result->FinalGrade / $result->TestDegree) * 100)) : 0;
              $name = $result->studentName;
              $subject = 'New Result - O6U EXAM SYSTEM';
              $variables = array();
              $message =  $name . ' Just Finished The Test ('. $result->testName .')';
              $header = 'New Test Taken..';
              $resultLink = 'http://' . $domain . '/instructor/?results&id=' . $result->id;
              $percent = 'Result Is: ' . $percent . '%';
              $website = 'http://' . $domain;
              $template = file_get_contents(dirname(__FILE__) . '/mails/result.html');

      			  $template = str_replace('{{percent}}', $percent, $template);
      			  $template = str_replace('{{header}}', $header, $template);
      			  $template = str_replace('{{message}}', $message, $template);
      			  $template = str_replace('{{resultLink}}', $resultLink, $template);
      			  $template = str_replace('{{website}}', $website, $template);
              sendMail($result->instructorMail, $result->instructorName,$subject,$template);
              $_mail->mailSent($m->id);
            }
          }

          function sendMail($to, $name,$subject,$template){
            $mail = new PHPMailer;
            $mail->SMTPAuth = false;
          	$mail->Host = 'relay-hosting.secureserver.net';
          	$mail->Port = 25;
          	$mail->Username = '';
          	$mail->Password = '';
            $mail->setFrom('support@o6uexam.online', 'O6U Online Exam');
            $mail->addReplyTo('support@o6uexam.online', 'O6U Online Exam');
            $mail->addAddress($to, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $template;
            $mail->send();
          }

<?php

    namespace DAO;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class AdministratorDAO extends DAO{

        public function countMembers(){
            $sql = 'SELECT COUNT(*) FROM members WHERE status = :status';
            $result = $this->sql($sql, [
                'status' => 'member'
            ]);
            $row = $result->fetch();

            $nbMembers = $row['COUNT(*)'];

            return $nbMembers;
        }

        public function countBooks(){
            $sql = 'SELECT COUNT(*) FROM bookslist';
            $result = $this->sql($sql);
            $row = $result->fetch();

            $nbBooks = $row['COUNT(*)'];

            return $nbBooks;
        }

        public function contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){

            $this->sendEmail($loginSeeker, $emailSeeker, $subjectMail, $bodyMail);

            $message = $loginSeeker. ', votre message a été envoyé. Vous receverez une réponse le plus rapidement possible.';

            return $message;
        }

        public function sendEmail($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){
            $mail = new PHPMailer(true);
            try{
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'auth.smtp.1and1.fr';
                $mail->SMTPAuth = true;
                $mail->Username = 'hab@audeleissen.com';
                $mail->Password = '#mp;W"=2qgLbgL]V?-g';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setfrom($emailSeeker, $loginSeeker);
                $mail->addAddress('hab@audeleissen.com');

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subjectMail;
                $mail->Body = $bodyMail;
                $mail->AltBody = $bodyMail;

                $mail->send();

            } catch (Exception $e){
                echo 'Votre message n\'a pas pu être envoyé.';
                echo 'Erreur : ' . $mail->ErrorInfo;
            }


        }
    }
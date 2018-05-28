<?php

    namespace DAO;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class AdministratorDAO extends DAO{

        /**
         * @return mixed
         */
        // Count the number of members registered on the website
        public function countMembers(){
            $sql = 'SELECT COUNT(*) FROM members WHERE status = :status';
            $result = $this->sql($sql, [
                'status' => 'member'
            ]);
            $row = $result->fetch();

            $nbMembers = $row['COUNT(*)'];

            return $nbMembers;
        }

        /**
         * @return mixed
         */
        // Count the number of books registered on the website
        public function countBooks(){
            $sql = 'SELECT COUNT(*) FROM bookslist';
            $result = $this->sql($sql);
            $row = $result->fetch();

            $nbBooks = $row['COUNT(*)'];

            return $nbBooks;
        }

        /**
         * @param $loginSeeker
         * @param $emailSeeker
         * @param $subjectMail
         * @param $bodyMail
         * @return string
         */
        // Send an email to the administrator
        public function contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){

            $this->sendEmail($loginSeeker, $emailSeeker, $subjectMail, $bodyMail);

            $message = $loginSeeker. ', votre message a été envoyé. Vous receverez une réponse le plus rapidement possible.';

            return $message;
        }

        /**
         * @param $loginSeeker
         * @param $emailSeeker
         * @param $subjectMail
         * @param $bodyMail
         */
        // PHPMailer is generating the email with specific settings to be send to the administrator
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
                $errorMessage = 'Votre message n\'a pas pu être envoyé. Erreur : ' . $mail->ErrorInfo.'';
                header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');
            }


        }
    }
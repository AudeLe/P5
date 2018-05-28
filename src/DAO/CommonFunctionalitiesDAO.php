<?php

    namespace DAO;

    use models\BookList;

    // PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class CommonFunctionalitiesDAO extends DAO{

        /**
         * @param $email
         * @param $subjectMail
         * @param $bodyMail
         */
        // PHPMailer is generating an email send "from the website"
        public function sendEmail($email, $subjectMail, $bodyMail, $altBodyMail){
            $mail = new PHPMailer(true);
            try{
                // Server settings

                //$mail->SMTPDebug = 2;
                $mail->isSMTP();
                $mail->Host = 'auth.smtp.1and1.fr';
                $mail->SMTPAuth = true;
                $mail->Username = 'hab@audeleissen.com';
                $mail->Password = '#mp;W"=2qgLbgL]V?-g';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setfrom('hab@audeleissen.com', 'Plop');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subjectMail;
                $mail->Body = $bodyMail;
                $mail->AltBody = $altBodyMail;

                $mail->send();
                //echo 'Message has been sent';
            } catch (Exception $e){
                $errorMessage = 'Message could not be sent. Erreur : ' . $mail->ErrorInfo .'';
                header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage .'');
            }

        }

        /**
         * @param array $row
         * @return BookList
         */
        // Initiates the setters for book datas
        public function buildObject(array $row){
            $book = new BookList();
            $book->setId($row['id']);
            $book->setAuthor($row['author']);
            $book->setTitle($row['title']);
            $book->setISBN($row['ISBN']);
            $book->setSummary($row['summary']);
            $book->setPublishingYear($row['publishingYear']);
            $book->setNbPages($row['nbPages']);
            return $book;
        }
    }
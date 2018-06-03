<?php

    namespace DAO;

    use models\BookList;

    class CommonFunctionalitiesDAO extends DAO{

        /**
         * @param $email
         * @param $subjectMail
         * @param $bodyMail
         */
        // Sending email through PHP's mail() function
        public function sendEmail($email, $subjectMail, $bodyMail, $altBodyMail){
            $hab = 'hab@audeleissen.com';

            if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,6}$#", $email)){
                $return_line = "\r\n";
            } else{
                $return_line = "\n";
            }

            // Creation of the messages with a text format and an HTML one
            $message_txt = $altBodyMail;
            $message_html = $bodyMail;

            // Creation of the boundary
            $boundary = "-----=".md5(rand());

            //$to = "hab@audeleissen.com";
            // Define the subject
            $subject = $subjectMail;

            // Creation of the email header
            $headers = 'From: \'Have a Book\' <hab@audeleissen.com >' . $return_line;
            $headers .= 'Reply-To: ' . $hab;
            $headers .= 'MIME-Version: 1.0' . $return_line;
            $headers .= 'X-Priority: 3' . $return_line;
            $headers .= 'Content-Type: multipart/alternative;' . $return_line . ' boundary= ' . $boundary . $return_line;

            // Creation of the message
            $message = $return_line . '--' . $boundary . $return_line;
            // Adding the message with a text format
            $message .= 'Content-Type: text/plain; charset=\'ISO-8859-1\''.$return_line;
            $message .= 'Content-Transfer-Encoding: 8bit'.$return_line;
            $message .= $return_line . $message_txt . $return_line;

            $message .= $return_line . '--' . $boundary .$return_line;
            // Adding the message with the HTML format
            $message .= 'Content-Type: text/html; charset=\'ISO-8859-1\''.$return_line;
            $message .= 'Content-Transfer-Encoding: 8bit'.$return_line;
            $message .= $return_line . $message_html . $return_line;

            $message .= $return_line . '--' . $boundary . '--' . $return_line;
            $message .= $return_line . '--' . $boundary . '--' . $return_line;

            mail($email, $subject, $message, $headers);
            if(mail($email, $subject, $message, $headers)){
                $message = 'Votre message a été envoyé. Vous receverez une réponse le plus rapidement possible.';
                return $message;
            } else {
                $message = 'Votre message n\'a pas pu être envoyé. Veuillez réessayer ultérieurement.';
                return $message;
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
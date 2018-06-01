<?php

    namespace DAO;

    use models\RegistrationForm;

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
         */
        // PHPMailer is generating the email with specific settings to be send to the administrator
        public function sendEmail($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){

            $violations = [];

            $validator = new RegistrationForm();
            $validator['login'] = $validator->checkLogin($loginSeeker);
            $validator['email'] = $validator->checkEmail($emailSeeker);

            if(empty($violations['login']) && empty($violations['email'])){
                if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,6}$#", $emailSeeker)){
                    $return_line = "\r\n";
                } else{
                    $return_line = "\n";
                }

                // Creation of the messages with a text format and an HTML one
                $message_txt = $bodyMail;
                $message_html = $bodyMail;

                // Creation of the boundary
                $boundary = "-----=".md5(rand());

                //$to = "hab@audeleissen.com";
                // Define the subject
                $subject = $subjectMail;

                // Creation of the email header
                $headers = 'From: ' . $loginSeeker . ' - ' . $emailSeeker . $return_line;
                $headers .= 'Reply-To: ' . $emailSeeker;
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

                mail('hab@audeleissen.com', $subject, $message, $headers);
                if(mail($emailSeeker, $subject, $message, $headers)){
                    $message = $loginSeeker. ', votre message a été envoyé. Vous receverez une réponse le plus rapidement possible.';

                    return $message;
                } else {
                    $message = $loginSeeker. ', votre message n\'a pas pu être envoyé. Veuillez réessayer ultérieurement.';

                    return $message;
                }

            } else {
                // If there is, the error(s) is/are displayed
                foreach ($violations as $violation) {
                    if ($violation !== null){
                        echo $violation . '<br />';
                    }
                }
            }

        }
    }
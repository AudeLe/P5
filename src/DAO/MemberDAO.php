<?php
    namespace DAO;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\exception;

    /*use Swift_SmtpTransport;
    use Swift_Mailer;
    use Swift_Message;*/

    use models\BookList;

    class MemberDAO extends DAO{
        public function getMemberBookList(){
            $sql = 'SELECT id, author, title, summary, publishingYear, ISBN, nbPages FROM bookslist WHERE id_member = :id_member';
            $result = $this->sql($sql, [
                'id_member' => $_SESSION['id']
            ]);

            $memberBookList = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $memberBookList[$bookId] = $this->buildObject($row);
            }

            return $memberBookList;

        }

        // Verify if a book has been registered
        public function searchBook($id, $ISBN){
            $sql = 'SELECT id_member, author, title, ISBN FROM bookslist WHERE id_member = :id';
            $result = $this->sql($sql, [
                'id' => $id
            ]);

            foreach($result as $row){
                $bookISBN = $row['ISBN'];

                if($bookISBN == $ISBN){
                    $message = 'Vous avez déjà enregistré cet ouvrage.';
                } else {
                    $message = 'Vous n\'avez pas ou n\'avez pas encore enregistré cet ouvrage.';
                }
            }

            return $message;
        }

        public function reachFriend($login, $loginFriend){
            if($login == $loginFriend){
                $message =  'Vous ne pouvez pas vous envoyer une demande d\'ami(e).';
            } else {
                $sql = 'SELECT login, email FROM members WHERE login = :login';
                $result = $this->sql($sql,[
                    'login' => $loginFriend
                ]);
                $row = $result->fetch();

                if($row){
                    $email = $row['email'];

                    $subjectMail = 'HaB - Demande de partage de liste de livres';
                    $bodyMail = 'Vous avez reçu ce message car '.$login.' souhaite accéder à votre liste de livres.<br/>Veuillez vous rendre sur cette <a href="http://localhost/P5/public/index.php?action=shareBookList&login=' . $login . '&loginFriend=' .$loginFriend. '">page</a>';

                    $this->sendEmail($email, $subjectMail, $bodyMail);
                    $message = 'Une demande de partage de liste de livres a été envoyé.';

                } else {

                    $message = 'Le pseudo indiqué ne correspond à aucun de nos membres.';
                }

                return $message;
            }
            return $message;
        }

        public function shareBookListWithFriend($login, $loginFriend){
            $sql = 'SELECT login_member, login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :login';
            $result = $this->sql($sql, [
                'login' =>$login
            ]);
            $row = $result->fetch();

            if($row){
                // If the member already ask to share someone's booklist, checking if there still is place to register
                if($row['login_share_booklist_1'] !== null){
                    if($row['login_share_booklist_1'] !== $loginFriend){
                        $sql = 'UPDATE sharedbooklist SET login_share_booklist_1 = :loginFriend';
                        $this->sql($sql, [
                            'loginFriend' => $loginFriend
                        ]);

                        $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                        $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';

                    } else {
                        echo 'Vous avez déjà été ajouté.';
                    }

                } elseif($row['login_share_booklist_2']){
                    if($row['login_share_booklist_2'] !== $loginFriend){
                        $sql = 'UPDATE sharedbooklist SET login_share_booklist_2 = :loginFriend';
                        $this->sql($sql, [
                            'loginFriend' => $loginFriend
                        ]);

                        $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                        $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';

                    } else {

                        $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                        $message = 'Vous avez déjà été ajouté. Une mail a été envoyé à ' . $login . ' afin de le lui signaler.';
                    }

                } elseif($row['login_share_booklist_3']){
                    if($row['login_share_booklist_3'] !== $loginFriend){
                        $sql = 'UPDATE sharedbooklist SET login_share_booklist_3 = :loginFriend';
                        $this->sql($sql, [
                            'loginFriend' => $loginFriend
                        ]);

                        $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                        $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';
                    } else {
                        $bodyMail = 'Vous avez déjà ajouté ' . $loginFriend . ' à votre cercle d\'ami(e)s. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                        $message = 'Vous avez déjà été ajouté. Un mail a été envoyé à ' . $login . ' afin de le lui signaler';
                    }
                } else {
                    $message = 'Vous ne pouvez être ajouté à son cercle d\'ami(e)s. Le maximum a été atteint. Un mail expliquant cette situation au demandeur a été envoyé';
                }

            } else {
                // If the member has not ask to share someone's booklist yet, updating the db
                $sql = 'UPDATE sharedbooklist SET login_member = :login, login_share_booklist_1 = :loginShareBooklist';
                $this->sql($sql, [
                    'login' => $login,
                    'loginShareBooklist' => $loginFriend
                ]);

                $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';

            }

            // Recover the email of the person asking to access the booklist
            $sql = 'SELECT email FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            $email = $row['email'];

            $subjectMail = 'Acceptation de votre demande de partage !';

            $this->sendEmail($email, $subjectMail, $bodyMail);

            return $message;
        }

        public function notShare($login, $loginFriend){
            $sql = 'SELECT email FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            $email = $row['email'];

            $subjectMail = 'Refus de votre demande de partage';
            $bodyMail = 'Votre demande de partage de la liste de livres de ' . $loginFriend . ' a été refusée.';

            $this->sendEmail($email, $subjectMail, $bodyMail);

            $message = 'Un mail a été envoyé à ' . $login . 'afin de lui signifier que vous avez refusé sa demande.';

            return $message;
        }

        public function sendEmail($email, $subjectMail, $bodyMail){
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
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients.';

                $mail->send();
                //echo 'Message has been sent';
            } catch (Exception $e){
                echo 'Message could not be sent.';
                echo 'Mailor Error : ' . $mail->ErrorInfo;
            }

            // SWIFTMAILER - Create the Transport
            /*$transport = (new Swift_SmtpTransport('auth.smtp.1and1.fr', 465, 'ssl'))
                ->setUsername('hab@audeleissen.com')
                ->setPassword('**************');

            // Create theMailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message('Demande d\'autorisation de partage de liste de livres'))
                ->setFrom(['hab@audeleissen.com' => 'Plop'])
                ->setTo($email)
                ->setBody('Here is the message itself');

            // Send the message
            $result = $mailer->send($message);*/

        }


        private function buildObject(array $row){
            $book = new BookList();
            $book->setId($row['id']);
            $book->setAuthor($row['author']);
            $book->setTitle($row['title']);
            //$book->setIdMember($row['id_member']);
            $book->setISBN($row['ISBN']);
            $book->setSummary($row['summary']);
            $book->setPublishingYear($row['publishingYear']);
            $book->setNbPages($row['nbPages']);
            return $book;
        }
    }
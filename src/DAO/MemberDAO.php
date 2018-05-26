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

        public function reminderFriends($login){
            $sql = 'SELECT login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if(empty($row['login_share_booklist_1']) && empty($row['login_share_booklist_2']) && empty($row['login_share_booklist_3'])){
                $friends = [];
            } else {
                $friends = [$row['login_share_booklist_1'], $row['login_share_booklist_2'], $row['login_share_booklist_3']];
            }

            return $friends;
        }

        public function reachFriend($login, $loginFriend){
            // You cannot sned an invit to yourself
            if($login == $loginFriend){
                $message =  'Vous ne pouvez pas vous envoyer une demande d\'ami(e).';
            } else {
                // We are checking if the user already has people sharing their booklist with.
                $sql = 'SELECT login_member, login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :login';
                $result = $this->sql($sql, [
                    'login' => $login
                ]);
                $row = $result -> fetch();

                // If the member is registered as someone who already has asked people to share their booklist
                // we are checking if the person asked is already in the member's sharedbooklist table.
                if($row){

                    // We are checking if the spots already have a value
                    if($row['login_share_booklist_1'] !== null && $row['login_share_booklist_2'] !== null && $row['login_share_booklist_3'] !== null){
                        $message = 'Vous ne pouvez pas enregister plus de 3 personnes.';
                    }
                    // We are checking if within one the spots the friend is already registered
                    elseif($row['login_share_booklist_1'] == $loginFriend || $row['login_share_booklist_2'] == $loginFriend || $row['login_share_booklist_3'] == $loginFriend){
                        $message = $loginFriend . ' fait déjà partie de votre cercle d\'ami(e)s.';
                    }
                    // If it's not the case, an email with the request is sent
                    else {
                        $sql = 'SELECT login, email FROM members WHERE login = :login';
                        $result = $this->sql($sql,[
                            'login' => $loginFriend
                        ]);
                        $row = $result->fetch();

                        if($row){
                            // We are recovering the email of the recipient
                            $email = $row['email'];

                            $subjectMail = 'HaB - Demande de partage de liste de livres';
                            $bodyMail = 'Vous avez reçu ce message car '.$login.' souhaite accéder à votre liste de livres.<br/>Veuillez vous rendre sur cette <a href="http://localhost/P5/public/index.php?action=shareBookList&login=' . $login . '&loginFriend=' .$loginFriend. '">page</a>';

                            $this->sendEmail($email, $subjectMail, $bodyMail);
                            $message = 'Une demande de partage de liste de livres a été envoyé.';

                        } else {
                            // If there is anyone with this login
                            // we are displing a message saying it.
                            $message = 'Le pseudo indiqué ne correspond à aucun de nos membres.';
                        }
                    }

                // If the member is not registered within the sharedbooklist, an email with the request is sent.
                } else {

                    $sql = 'SELECT login, email FROM members WHERE login = :login';
                    $result = $this->sql($sql,[
                        'login' => $loginFriend
                    ]);
                    $row = $result->fetch();

                    if($row){
                        // We are recovering the email of the recipient
                        $email = $row['email'];

                        $subjectMail = 'HaB - Demande de partage de liste de livres';
                        $bodyMail = 'Vous avez reçu ce message car '.$login.' souhaite accéder à votre liste de livres.<br/>Veuillez vous rendre sur cette <a href="http://localhost/P5/public/index.php?action=shareBookList&login=' . $login . '&loginFriend=' .$loginFriend. '">page</a>';

                        $this->sendEmail($email, $subjectMail, $bodyMail);
                        $message = 'Une demande de partage de liste de livres a été envoyé.';

                    } else {
                        // If there is anyone with this login
                        // we are displing a message saying it.
                        $message = 'Le pseudo indiqué ne correspond à aucun de nos membres.';
                    }
                }
            }
            return $message;
        }

        public function shareBookListWithFriend($login, $loginFriend){

            // We are checking is the member already asked someone to share their booklist
            $sql = 'SELECT login_member, login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :login';
            $result = $this->sql($sql, [
                'login' =>$login
            ]);
            $row = $result->fetch();

            if($row){
                // If the member already ask to share someone's booklist, checking if there still is place to register
                // If the spot is null, then we register the friend.
                if($row['login_share_booklist_1'] == null){

                    $sql = 'UPDATE sharedbooklist SET login_share_booklist_1 = :loginFriend WHERE login_member = :login';
                    $this->sql($sql, [
                        'login' => $login,
                        'loginFriend' => $loginFriend
                    ]);

                    $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                    $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';


                } elseif($row['login_share_booklist_2'] == null){

                    $sql = 'UPDATE sharedbooklist SET login_share_booklist_2 = :loginFriend WHERE login_member = :login';
                    $this->sql($sql, [
                        'login' => $login,
                        'loginFriend' => $loginFriend
                    ]);

                    $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                    $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';


                } elseif($row['login_share_booklist_3'] == null){

                    $sql = 'UPDATE sharedbooklist SET login_share_booklist_3 = :loginFriend WHERE login_member = :login';
                    $this->sql($sql, [
                        'login' => $login,
                        'loginFriend' => $loginFriend
                    ]);

                    $bodyMail = 'Votre demande à ' . $loginFriend . ' a été acceptée. Connectez vous à votre espace personnel afin de vérifier s\'il/elle a déjà un livre !';
                    $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez accepté sa demande.';

                } else {
                    // If there is not spot available, display a message saying so.
                    $message = 'Vous ne pouvez être ajouté à son cercle d\'ami(e)s. Le maximum a été atteint (3 personnes).';
                }

            } else {
                // If the member has not ask to share someone's booklist yet, updating the db
                $sql = 'INSERT INTO sharedbooklist(login_member, login_share_booklist_1) VALUES (:login, :loginShareBooklist)';
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

            $message = 'Un mail a été envoyé à ' . $login . ' afin de lui signifier que vous avez refusé sa demande.';

            return $message;
        }

        public function nbBooks(){
            $sql = 'SELECT COUNT(*) FROM bookslist WHERE id_member = :id';
            $result = $this->sql($sql, [
                'id' => $_SESSION['id']
            ]);
            $row = $result->fetch();
            $totalBooks = $row['COUNT(*)'];

            return $totalBooks;
        }

        public function deleteSharedBooklist($login, $loginFriend){
            $sql = 'SELECT login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row['login_share_booklist_1'] == $loginFriend){
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_1 = :newValue WHERE login_member = :login';
                $result = $this->sql($sql, [
                    'newValue' => NULL,
                    'login' => $login
                ]);

            } elseif ($row['login_share_booklist_2'] == $loginFriend) {
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_2 = :newValue WHERE login_member = :login';
                $result = $this->sql($sql, [
                    'newValue' => NULL,
                    'login' => $login
                ]);
            } elseif ($row['login_share_booklist_3'] == $loginFriend) {
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_3 = :newValue WHERE login_member = :login';
                $result = $this->sql($sql, [
                    'newValue' => NULL,
                    'login' => $login
                ]);
            }

            $sql = 'SELECT email FROM members WHERE login = :loginFriend';
            $result = $this->sql($sql, [
                'loginFriend' => $loginFriend
            ]);
            $row = $result->fetch();

            $email = $row['email'];
            $subjectMail = 'Suppression de liste de partage';
            $bodyMail = 'Vous venez d\'être supprimé de la liste de partage de livres de ' . $login . '.';
            $this->sendEmail($email, $subjectMail, $bodyMail);
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
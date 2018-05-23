<?php
    namespace DAO;


    use Swift_SmtpTransport;
    use Swift_Mailer;
    use Swift_Message;

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
            $sql = 'SELECT id_member, author, title, ISBN FROM bookslist WHERE ISBN = :ISBN';
            $result = $this->sql($sql, [
                'ISBN' => $ISBN
            ]);
            $row = $result->fetch();

            if($row['id_member'] == $id){
                $message = 'Vous avez déjà enregistré cet ouvrage.';
            } else {
                $message = 'Vous n\'avez pas ou n\'avez pas encore enregistré cet ouvrage.';
            }

            /* FONCTIONNE MAIS PROBLEME AU NIVEAU DE LA REDIRECTION*/

            //header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
            return $message;
        }

        public function reachFriend($login, $loginFriend){
            if($login == $loginFriend){
                echo 'Vous ne pouvez pas vous envoyer une demande d\'ami(e).';
            } else {
                $sql = 'SELECT login, email FROM members WHERE login = :login';
                $result = $this->sql($sql,[
                    'login' => $loginFriend
                ]);
                $row = $result->fetch();

                if($row){
                    $email = $row['email'];

                    //var_dump($email);
                    //die();
                    $this->sendEmail($email);
                    echo 'Une demande de partage de liste de livres a été envoyé.';
                } else{
                    echo 'Le pseudo indiqué ne correspond à aucun de nos membres.';
                }

                //return $message;
            }
        }


        public function sendEmail($email){
            // Create the Transport
            $smtp_host_ip = gethostbyname('smtp.gmail.com');
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                ->setUsername('aude.leissen.dev@gmail.com')
                ->setPassword('ggyuzcgqmxcbofvy');

            // Create theMailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message('Demande d\'autorisation de partage de liste de livres'))
                ->setFrom(['aude.leissen.dev@gmail.com' => 'Plop'])
                ->setTo($email)
                ->setBody('Here is the message itself');

            // Send the message
            $result = $mailer->send($message);
            var_dump($result);
            die();
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
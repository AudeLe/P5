<?php
    namespace DAO;

    class MemberDAO extends DAO{

        protected $commonFunctionalities;

        public function __construct(){
            $this->commonFunctionalities = new CommonFunctionalitiesDAO();
        }

        public function getMemberBookList(){
            $sql = 'SELECT id, author, title, summary, publishingYear, ISBN, nbPages FROM bookslist WHERE id_member = :id_member';
            $result = $this->sql($sql, [
                'id_member' => $_SESSION['id']
            ]);

            $memberBookList = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $memberBookList[$bookId] = $this->commonFunctionalities->buildObject($row);
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

                            $this->commonFunctionalities->sendEmail($email, $subjectMail, $bodyMail);
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

                        $this->commonFunctionalities->sendEmail($email, $subjectMail, $bodyMail);
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



        public function notShare($login, $loginFriend){
            $sql = 'SELECT email FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            $email = $row['email'];

            $subjectMail = 'Refus de votre demande de partage';
            $bodyMail = 'Votre demande de partage de la liste de livres de ' . $loginFriend . ' a été refusée.';

            $this->commonFunctionalities->sendEmail($email, $subjectMail, $bodyMail);

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

    }
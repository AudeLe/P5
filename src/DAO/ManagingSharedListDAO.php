<?php

    namespace DAO;

    use models\ManagingSharedLists;

    class ManagingSharedListDAO extends DAO{

        protected $commonFunctionalities;

        public function __construct(){
            $this->commonFunctionalities = new CommonFunctionalitiesDAO();
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

            $this->commonFunctionalities->sendEmail($email, $subjectMail, $bodyMail);

            return $message;
        }

        public function deleteSharedBooklist($login, $loginFriend){
            $sql = 'SELECT login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row['login_share_booklist_1'] == $loginFriend){
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_1 = :newValue WHERE login_member = :login';
                $this->sql($sql, [
                    'newValue' => NULL,
                    'login' => $login
                ]);

            } elseif ($row['login_share_booklist_2'] == $loginFriend) {
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_2 = :newValue WHERE login_member = :login';
                $this->sql($sql, [
                    'newValue' => NULL,
                    'login' => $login
                ]);
            } elseif ($row['login_share_booklist_3'] == $loginFriend) {
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_3 = :newValue WHERE login_member = :login';
                $this->sql($sql, [
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
            $this->commonFunctionalities->sendEmail($email, $subjectMail, $bodyMail);
        }

        public function stopSharingBooklist($login, $loginFriend){
            $sql = 'SELECT login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist WHERE login_member = :loginFriend';
            $result = $this->sql($sql, [
                'loginFriend' => $loginFriend
            ]);
            $row = $result->fetch();

            if($row['login_share_booklist_1'] == $login){
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_1 = :newValue WHERE login_member = :loginFriend';
                $this->sql($sql, [
                    'newValue' => NULL,
                    'loginFriend' => $loginFriend
                ]);

            } elseif ($row['login_share_booklist_2'] == $login) {
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_2 = :newValue WHERE login_member = :loginFriend';
                $this->sql($sql, [
                    'newValue' => NULL,
                    'loginFriend' => $loginFriend
                ]);
            } elseif ($row['login_share_booklist_3'] == $login) {
                $sql = 'UPDATE sharedbooklist SET login_share_booklist_3 = :newValue WHERE login_member = :loginFriend';
                $this->sql($sql, [
                    'newValue' => NULL,
                    'loginFriend' => $loginFriend
                ]);
            }


            $sql = 'SELECT email FROM members WHERE login = :loginFriend';
            $result = $this->sql($sql, [
                'loginFriend' => $loginFriend
            ]);
            $row = $result->fetch();

            $email = $row['email'];
            $subjectMail = 'Suppression de liste de partage';
            $bodyMail = $loginFriend . ' vient de supprimer votre accès à sa liste de livres.';
            $this->commonFunctionalities->sendEmail($email, $subjectMail, $bodyMail);

        }

        public function managingSharedLists(){
            $sql = 'SELECT * FROM sharedbooklist';
            $result = $this->sql($sql);

            $members = [];
            foreach ($result as $row){
                $memberId = $row['id'];
                $members[$memberId] = $this->buildObjectMembers($row);
            }
            return $members;
        }

        private function buildObjectMembers(array $row){
            $member = new ManagingSharedLists();
            $member->setId($row['id']);
            $member->setLoginMember($row['login_member']);
            $member->setLoginShareBooklist1($row['login_share_booklist_1']);
            $member->setLoginShareBooklist2($row['login_share_booklist_2']);
            $member->setLoginShareBooklist3($row['login_share_booklist_3']);
            return $member;
        }
    }
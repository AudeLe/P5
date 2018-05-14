<?php
    namespace DAO;

    use Symfony\Component\Validator\Validation;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    class ConnectionDAO extends DAO{

        // Validates the inputs before recording them into the db
        protected function validateInputsRegistration(){
            $validator = Validation::createValidator();
            $violations = $validator->validate('Bernhard', array(
               new Length(array('min' => 10)),
               new NotBlank(),
            ));

            if (0 !== count($violations)){
                // There are errors to show
                foreach($violations as $violation){
                    echo $violation->getMessage().'<br />';
                }
            }
        }

        // Register a new member
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor, $birthDateVisitor){
            $sql = 'SELECT login FROM members WHERE login = ?';
            $result = $this->sql($sql, [$login]);
            $row = $result -> fetch();

            // If the login is already used, display a message
            // Otherwise, allows the visitor to register
            if($row){
                echo 'Ce pseudo est déj) utilisé.';
            } else {
                // Check if the visitor has typed the same passwords
                if($passwordVisitor == $passwordVisitorCheck){

                    $passwordVisitorHashed = password_hash($passwordVisitor, PASSWORD_DEFAULT);

                    $sql = 'INSERT INTO members(login, password, email, birthDate, registrationDate) VALUES (:login, :password, :email, :birthDate, NOW())';
                    $result = $this->sql($sql, [
                        'login' => $login,
                        'email' => $emailVisitor,
                        'birthDate' => $birthDateVisitor,
                        'password' => $passwordVisitorHashed
                    ]);
                } else {
                    echo 'Vous n\'avez pas saisi les mêmes mots de passe.';
                }
            }
        }

        // Allows a member to connect to his/her personal space
        public function connection($loginConnection, $passwordVisitorConnection){

            $sql = 'SELECT id, password, status FROM members WHERE login = ?';
            $result = $this->sql($sql, [$loginConnection]);
            $row = $result -> fetch();

            // Verfies if the login is in the database
            if($row){
                $checkPassword = password_verify($passwordVisitorConnection, $row['password']);

                // And if the password typed is the right one
                if($checkPassword == true){
                    // Charging the credentials of the session
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['login'] = $row['login'];
                    $_SESSION['status'] = $row['status'];

                    // Regarding the status of the member, the redirection is different
                    if($row['status'] == 'admin'){
                        header('Location: ../public/index.php?action='); /* ACTION A DETERMINER */
                    } else {
                        header('Location: ../public/index.php?action='); /* ACTION A DETERMINER */
                    }
                } else {
                    echo 'Mauvais identifiant ou mot de passe.';
                }
            } else {
                echo 'Mauvais identifiant ou mot de passe.';
            }
        }

        // Allows the member/admin connected to log out
        public function logOut(){
            $_SESSION = array();
            session_destroy();
        }
    }
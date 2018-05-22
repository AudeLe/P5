<?php
    namespace models;

    use Symfony\Component\Validator\Constraints\Date;
    use Symfony\Component\Validator\Constraints\IdenticalTo;
    use Symfony\Component\Validator\Validation;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\Regex;
    use Symfony\Component\Validator\Constraints\Email;

    class RegistrationForm{
        private $login;
        private $passwordVisitor;
        private $passwordVisitorCheck;
        private $emailVisitor;
        //private $birthDateVisitor;

        // Validates the inputs before recording them into the db

        public function __construct()
        {
            $this->validator = Validation::createValidator();
        }

        public function checkLogin($login)
        {
            $violations = $this->validator->validate($login, array(
                new NotBlank(),
                new Length(array('min' => 2, 'max' => 25))
            ));

            $this->check($violations);
        }

        public function checkPassword($passwordVisitor, $passwordVisitorCheck){
            $violations = $this->validator->validate($passwordVisitor, array(
                new NotBlank(),
                new Regex(array(
                    'pattern' => '/^(?=.*\d)(?=.*[!@#$%^&*;?])(?=.*[a-z])(?=.*[A-Z]).{8,20}$/'
                )),
                new IdenticalTo($passwordVisitorCheck)
            ));

            $this->check($violations);
        }

        /*public function checkPasswordConfirmation($passwordVisitorCheck, $passwordVisitor){
            $violations = $this->validator->validate($passwordVisitorCheck, array(
                new NotBlank(),
                new Regex(array(
                    'pattern' => '/^(?=.*\d)(?=.*[!@#$%^&*;?])(?=.*[a-z])(?=.*[A-Z]).{8,20}$/'
                )),
                new IdenticalTo($passwordVisitor)
            ));

            $this->check($violations);
        }*/

        public function checkEmail($emailVisitor){
            $violations = $this->validator->validate($emailVisitor, array(
               new NotBlank(),
               new Email()
            ));

            $this->check($violations);
        }

        /*public function checkBirthDate($birthDateVisitor){
            $violations = $this->validator->validate($birthDateVisitor, array(
               new NotBlank(),
               new Regex(array(
                       'pattern' => '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/'
               ))
            ));

            $this->check($violations);
        }*/

        private function check($violations){
            if (0 !== count($violations)) {
                // There are errors to show
                foreach ($violations as $violation) {
                    return $violation->getMessage() . '<br />';
                }
            }
        }

    }
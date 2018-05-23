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

        // Validates the inputs before recording them into the db
        public function __construct()
        {
            $this->validator = Validation::createValidator();
        }

        public function checkLogin($login)
        {
            $violations = $this->validator->validate($login, [
                new NotBlank(),
                new Length([
                    'min' => 2,
                    'max' => 25,
                    'minMessage' => 'Votre pseudo doit être composé de 2 caractères minimum et de 25 caractères au maximum.',
                    'maxMessage' => 'Votre pseudo doit être composé de 2 caractères minimum et de 25 caractères au maximum.'
                ])
            ]);

            $errors = $this->check($violations);

            return $errors;

        }

        public function checkPassword($passwordVisitor){
            $violations = $this->validator->validate($passwordVisitor, [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^(?=.*\d)(?=.*[!@#$%^&*;?])(?=.*[a-z])(?=.*[A-Z]).{8,20}$/',
                    'message' => 'Votre mot de passe doit comporter entre 8 et 20 charactères. Il doit est composé d\'au moins un caractère spécial (!@#$%^&*;?), d\'un chiffre, d\'une minuscule et d\'une majuscule.',
                ])
            ]);

            $errors =$this->check($violations);

            return $errors;
        }

        public function checkPasswordConfirmation($passwordVisitorCheck, $passwordVisitor){
            $violations = $this->validator->validate($passwordVisitorCheck, array(
                new NotBlank(),
                new IdenticalTo([
                    'value' => $passwordVisitor,
                    'message' => 'Vous n\'avez pas entré le même mot de passe.'
                ])
            ));

            $errors = $this->check($violations);

            return $errors;
        }

        public function checkEmail($emailVisitor){
            $violations = $this->validator->validate($emailVisitor, array(
               new NotBlank(),
               new Email(['message' => 'Email invalide.'])
            ));

            $errors = $this->check($violations);

            return $errors;
        }

        private function check($violations){
            if (0 !== count($violations)) {
                // There are errors to show
                foreach ($violations as $violation) {
                    return $violation->getMessage() . '<br />';
                }
            }
        }

    }
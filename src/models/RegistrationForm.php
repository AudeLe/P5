<?php
    namespace models;

    use Symfony\Component\Validator\Validation;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\RegexValidator;
    use Symfony\Component\Validator\Constraints\EmailValidator;

    class RegistrationForm{

        // Validates the inputs before recording them into the db
        protected function validateInputsRegistration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor, $birthDateVisitor){
            //$registration = [$login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor, $birthDateVisitor];

            $validator = Validation::createValidator();

            $violations = $validator->validate($login, array(
                new NotBlank(),
                new Length(array('min' => 2, 'max' <= 25))
            ));

            $violations = $validator->validate($passwordVisitor, array(
                new NotBlank(),
                new RegexValidator(array(
                    'pattern' => '#(?=.*\d)(?=.*[!@#$%^&*;?])(?=.*[a-z])(?=.*[A-Z]).{8,20}#'
                ))
            ));

            if (0 !== count($violations)){
                // There are errors to show
                foreach($violations as $violation){
                    echo $violation->getMessage().'<br />';
                }
            }
        }
    }
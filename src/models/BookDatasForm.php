<?php
    namespace models;

    use Symfony\Component\Validator\Validation;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\Regex;

    class BookDatasForm{

        public function __construct(){

            $this->validator = Validation::createValidator();

        }

        public function inputNotBlank($input){
            $violations = $this->validator->validate($input,[
                new NotBlank()
            ]);

            $errors = $this->check($violations);

            return $errors;
        }

        public function checkISBN($ISBN){
            $violations = $this->validator->validate($ISBN,[
                new NotBlank(),
                new Length([
                    'min' => 10,
                    'max' => 13,
                    'minMessage' => 'L\'ISBN doit comporter 10 ou 13 caractères.',
                    'maxMessage' => 'L\'ISBN doit comporter 10 ou 13 caractères.'
                ])
            ]);

            $errors = $this->check($violations);

            return $errors;
        }

        public function checkYear($publishingYear){
            $violations = $this->validator->validate($publishingYear, [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^([0-2])([0-9]{3})$/',
                    'message' => 'Veuillez entrer uniquement l\'année de publication.'
                ])
            ]);

            $errors = $this->check($violations);

            return $errors;
        }

        private function check($violations){
            if(0 !== count($violations)){
                // There are errors to display
                foreach ($violations as $violation){
                    return $violation->getMessage() . '<br />';
                }
            }
        }
    }
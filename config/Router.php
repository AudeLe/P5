<?php

    namespace config;

    use controllers\BackController;
    use controllers\BookController;
    use controllers\FrontController;

    use Exception;

    class Router {

        private $frontController;

        public function __construct(){
            $this->backController = new BackController();
            $this->bookController = new BookController();
            $this->frontController = new FrontController();
        }

        public function requestRouter(){

            try{
                if(isset($_GET['action'])){
                    /* ----- CONNECTION RELATED ----- */

                    // Registration on the website
                    if($_GET['action'] == 'registration'){
                        if(!empty($_POST['login']) && !empty($_POST['passwordVisitor']) && !empty($_POST['passwordVisitorCheck']) && !empty($_POST['emailVisitor'])){
                            $this->backController->registration($_POST['login'], $_POST['passwordVisitor'], $_POST['passwordVisitorCheck'], $_POST['emailVisitor']);
                        } else {
                            throw new Exception('Impossible d\'enregistrer vos informations.');
                        }
                    }

                    // Connection on the website
                    elseif($_GET['action'] == 'connection'){
                        if(!empty($_POST['loginConnection']) && !empty($_POST['passwordVisitorConnection'])){
                            $this->backController->connection($_POST['loginConnection'], $_POST['passwordVisitorConnection']);
                        } else {
                            throw new Exception('Impossible de vous identifier.');
                        }
                    }

                    // Log out of the website
                    elseif ($_GET['action'] == 'logOut'){
                        $this->backController->logOut();
                    }

                    // Access the member profile page
                    elseif($_GET['action'] == 'memberProfile'){
                        if(isset($_GET['login'])){
                            $this->backController->memberProfile($_GET['login']);
                        } else {
                            throw new Exception('Veuillez vous connecter afin d\'accéder à vos commentaires.');
                        }
                    }


                    /* ----- BOOK DATAS -----*/
                    elseif ($_GET['action'] == 'registerBookDatas'){
                        if(!empty($_POST['bookTitle']) && !empty($_POST['bookAuthors']) && !empty($_POST['bookPublishedDate']) && !empty($_POST['bookDescription']) && !empty($_POST['bookISBN']) && !empty($_POST['bookNbPages'])){
                            $this->bookController->registerBookDatas($_POST['bookTitle'], $_POST['bookAuthors'], $_POST['bookPublishedDate'], $_POST['bookDescription'], $_POST['bookISBN'], $_POST['bookNbPages']);
                        } else {
                            throw new Exception('Veuillez remplir tous les champs.');
                        }

                    }

                } else{
                    // Default action
                    $this->frontController->welcome();
                }
            } catch(Exception $e){
                echo 'Erreur : ' . $e->getMessage();
            }
        }
    }

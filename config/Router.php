<?php

    namespace config;

    use controllers\FrontController;
    use controllers\BackController;
    use Exception;

    class Router {

        private $frontController;

        public function __construct(){
            $this->frontController = new FrontController();
            $this->backController = new BackController();
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

                    elseif($_GET['action'] == 'memberProfile'){
                        if(isset($_GET['login'])){
                            $this->backController->memberProfile($_GET['login']);
                        } else {
                            throw new Exception('Veuillez vous connecter afin d\'accéder à vos commentaires.');
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

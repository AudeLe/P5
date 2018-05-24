<?php

    namespace config;

    use controllers\BackController;
    use controllers\BookController;
    use controllers\Controller;
    use controllers\FrontController;

    use Exception;

    class Router {

        private $frontController;
        private $backController;
        private $bookController;

        //protected $twig;

        public function __construct(){
            $this->backController = new BackController();
            $this->bookController = new BookController();
            $this->controller = new Controller();
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

                    // Verify the credentials before allowing the user to modify them
                    elseif($_GET['action'] == 'verifyInformations'){
                        if(!empty($_POST['verifyLogin']) && !empty($_POST['verifyPassword'])){
                            $this->backController->verifyInformations($_POST['verifyLogin'], $_POST['verifyPassword']);
                        } else {
                            throw new Exception('Veuillez confirmer vos identifiants afin de pouvoir les modifier.');
                        }
                    }

                    // Modify the login
                    elseif($_GET['action'] == 'editLogin'){
                        if(!empty($_POST['editLogin'])){
                            $this->backController->editLogin($_POST['login']);
                        } else {
                            throw new Exception('Impossible de modifier votre pseudonyme.');
                        }
                    }

                    // Modify the password
                    elseif($_GET['action'] == 'editPassword'){
                        if(!empty($_POST['editPassword']) && !empty($_POST['confirmEditPassword'])){
                            $this->backController->editPassword($_POST['editPassword'], $_POST['confirmEditPassword']);
                        } else {
                            throw new Exception('Veuillez indiquer le même mot de passe');
                        }
                    }

                    // Deletion of the account
                    elseif($_GET['action'] == 'deleteAccount'){
                        if(isset($_GET['id']) && !empty($_POST['loginDelete']) && !empty($_POST['passwordDelete'])){
                            $this->backController->deleteAccount($_GET['id'], $_POST['loginDelete'], $_POST['passwordDelete']);
                        } else {
                            throw new Exception('Impossible de supprimer votre compte');
                        }
                    }

                    // Access to the page allowing to reach a friend
                    elseif($_GET['action'] == 'friendsPage'){
                        $this->backController->friendsPage();
                    }

                    // Ask to access the booklist of someone else
                    elseif($_GET['action'] == 'reachFriend'){
                        if(isset($_GET['login'])){
                            if(!empty($_POST['loginFriend'])){
                                $this->backController->reachFriend($_GET['login'], $_POST['loginFriend']);
                            } else {
                                throw new Exception('Impossible de contacter la personne indiquée.');
                            }
                        } else {
                            throw new Exception('Impossible de vous identifier.');
                        }

                    }

                    elseif ($_GET['action'] == 'accountPage'){
                        $this->backController->accountPage();
                    }

                    elseif($_GET['action'] == 'deleteAccountPage'){
                        $this->backController->deleteAccountPage();
                    }

                    elseif($_GET['action'] == 'getMemberBookList'){
                        if(isset($_GET['login'])){
                            $this->backController->getMemberBookList($_GET['login']);
                        } else{
                            throw new Exception('Impossible de récupérer votre liste de livres.');
                        }

                    }

                    /* ----- BOOK DATAS -----*/
                    // Record the datas of the selected book
                    elseif ($_GET['action'] == 'registerBookDatas'){
                        if(isset($_GET['id'])){
                            if(!empty($_POST['bookTitle']) && !empty($_POST['bookAuthors']) && !empty($_POST['bookPublishedDate']) && !empty($_POST['bookDescription']) && !empty($_POST['bookISBN']) && !empty($_POST['bookNbPages'])){
                                $this->bookController->registerBookDatas($_GET['id'], $_POST['bookTitle'], $_POST['bookAuthors'], $_POST['bookPublishedDate'], $_POST['bookDescription'], $_POST['bookISBN'], $_POST['bookNbPages']);
                            } else {
                                throw new Exception('Veuillez remplir tous les champs.');
                            }
                        } else {
                            throw new Exception('Impossible de vous identifier.');
                        }

                    }

                    // Access to the page to search a book
                    elseif($_GET['action'] == 'searchBookPage'){
                        $this->backController->searchBookPage();
                    }

                    // Search if the user already has the book
                    elseif($_GET['action'] == 'searchBook'){
                        if(isset($_GET['id']) && !empty($_POST['ISBNSearch'])){
                            $this->bookController->searchBook($_GET['id'], $_POST['ISBNSearch']);
                        } else {
                            throw new Exception('Impossible de savoir si vous avez déjà cet ouvrage.');
                        }
                    }

                    // Delete the book
                    elseif($_GET['action'] == 'deleteBook') {
                        if (isset($_GET['bookId'])) {
                            $this->bookController->deleteBook($_GET['bookId']);
                        } else {
                            throw new Exception('Impossible de supprimer cet ouvrage de votre liste.');
                        }
                    }

                } else{
                    // Default action
                    $this->frontController->welcome();
                }
            } catch(Exception $e){

                $errorMessage = $e->getMessage();

                $this->controller->errorManagement($errorMessage);
            }
        }
    }

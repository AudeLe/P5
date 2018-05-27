<?php

    namespace config;

    use controllers\BackController;
    use controllers\BookController;
    use controllers\Controller;
    use controllers\FrontController;

    use Exception;

    class Router {

        private $backController;
        private $bookController;
        private $controller;
        private $frontController;

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

                    /* ----- REGISTRATION ----- */

                    // Registration on the website
                    if($_GET['action'] == 'registration'){
                        if(!empty($_POST['login']) && !empty($_POST['passwordVisitor']) && !empty($_POST['passwordVisitorCheck']) && !empty($_POST['emailVisitor'])){
                            $this->backController->registration($_POST['login'], $_POST['passwordVisitor'], $_POST['passwordVisitorCheck'], $_POST['emailVisitor']);
                        } else {
                            throw new Exception('Impossible d\'enregistrer vos informations.');
                        }
                    }

                    // Indicates that an email has been sent to confirm the registration
                    elseif($_GET['action'] == 'confirmRegistrationPage'){
                        if(isset($_GET['login'])){
                            $this->backController->confirmRegistrationPage($_GET['login']);
                        } else {
                            throw new Exception('Impossible de vous rediriger vers la page permettant la confirmation de votre inscriptio.');
                        }
                    }

                    // Display the page indicating that the registration has been made
                    elseif($_GET['action'] == 'confirmRegistration'){
                        if(isset($_GET['login'])){
                            $this->backController->confirmRegistration($_GET['login']);
                        } else {
                            throw new Exception('Impossible de confirmer votre inscription.');
                        }
                    }

                    // Erase the registration
                    elseif($_GET['action'] == 'refuseRegistration'){
                        if(isset($_GET['login'])){
                            $this->backController->refuseRegistration($_GET['login']);
                        } else {
                            throw new Exception('Impossible d\'enregistrer votre refus d\'inscription pour l\'instant.');
                        }
                    }

                    // Indicates that the registration has not been confirmed yet
                    elseif ($_GET['action'] == 'awaitingRegistrationConfirmation'){
                        if(isset($_GET['login'])){
                            $this->backController->awaitingRegistrationConfirmation($_GET['login']);
                        } else {
                            throw new Exception('Impossible de vous rediriger vers la page demandée. Veuillez confirmer votre inscription.');
                        }
                    }

                    /* ----- CONNECTION TO/LOG OUT FROM THE WEBSITE -----*/

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


                /* ----- ACCESS PERSONAL PAGES -----*/

                    // Access the member profile page
                    elseif($_GET['action'] == 'memberProfile'){
                        if(isset($_GET['login'])){
                            $this->backController->memberProfile($_GET['login']);
                        } else {
                            throw new Exception('Veuillez vous connecter afin d\'accéder à votre page personnelle.');
                        }
                    }

                    // Access the admin profile page
                    elseif($_GET['action'] == 'adminProfile'){
                        if(isset($_GET['login'])){
                            $this->backController->adminProfile($_GET['login']);
                        } else {
                            throw new Exception('Veuillez vous connecter afin d\'accéder à la page d\'administration.');
                        }
                    }

                    // Access the page allowing to register a book
                    elseif($_GET['action'] == 'registerBookPage'){
                        if(isset($_GET['login'])){
                            $this->backController->registerBookPage($_GET['login']);
                        } else {
                            throw new Exception('Impossible d\'accéder à la page permettant l\'enregistrement de livres.');
                        }
                    }

                    // Access the page displaying the app statistics
                    elseif($_GET['action'] == 'statisticsPage'){
                        $this->backController->statisticsApp();
                    }

                    // Access to the page allowing to reach a friend
                    elseif($_GET['action'] == 'friendsPage'){
                        if(isset($_GET['login'])){
                            $this->backController->friendsPage($_GET['login']);
                        } else {
                            throw new Exception('Impossible de vous identifier afin d\'accéder à la page demandée.');
                        }

                    }

                    // Access to the account informations page
                    elseif ($_GET['action'] == 'accountPage'){
                        if(isset($_GET['login'])){
                            $this->backController->accountPage($_GET['login']);
                        } else {
                            throw new Exception('Impossible d\'accéder à cette page.');
                        }

                    }

                    // Access to the deletion of the account page
                    elseif($_GET['action'] == 'deleteAccountPage'){
                        if(isset($_GET['login'])){
                            $this->backController->deleteAccountPage($_GET['login']);
                        } else {
                            throw new Exception('Impossible d\'accéder à cette page.');
                        }
                    }

                    // Access to the page to search a book
                    elseif($_GET['action'] == 'searchBookPage'){
                        $this->backController->searchBookPage();
                    }

                    // Access the page to search if a friend already has a book
                    elseif($_GET['action'] == 'searchBookFriendPage'){
                        if(isset($_GET['login'])){
                            $this->backController->searchBookFriendPage($_GET['login']);
                        } else {
                            throw new Exception('Impossible de vous identifier.');
                        }
                    }



                /* ----- MODIFICATION/DELECTION ACCOUNT ----- */

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
                            throw new Exception('Impossible de modifier le mot de passe.');
                        }
                    }

                    // Modify the mail
                    elseif($_GET['action'] == 'editMail'){
                        if(!empty($_POST['editMail']) && !empty($_POST['confirmEditMail'])){
                            $this->backController->editMail($_POST['editMail'], $_POST['confirmEditMail']);
                        } else {
                            throw new Exception('Impossible d\'enregistrer la modification de votre email.');
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


                /* ----- PERSONAL DATAS/SEARCH ON THEIR OWN DATAS -----*/

                    // Display the books registered by the member
                    elseif($_GET['action'] == 'getMemberBookList'){
                        if(isset($_GET['login'])){
                            $this->backController->getMemberBookList($_GET['login']);
                        } else{
                            throw new Exception('Impossible de récupérer votre liste de livres.');
                        }

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

                    elseif($_GET['action'] == 'editBookDatas'){
                        if(isset($_GET['bookId'])){
                            $this->bookController->editBookDatas($_GET['bookId']);
                        } else {
                            throw new Exception('Impossible de modifier cet ouvrage.');
                        }
                    }

                    elseif($_GET['action'] == 'registerEditBookDatas'){
                        if(isset($_GET['bookId'])){
                            if(!empty($_POST['editTitle']) && !empty($_POST['editAuthor']) && !empty($_POST['editPublishingYear']) && !empty($_POST['editSummary']) && !empty($_POST['editISBN']) && !empty($_POST['editNbPages'])){
                                $this->bookController->registerEditBookDatas($_GET['bookId'], $_POST['editTitle'], $_POST['editAuthor'], $_POST['editPublishingYear'], $_POST['editSummary'], $_POST['editISBN'], $_POST['editNbPages']);
                            } else {
                                throw new Exception('Tous les champs ne sont pas remplis.');
                            }
                        } else {
                            throw new Exception('Impossible d\'enregistrer vos modifications.');
                        }
                    }


                /* ----- INTERACTION WITH OTHER MEMBERS -----*/

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

                    // Ask the authorization to share a book list
                    elseif($_GET['action'] == 'shareBookList'){
                        if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                            $this->backController->shareBookList($_GET['login'], $_GET['loginFriend']);
                        } else {
                            throw new Exception('Impossible d\'accéder à la page de demande de partage de livres.');
                        }
                    }

                    // Confirm the sharing of the booklist
                    elseif($_GET['action'] == 'shareBookListWithFriend'){
                        if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                            $this->backController->shareBookListWithFriend($_GET['login'], $_GET['loginFriend']);
                        } else {
                            throw new Exception('Impossible d\'enregistrer votre souhait de partager votre liste de livres.');
                        }
                    }

                    // Refuse to share the booklist
                    elseif($_GET['action'] == 'notShare'){
                        if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                            $this->backController->notShare($_GET['login'], $_GET['loginFriend']);
                        } else {
                            throw new Exception('Impossible d\'enregistrer votre soujait de ne pas partager votre liste de livres.');
                        }
                    }

                    // Verifies if the person selected already has this book or not
                    elseif($_GET['action'] == 'checkBookFriend'){
                        if(isset($_GET['login'])){
                            if(!empty($_POST['checkBookFriendISBN']) && !empty($_POST['checkBookFriend'])){
                                $this->bookController->checkBookFriend($_GET['login'], $_POST['checkBookFriendISBN'], $_POST['checkBookFriend']);
                            } else {
                                throw new Exception('Impossible de vérifier si la personne a déjà cet ouvrage ou non.');
                            }
                        } else {
                            throw new Exception('Impossible de vous identifier.');
                        }
                    }

                    // Allows to delete a shared booklist
                    elseif($_GET['action'] == 'deleteSharedBooklist'){
                        if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                            $this->backController->deleteSharedBooklist($_GET['login'], $_GET['loginFriend']);
                        } else {
                            throw new Exception('Impossible de supprimer cette personne de votre cercle d\'ami(e)s.');
                        }
                    }

                    // Allows the users to stop sharing their booklist with someone
                    elseif($_GET['action'] == 'stopSharingBooklist'){
                        if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                            $this->backController->stopSharingBooklist($_GET['login'], $_GET['loginFriend']);
                        } else {
                            throw new Exception('Impossible de supprimer le partage de votre liste de livres.');
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

                    // Display all the informations of the selected book
                    elseif($_GET['action'] == 'displayBook'){
                        if(isset($_GET['bookId'])){
                            $this->bookController->displayBook($_GET['bookId']);
                        } else {
                            throw new Exception('Impossible d\'afficher cet ouvrage.');
                        }
                    }


                /* ----- CONTACT ADMIN -----*/

                    // Display the page to access the contact form
                    elseif($_GET['action'] == 'contactForm'){
                        $this->backController->contactForm();
                    }

                    // Send the message to the admin
                    elseif($_GET['action'] == 'contactAdmin'){
                        if(!empty($_POST['loginSeeker']) && !empty($_POST['emailSeeker']) && !empty($_POST['subjectMail']) && !empty($_POST['bodyMail'])){
                            $this->backController->contactAdmin($_POST['loginSeeker'], $_POST['emailSeeker'], $_POST['subjectMail'], $_POST['bodyMail']);
                        } else {
                            throw new Exception('Impossible d\'envoyer votre message à l\'administrateur.');
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

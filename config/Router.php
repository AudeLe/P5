<?php

    namespace config;

    use controllers\AccountController;
    use controllers\BackController;
    use controllers\BookController;
    use controllers\ConnectionController;
    use controllers\Controller;
    use controllers\FrontController;
    use controllers\PageController;
    use controllers\PersonalSpaceController;

    use Exception;

    class Router {

        private $accountController;
        private $backController;
        private $bookController;
        private $connectionController;
        private $controller;
        private $frontController;
        private $pageController;
        private $personalSpaceController;

        public function __construct(){

            $this->accountController = new AccountController();
            $this->backController = new BackController();
            $this->bookController = new BookController();
            $this->connectionController = new ConnectionController();
            $this->controller = new Controller();
            $this->frontController = new FrontController();
            $this->pageController = new PageController();
            $this->personalSpaceController = new PersonalSpaceController();

        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        public function requestRouter(){
            /**
             *
             */
            try{
                if(isset($_GET['action'])){
                    $action = $_GET['action'];
                    switch($action){
                    /* ===== CONNECTION RELATED ===== */

                        /* ===== REGISTRATION ===== */

                        // Registration on the website
                        case 'registration':
                            if(!empty($_POST['login']) && !empty($_POST['passwordVisitor']) && !empty($_POST['passwordVisitorCheck']) && !empty($_POST['emailVisitor'])){
                                $this->connectionController->registration($_POST['login'], $_POST['passwordVisitor'], $_POST['passwordVisitorCheck'], $_POST['emailVisitor']);
                            } else {
                                throw new Exception('Impossible d\'enregistrer vos informations.');
                            }
                            break;


                        // Indicates that an email has been sent to confirm the registration
                        case 'confirmRegistrationPage':
                            if(isset($_GET['login'])){
                                $this->pageController->confirmRegistrationPage($_GET['login']);
                            } else {
                                throw new Exception('Impossible de vous rediriger vers la page permettant la confirmation de votre inscriptio.');
                            }
                            break;

                        // Display the page indicating that the registration has been made
                        case 'confirmRegistration':
                            if(isset($_GET['login'])){
                                $this->connectionController->confirmRegistration($_GET['login']);
                            } else {
                                throw new Exception('Impossible de confirmer votre inscription.');
                            }
                            break;

                        // Erase the registration
                        case 'refuseRegistration':
                            if(isset($_GET['login'])){
                                $this->connectionController->refuseRegistration($_GET['login']);
                            } else {
                                throw new Exception('Impossible d\'enregistrer votre refus d\'inscription pour l\'instant.');
                            }
                            break;

                        // Indicates that the registration has not been confirmed yet
                        case ($action == 'awaitingRegistrationConfirmation'):
                            if(isset($_GET['login'])){
                                $this->pageController->awaitingRegistrationConfirmation($_GET['login']);
                            } else {
                                throw new Exception('Impossible de vous rediriger vers la page demandée. Veuillez confirmer votre inscription.');
                            }
                            break;

                        /* ===== CONNECTION TO/LOG OUT FROM THE WEBSITE ===== */

                        // Connection on the website
                        case 'connection':
                            if(!empty($_POST['loginConnection']) && !empty($_POST['passwordVisitorConnection'])){
                                $this->connectionController->connection($_POST['loginConnection'], $_POST['passwordVisitorConnection']);
                            } else {
                                throw new Exception('Impossible de vous identifier.');
                            }
                            break;

                        // Log out of the website
                        case ($action == 'logOut'):
                            $this->connectionController->logOut();
                            break;

                        // Access to the log out page
                        case ($action == 'logOutPage'):
                            $this->connectionController->logOutPage();
                            break;


                    /*===== ACCESS PERSONAL PAGES ===== */

                        // Access the member profile page
                        case  'memberProfile':
                            if(isset($_GET['login'])){
                                $this->backController->memberProfile($_GET['login']);
                            } else {
                                throw new Exception('Veuillez vous connecter afin d\'accéder à votre page personnelle.');
                            }
                            break;

                        // Access the admin profile page
                        case 'adminProfile':
                            if(isset($_GET['login'])){
                                $this->backController->adminProfile($_GET['login']);
                            } else {
                                throw new Exception('Veuillez vous connecter afin d\'accéder à la page d\'administration.');
                            }
                            break;

                        // Access the page allowing to register a book
                        case 'registerBookPage':
                            if(isset($_GET['login'])){
                                $this->pageController->registerBookPage();
                            } else {
                                throw new Exception('Impossible d\'accéder à la page permettant l\'enregistrement de livres.');
                            }
                            break;

                        // Access the page displaying the app statistics
                        case 'statisticsPage':
                            $this->backController->statisticsApp();
                            break;

                        // Access to the page allowing to reach a friend
                        case 'friendsPage':
                            if(isset($_GET['login'])){
                                $this->personalSpaceController->friendsPage($_GET['login']);
                            } else {
                                throw new Exception('Impossible de vous identifier afin d\'accéder à la page demandée.');
                            }
                            break;

                        // Access to the account informations page
                        case 'accountPage':
                            if(isset($_GET['login'])){
                                $this->accountController->accountPage($_GET['login']);
                            } else {
                                throw new Exception('Impossible d\'accéder à cette page.');
                            }
                            break;

                        // Access to the deletion of the account page
                        case 'deleteAccountPage':
                            if(isset($_GET['login'])){
                                $this->accountController->deleteAccountPage($_GET['login']);
                            } else {
                                throw new Exception('Impossible d\'accéder à cette page.');
                            }
                            break;

                        // Access to the page to search a book
                        case 'searchBookPage':
                            $this->pageController->searchBookPage();
                            break;

                        // Access the page to search if a friend already has a book
                        case 'searchBookFriendPage':
                            if(isset($_GET['login'])){
                                $this->personalSpaceController->searchBookFriendPage($_GET['login']);
                            } else {
                                throw new Exception('Impossible de vous identifier.');
                            }
                            break;



                    /* ===== MODIFICATION/DELETION ACCOUNT ===== */

                        // Verify the credentials before allowing the user to modify them
                        case 'verifyInformations':
                            if(!empty($_POST['verifyLogin']) && !empty($_POST['verifyPassword'])){
                                $this->accountController->verifyInformations($_POST['verifyLogin'], $_POST['verifyPassword']);
                            } else {
                                throw new Exception('Veuillez confirmer vos identifiants afin de pouvoir les modifier.');
                            }
                            break;

                        // Modify the login
                        case 'editLogin':
                            if(!empty($_POST['editLogin'])){
                                $this->accountController->editLogin($_POST['login']);
                            } else {
                                throw new Exception('Impossible de modifier votre pseudonyme.');
                            }
                            break;

                        // Modify the password
                        case 'editPassword':
                            if(!empty($_POST['editPassword']) && !empty($_POST['confirmEditPassword'])){
                                $this->accountController->editPassword($_POST['editPassword'], $_POST['confirmEditPassword']);
                            } else {
                                throw new Exception('Impossible de modifier le mot de passe.');
                            }
                            break;

                        // Modify the mail
                        case 'editMail':
                            if(!empty($_POST['editMail']) && !empty($_POST['confirmEditMail'])){
                                $this->accountController->editMail($_POST['editMail'], $_POST['confirmEditMail']);
                            } else {
                                throw new Exception('Impossible d\'enregistrer la modification de votre email.');
                            }
                            break;

                        // Deletion of the account
                        case 'deleteAccount':
                            if(isset($_GET['id']) && !empty($_POST['loginDelete']) && !empty($_POST['passwordDelete'])){
                                $this->accountController->deleteAccount($_GET['id'], $_POST['loginDelete'], $_POST['passwordDelete']);
                            } else {
                                throw new Exception('Impossible de supprimer votre compte');
                            }
                            break;


                    /* ===== PERSONAL DATAS/SEARCH ON THEIR OWN DATAS ===== */

                        // Display the books registered by the member
                        case 'getMemberBookList':
                            if(isset($_GET['login'])){
                                $this->personalSpaceController->getMemberBookList();
                            } else{
                                throw new Exception('Impossible de récupérer votre liste de livres.');
                            }
                            break;

                        // Search if the user already has the book
                        case 'searchBook':
                            if(isset($_GET['id']) && !empty($_POST['ISBNSearch'])){
                                $this->bookController->searchBook($_GET['id'], $_POST['ISBNSearch']);
                            } else {
                                throw new Exception('Impossible de savoir si vous avez déjà cet ouvrage.');
                            }
                            break;

                        // Delete the book
                        case 'deleteBook':
                            if (isset($_GET['bookId'])) {
                                $this->bookController->deleteBook($_GET['bookId']);
                            } else {
                                throw new Exception('Impossible de supprimer cet ouvrage de votre liste.');
                            }
                            break;

                        case 'editBookDatas':
                            if(isset($_GET['bookId'])){
                                $this->bookController->editBookDatas($_GET['bookId']);
                            } else {
                                throw new Exception('Impossible de modifier cet ouvrage.');
                            }
                            break;

                        case 'registerEditBookDatas':
                            if(isset($_GET['bookId'])){
                                if(!empty($_POST['editTitle']) && !empty($_POST['editAuthor']) && !empty($_POST['editPublishingYear']) && !empty($_POST['editSummary']) && !empty($_POST['editISBN']) && !empty($_POST['editNbPages'])){
                                    $this->bookController->registerEditBookDatas($_GET['bookId'], $_POST['editTitle'], $_POST['editAuthor'], $_POST['editPublishingYear'], $_POST['editSummary'], $_POST['editISBN'], $_POST['editNbPages']);
                                } else {
                                    throw new Exception('Tous les champs ne sont pas remplis.');
                                }
                            } else {
                                throw new Exception('Impossible d\'enregistrer vos modifications.');
                            }
                            break;


                    /* ===== INTERACTION WITH OTHER MEMBERS ===== */

                        // Ask to access the booklist of someone else
                        case 'reachFriend':
                            if(isset($_GET['login'])){
                                if(!empty($_POST['loginFriend'])){
                                    $this->personalSpaceController->reachFriend($_GET['login'], $_POST['loginFriend']);
                                } else {
                                    throw new Exception('Impossible de contacter la personne indiquée.');
                                }
                            } else {
                                throw new Exception('Impossible de vous identifier.');
                            }
                            break;

                        // Ask the authorization to share a book list
                        case 'shareBookList':
                            if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                                $this->personalSpaceController->shareBookList($_GET['login'], $_GET['loginFriend']);
                            } else {
                                throw new Exception('Impossible d\'accéder à la page de demande de partage de livres.');
                            }
                            break;

                        // Confirm the sharing of the booklist
                        case 'shareBookListWithFriend':
                            if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                                $this->personalSpaceController->shareBookListWithFriend($_GET['login'], $_GET['loginFriend']);
                            } else {
                                throw new Exception('Impossible d\'enregistrer votre souhait de partager votre liste de livres.');
                            }
                            break;

                        // Refuse to share the booklist
                        case 'notShare':
                            if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                                $this->personalSpaceController->notShare($_GET['login'], $_GET['loginFriend']);
                            } else {
                                throw new Exception('Impossible d\'enregistrer votre soujait de ne pas partager votre liste de livres.');
                            }
                            break;

                        // Verifies if the person selected already has this book or not
                        case 'checkBookFriend':
                            if(isset($_GET['login'])){
                                if(!empty($_POST['checkBookFriendISBN']) && !empty($_POST['checkBookFriend'])){
                                    $this->bookController->checkBookFriend($_GET['login'], $_POST['checkBookFriendISBN'], $_POST['checkBookFriend']);
                                } else {
                                    throw new Exception('Impossible de vérifier si la personne a déjà cet ouvrage ou non.');
                                }
                            } else {
                                throw new Exception('Impossible de vous identifier.');
                            }
                            break;

                        // Allows to delete a shared booklist
                        case 'deleteSharedBooklist':
                            if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                                $this->personalSpaceController->deleteSharedBooklist($_GET['login'], $_GET['loginFriend']);
                            } else {
                                throw new Exception('Impossible de supprimer cette personne de votre cercle d\'ami(e)s.');
                            }
                            break;

                        // Allows the users to stop sharing their booklist with someone
                        case 'stopSharingBooklist':
                            if(isset($_GET['login']) && isset($_GET['loginFriend'])){
                                $this->personalSpaceController->stopSharingBooklist($_GET['login'], $_GET['loginFriend']);
                            } else {
                                throw new Exception('Impossible de supprimer le partage de votre liste de livres.');
                            }
                            break;



                    /* ===== BOOK DATAS ===== */

                        // Record the datas of the selected book
                        case 'registerBookDatas':
                            if(isset($_GET['id'])){
                                if(!empty($_POST['bookTitle']) && !empty($_POST['bookAuthors']) && !empty($_POST['bookPublishedDate']) && !empty($_POST['bookDescription']) && !empty($_POST['bookISBN']) && !empty($_POST['bookNbPages'])){
                                    $this->bookController->registerBookDatas($_GET['id'], $_POST['bookTitle'], $_POST['bookAuthors'], $_POST['bookPublishedDate'], $_POST['bookDescription'], $_POST['bookISBN'], $_POST['bookNbPages']);
                                } else {
                                    throw new Exception('Veuillez remplir tous les champs.');
                                }
                            } else {
                                throw new Exception('Impossible de vous identifier.');
                            }
                            break;

                        // Display all the informations of the selected book
                        case 'displayBook':
                            if(isset($_GET['bookId'])){
                                $this->bookController->displayBook($_GET['bookId']);
                            } else {
                                throw new Exception('Impossible d\'afficher cet ouvrage.');
                            }
                            break;


                    /* ===== CONTACT ADMIN ===== */

                        // Display the page to access the contact form
                        case 'contactForm':
                            $this->frontController->contactForm();
                            break;

                        // Send the message to the admin
                        case 'contactAdmin':
                            if(!empty($_POST['loginSeeker']) && !empty($_POST['emailSeeker']) && !empty($_POST['subjectMail']) && !empty($_POST['bodyMail'])){
                                $this->frontController->contactAdmin($_POST['loginSeeker'], $_POST['emailSeeker'], $_POST['subjectMail'], $_POST['bodyMail']);
                            } else {
                                throw new Exception('Impossible d\'envoyer votre message à l\'administrateur.');
                            }
                            break;

                    /* ===== LEGAL MENTIONS ===== */
                        case 'legalMentionsPage':
                            $this->frontController->legalMentionsPage();
                            break;

                    /* ===== DISPLAY ERRORS WITHIN THE MODEL ===== */
                        // Display the error detected within the model
                        case 'error':
                            if(isset($_GET['errorMessage'])){
                                $this->controller->errorManagement($_GET['errorMessage']);
                            } else {
                                throw new Exception('Impossible d\'afficher l\'erreur.');
                            }
                            break;

                        default:
                            // Default action
                            $this->frontController->welcome();
                    }
                } else{
                    $this->frontController->welcome();
                }

            } catch(Exception $e){

                $errorMessage = $e->getMessage();

                $this->controller->errorManagement($errorMessage);
            }
        }
    }

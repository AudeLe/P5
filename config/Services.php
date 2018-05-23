<?php

    namespace config;

    use \Twig_Loader_Filesystem;
    use \Twig_Environment;
    use \Twig_Extension_Debug;

    class Services{

        protected $twig;

        public function runTwig(){
            //Twig configuration
            $loader = new Twig_Loader_Filesystem('../templates');
            $this->twig = new Twig_Environment($loader, array(
                'cache' => false,
                'debug' => true
            ));

            // Accessing the $_SESSION datas through Twig
            $this->twig->addGlobal('session', $_SESSION);

            // Adding te debug extension
            $this->twig->addExtension(new Twig_Extension_Debug());
        }
    }
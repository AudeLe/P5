<?php
    namespace DAO;

    use PDO;
    use Exception;

    abstract class DAO {

        private $connection;

        /**
         * @return PDO
         */
        private function checkConnection(){
            // Verify if the connection is already established or not
            // And 'call' getConnection()
            if($this->connection == null){
                return $this->getConnection();
            }

            // If the connection is already established, it's returned.
            // No need to start a new connection
            return $this->connection;
        }

        /**
         * @return PDO
         */
        private function getConnection(){
            // Trying to connect to the database
            try{
                $this->connection = new PDO(DB_HOST, DB_USER, DB_PASS);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return $this->connection;
            }

            // An error is raised if the connection doesn't go through
            catch(Exception $errorConnection){
                die('Erreur de connexion : ' . $errorConnection->getMessage());
            }
        }

        /**
         * @param $sql
         * @param null $parameters
         * @return bool|\PDOStatement
         */
        protected function sql($sql, $parameters = null){
            if($parameters){
                $result = $this->checkConnection()->prepare($sql);
                $result->execute($parameters);

                return $result;
            } else {
                $result = $this->checkConnection()->query($sql);

                return $result;
            }
        }
    }
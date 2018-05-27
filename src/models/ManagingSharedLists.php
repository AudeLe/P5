<?php

    namespace models;

    class ManagingSharedLists{

        private $id;
        private $login_member;
        private $login_share_booklist_1;
        private $login_share_booklist_2;
        private $login_share_booklist_3;

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getLoginMember()
        {
            return $this->login_member;
        }

        /**
         * @param mixed $login_member
         */
        public function setLoginMember($login_member)
        {
            $this->login_member = $login_member;
        }

        /**
         * @return mixed
         */
        public function getLoginShareBooklist1()
        {
            return $this->login_share_booklist_1;
        }

        /**
         * @param mixed $login_share_booklist_1
         */
        public function setLoginShareBooklist1($login_share_booklist_1)
        {
            $this->login_share_booklist_1 = $login_share_booklist_1;
        }

        /**
         * @return mixed
         */
        public function getLoginShareBooklist2()
        {
            return $this->login_share_booklist_2;
        }

        /**
         * @param mixed $login_share_booklist_2
         */
        public function setLoginShareBooklist2($login_share_booklist_2)
        {
            $this->login_share_booklist_2 = $login_share_booklist_2;
        }

        /**
         * @return mixed
         */
        public function getLoginShareBooklist3()
        {
            return $this->login_share_booklist_3;
        }

        /**
         * @param mixed $login_share_booklist_3
         */
        public function setLoginShareBooklist3($login_share_booklist_3)
        {
            $this->login_share_booklist_3 = $login_share_booklist_3;
        }
    }
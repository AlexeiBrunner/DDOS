<?php


    namespace Api;


    use Api\Middleware\DDOS;

    class ExampleApi extends Api
    {
        use DDOS;

        public function run()
        {
            return $this->ipCheck($this->ip);
            return parent::run();
        }

        protected function indexAction()
        {
            // TODO: Implement indexAction() method.
        }

        protected function viewAction()
        {
            // TODO: Implement viewAction() method.
        }

        protected function createAction()
        {
            // TODO: Implement createAction() method.
        }

        protected function updateAction()
        {
            // TODO: Implement updateAction() method.
        }

        protected function deleteAction()
        {
            // TODO: Implement deleteAction() method.
        }
    }
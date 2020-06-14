<?php

    namespace App\Models;

    use App\Databases\DB;

    class Model
    {
        public $table;
        protected $fields = [];
        protected $properties = [];
        private $connection = null;

        private $where = '';
        private $limit = ' LIMIT 1';

        public function __construct()
        {
            $this->connection = (Db::getInstance())->getConnection();
            $this->getColumnName();
        }

        public function __set($name, $value)
        {
            $this->properties[$name] = $value;
        }

        public function __get($name)
        {

            if (in_array($name, $this->fields)) {
                return $this->properties[$name];
            }
        }

        private function getColumnName()
        {
            foreach ($this->connection->query("SHOW COLUMNS FROM {$this->table}") as $column) {
                    $this->fields[] = $column['Field'];
            }

            return $this->fields;
        }

        public function save()
        {
            if ($this->id)
                $this->update();
            else
                $this->insert();
        }

        private function insert()
        {
            $bindParamNames = [];

            foreach ($this->fields as $field) {
                $bindParamNames[] = ":" . $field;
            }

            $fields = implode(', ', $this->fields);
            $bindParamNamesString = implode(', ', $bindParamNames);

            $stmt = $this->connection->prepare("INSERT INTO " . $this->table . " (" . $fields . ") VALUES (" . $bindParamNamesString . ")");
            foreach ($bindParamNames as $param) {
                $key = str_replace(':', '', $param);
                $stmt->bindParam($param, $this->properties[$key]);
            }

            $stmt->execute();
        }

        private function update()
        {
            $bindParamNames = [];

            foreach ($this->fields as $field) {
                if ($field !== 'id')
                    $bindParamNames[$field] = $field . "=:" . $field;
            }
            $stmt = $this->connection->prepare("UPDATE " . $this->table . " SET " . implode(',', $bindParamNames) . " WHERE id =:id");
            foreach ($this->fields as $param) {
                $key = str_replace(':', '', $param);
                $stmt->bindParam($param, $this->properties[$key]);
            }

            $stmt->execute();

        }

        public function where(array $cond)
        {
            $params = [];
            foreach ($cond as $key => $value) {
                $params[] = $key . "='" . $value ."'";
            }
            $this->where = " WHERE " . implode(' AND', $params);

            return $this;
        }

        public function load()
        {
            $stmt = $this->connection->prepare("SELECT * FROM " . $this->table . $this->where . $this->limit);
            $stmt->execute();
            $row = $stmt->fetch();
            foreach ($this->fields as $field) {
                $this->{$field} = $row[$field];
            }

        }

    }
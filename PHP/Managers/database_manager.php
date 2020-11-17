<?php

class DatabaseManager extends Singleton {

    protected static $instance;

    private $DB;

    public function getConnection() {
        if ($this->DB == null) {
            $this->DB = mysqli_connect(DB_HOST, DB_ID, DB_PW, DB_NAME);
        }
        if (mysqli_connect_errno()) {
            printOutput(-2, "DB CONNECTION FAILURE : ".mysqli_connect_error());
            exit();
        }
        return $this->DB;
    }

}

?>
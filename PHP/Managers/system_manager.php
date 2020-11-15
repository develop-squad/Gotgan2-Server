<?php

class SystemManager extends Singleton {

    const SYSTEM_ON = "SYSTEM_ON";
    const SYSTEM_LOGIN = "SYSTEM_LOGIN";
    const SYSTEM_RENT = "SYSTEM_RENT";
    const SYSTEM_MESSAGE = "SYSTEM_MESSAGE";

    private $systemOn;
    private $systemLogin;
    private $systemRent;
    private $systemMessage;

    public function checkSystemOn() : bool {

    }

    public function checkSystemLogin() : bool {

    }

    public function checkSystemRent() : bool {

    }

    public function checkSystemMessage() : bool {

    }

    public function switchSystemOn($systemSwitch) {

    }

    public function switchSystemLogin($loginSwitch) {

    }

    public function switchSystemRent($rentSwitch) {

    }

    public function switchSystemMessage($messageSwitch) {

    }

    public function sendGlobalMail($message) {

    }

    public function sendPersonalMail($userIndex, $message) {

    }

}

?>
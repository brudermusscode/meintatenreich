<?php

$admin = new Admin($pdo, $my);

class Admin extends Login
{
    public object $pdo;
    public object $my;

    public function __construct(object $pdo, object $my)
    {
        $this->pdo = $pdo;
        $this->my = $my;
    }

    public function isAdmin()
    {

        if ($this->isAuthed($this->pdo)) {
            if ($this->my->admin == '1') {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    public function getDump($dump)
    {

        switch ($dump) {
            case "session":
                return print_r($_SESSION);
                break;
            case "request":
                return print_r($_REQUEST);
                break;
            default:
                return false;
                break;
        }
    }
}

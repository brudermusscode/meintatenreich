<?php

$admin = new Admin($pdo, $my, $main);

class Admin extends Login
{
    public object $pdo;
    public object $my;
    public array $main;

    public function __construct(object $pdo, object $my, array $main)
    {
        $this->pdo = $pdo;
        $this->my = $my;
        $this->main = $main;
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

    public function isMaintenance()
    {
        if ($this->main["maintenance"] == "1") {
            if ($this->isAuthed($this->pdo)) {
                if ($this->my->admin == '1') {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
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

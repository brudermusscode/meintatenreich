<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


function clean($string)
{
    $string = str_replace(' ', '', $string);
    return preg_replace('/[^0-9\,]/', '', $string);
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (
    isset($_REQUEST['id'], $_REQUEST['date'], $_REQUEST['start'], $_REQUEST['end'])
    && is_numeric($_REQUEST['id'])
    && strlen($_REQUEST['date']) > 0
    && strlen($_REQUEST['start']) > 0
    && strlen($_REQUEST['end'])  > 0
    && $loggedIn
    && $user['admin'] === '1'
) {

    $couid = htmlspecialchars($_REQUEST['id']);
    $date  = htmlspecialchars($_REQUEST['date']);
    $start = htmlspecialchars($_REQUEST['start']);
    $end   = htmlspecialchars($_REQUEST['end']);

    // CHECK IF COURSE EXISTS
    $sel = $c->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->bind_param('s', $couid);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        if (validateDate($date)) {

            if (validateDate($start, $format = 'H:i') && validateDate($end, $format = 'H:i')) {

                $ins = $c->prepare("
                        INSERT INTO courses_dates (couid, date, start, end, timestamp) VALUES (?,?,?,?,?)
                    ");
                $ins->bind_param('sssss', $couid, $date, $start, $end, $timestamp);
                $ins->execute();


                if ($ins) {
                    $c->commit();
                    $c->close();
                    exit('success');
                } else {
                    $c->rollback();
                    $c->close();
                    exit('0');
                }
            } else {
                exit('3');
            }
        } else {
            exit('2');
        }
    } else {
        exit('1');
    }
} else {
    exit('0');
}

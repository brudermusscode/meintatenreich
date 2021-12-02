<?php

$shop = new Shop;

class Shop
{
    public static function validateName($nameString)
    {
        if (!preg_match('/[^a-z\-\s]/i', $nameString)) {
            return true;
        }

        return false;
    }

    public static function validatePassword($str)
    {
        if (!preg_match('/[^a-z0-9=.,_+*#~?!&%$§\-]/i', $str)) {
            return true;
        }

        return false;
    }

    public static function removeFileType($str)
    {
        $str = preg_replace("/(.+)\.php$/", "$1", $str);
        return $str;
    }

    public static function tryExecute($stmt, $params, $connection, $commit = false)
    {

        // TO DO: add support for several queries
        // -----
        // check if passed $params is of array type
        if (!is_array($params)) {
            $params = [$params];
        }

        try {

            // try executing the statement
            $stmt->execute($params);

            // store error information
            $return = [
                "status" => true,
                "commit" => $commit,
                "lastInsertId" => $connection->lastInsertId()
            ];

            // objectify error information array
            $return = (object) $return;

            // commit changes if true
            if ($commit) {
                $connection->commit();
            }

            // return the object back to the script
            return $return;
        } catch (PDOException $e) {

            // catch error information
            $return = [
                "status" => false,
                "message" => $e->getMessage(),
                "code" => $e->getCode()
            ];

            // rollback data and return error information
            if ($commit) {
                $connection->rollback();
            }

            return $return;
        }

        return false;
    }

    public static function trySendMail($address, $subject, $body, $header)
    {
        try {

            mail($address, $subject, $body, $header);
            return true;
        } catch (PDOException $e) {

            $errorInformation = [
                "status" => false,
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ];

            return $errorInformation;
        }

        return false;
    }
}

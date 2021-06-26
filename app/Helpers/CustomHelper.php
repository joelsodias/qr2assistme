<?php

namespace App\Helpers;

class CustomHelper
{
    /**
     * Create a record structure with one field ($fieldname) for all entries in $array. 
     * Only single dimention arrays are expected in $array parameter. 
     * Example: array('a','b','c')
     *
     * @param array $array
     * @param string $fieldname
     *
     * @return array
     */
    public static function array2records(array $array, string $fieldname)
    {
        $records = array_reduce($array, function ($result, $item) {
            $result[][$result[0]] = $item;
            return $result;
        }, array($fieldname));
        unset($records[0]);
        return $records;
    }


    public static function casttoclass($class, $object)
    {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }


    public static  function shortUid($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public static function currentURI()
    {

        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function forceSSL()
    {
        $calleduri = \CustomHelper::currentURI();
        $uri = new \CodeIgniter\HTTP\URI($calleduri);
        if ($uri->getScheme() == "http") {
            $newuri = str_replace("http", "https", $calleduri);
            return redirect()->to($newuri);
        }
    }

    public static function str_ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        return $length > 0 ? substr($haystack, -$length) === $needle : true;
    }

    public static function time_ago($datetime, $concat = false)
    {
        $timestring = '';

        if ($datetime) {
            $timestamp = (is_string($datetime)) ? strtotime($datetime) : $datetime;
            $now = new \DateTime();
            $result = $now->getTimestamp() - $timestamp;
            $years = floor($result / 31536000);
            $days = floor(($result - ($years * 31536000)) / 86400);
            $months = floor(($result - ($years * 31536000)) / 86400 / 30);
            $hours = floor(($result - ($years * 31536000 + $days * 86400)) / 3600);
            $minutes = floor(($result - ($years * 31536000 + $days * 86400 + $hours * 3600)) / 60);

            if ($years > 0) {
                $timestring .= $years . ' anos';
            }
            if ($months > 0 && (strlen($timestring) == 0 || $concat)) {
                $timestring .= $months . ' meses';
            }
            if ($days > 0 && (strlen($timestring) == 0 || $concat)) {
                $timestring .= $days . 'd';
            }
            if ($hours > 0 && (strlen($timestring) == 0 || $concat)) {
                $timestring .= $hours . 'h';
            }
            if ($minutes > 0 && (strlen($timestring) == 0 || $concat)) {
                $timestring .= $minutes . ' min';
            }

            # Optional
            # $timestring .= ' ago';
        }
        return $timestring;
    }

    # Formats a time stamp into a date string
    public static function format_timestamp($timestamp, $time = false)
    {
        if ($timestamp == 0) {
            return null;
        } else if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        if ($time) {
            $date_string = Date('H:i:s jS M Y', $timestamp);
        } else {
            $date_string = Date('jS M Y', $timestamp);
        }
        return $date_string;
    }

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function  array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = CustomHelper::array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

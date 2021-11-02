<?php
include "config.php";

mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=0");
// api data
// Blockonmics API stuff
$apikey = "api_key_here";
$url = "https://www.blockonomics.co/api/";

$options = array(
    'http' => array(
        'header'  => 'Authorization: Bearer ' . $apikey,
        'method'  => 'POST',
        'content' => '',
        'ignore_errors' => true
    )
);

// api functions
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateAddress()
{
    global $apikey;
    global $url;
    $options = array(
        'http' => array(
            'header'  => 'Authorization: Bearer ' . $apikey,
            'method'  => 'POST',
            'content' => '',
            'ignore_errors' => true
        )
    );

    $context = stream_context_create($options);
    $contents = file_get_contents($url . "new_address", false, $context);
    $object = json_decode($contents);

    // Check if address was generated successfully
    if (isset($object->address)) {
        $address = $object->address;
    } else {
        // Show any possible errors
        $address = $http_response_header[0] . "\n" . $contents;
    }
    return $address;
}

function getBTCPrice($currency)
{
    $content = file_get_contents("https://www.blockonomics.co/api/price?currency=" . $currency);
    $content = json_decode($content);
    $price = $content->price;
    return $price;
}

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function USDtoBTC($amount)
{
    $price = getBTCPrice("USD");
    return $amount / $price;
}

function BTCtoUSD($amount)
{
    $price = getBTCPrice("USD");
    return $amount * $price;
}

/**
 * Print array
 */
function arrayPrint($value)
{
    echo "<pre>", print_r($value, true), "</pre>";
}

/**
 * Print array
 * and die
 */
function dd($value){
    echo "<pre>", print_r($value, true), "</pre>";
    die();
}


/**
 * @param $sql
 * @param array $data
 * @return false|mysqli_stmt
 */
function executeQuery($sql, $data = []){
    global $connection;
    if ($stmt = $connection->prepare($sql)) {
        if (!empty($data)) {
            $values = array_values($data);
            $types = str_repeat('s', count($values));
            $stmt->bind_param($types, ...$values);
        }
        $stmt->execute();
    } else {
        $stmt = var_dump($connection->error);
    }
    return $stmt;
}


function selectAll($table, $conditions = [])
{
    global $connection;
    $sql = "SELECT * FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllRand($table, $conditions = [])
{
    global $connection;
    $sql = "SELECT * FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " ORDER BY rand()";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllRandLimit($table, $conditions = [], $row, $range)
{
    global $connection;
    $sql = "SELECT * FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " ORDER BY rand() LIMIT $row, $range";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllRandDesc($table, $conditions = [])
{
    global $connection;
    $sql = "SELECT * FROM(SELECT * FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " ORDER BY rand())T1 ORDER BY id DESC";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllDistinct($column, $table, $conditions = [])
{
    global $connection;
    $sql = "SELECT DISTINCT ($column) FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllDistinctOrder($column, $table, $conditions = [], $order)
{
    global $connection;
    $sql = "SELECT DISTINCT ($column) FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " ORDER BY $column $order";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllDistinctOrderWith($column, $table, $conditions = [], $orderParam, $order)
{
    global $connection;
    $sql = "SELECT DISTINCT ($column) FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " ORDER BY $orderParam $order";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllDistinctOrderWith2($column, $table, $conditions = [], $orderParam, $order)
{
    global $connection;
    $sql = "SELECT DISTINCT $column, product_type, date FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " ORDER BY $orderParam $order LIMIT 10";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function selectAllLike($table, $conditions = [], $conditions2 = [], $row, $range)
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $p = 0;
    foreach ($conditions as $key => $value) {
        if ($p === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $p++;
    }

    $sql = $sql . " AND (";
    $i = 0;
    foreach ($conditions2 as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " $key LIKE '%" . $value . "%'";
        } else {
        $sql = $sql . " AND $key LIKE '%" . $value . "%'";
        }
        $i++;
    }

    $sql = $sql . ") ORDER BY rand() LIMIT $row, $range";
    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function selectAllLikeOR($table, $conditions = [], $conditions2 = [], $row, $range)
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $p = 0;
    foreach ($conditions as $key => $value) {
        if ($p === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $p++;
    }

    $sql = $sql . " AND (";
    $i = 0;
    foreach ($conditions2 as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " $key LIKE '%" . $value . "%'";
        } else {
        $sql = $sql . " OR $key LIKE '%" . $value . "%'";
        }
        $i++;
    }

    $sql = $sql . ") ORDER BY rand() LIMIT $row, $range";
    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function selectAllLikeNoRange($table, $conditions = [], $conditions2 = [])
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $p = 0;
    foreach ($conditions as $key => $value) {
        if ($p === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $p++;
    }

    $sql = $sql . " AND (";
    $i = 0;
    foreach ($conditions2 as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " $key LIKE '%" . $value . "%'";
        } else {
        $sql = $sql . " OR $key LIKE '%" . $value . "%'";
        }
        $i++;
    }

    $sql = $sql . ") ORDER BY rand()";
    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function selectAllLike2($table, $conditions = [])
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " WHERE $key LIKE '%" . $value . "%'";
        } else {
            $sql = $sql . " AND $key LIKE '%" . $value . "%'";
        }
        $i++;
    }

    $result = mysqli_query($connection, $sql);
    return $result;
}


function selectAllWithOr($table, $conditions = [], $orField, $orValue)
{
    global $connection;
    $sql = "SELECT * FROM $table";
    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " OR " . $orField . "=" . $orValue;

        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}


function selectOne($table, $conditions)
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $i++;
    }

    $sql = $sql . " LIMIT 1";

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_assoc();
}

function selectOneRand($table, $conditions)
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $i++;
    }

    $sql = $sql . " ORDER BY rand() LIMIT 1";

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_assoc();
}

function selectOneRandDesc($table, $conditions)
{
    global $connection;
    $sql = "SELECT * FROM(SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $i++;
    }

    $sql = $sql . " ORDER BY rand())T1 ORDER BY ticket_count ASC LIMIT 1";

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_assoc();
}

function selectOneOr($table, $conditions)
{
    global $connection;
    $sql = "SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " OR $key=?";
        }
        $i++;
    }

    $sql = $sql . " LIMIT 1";

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_assoc();
}


function selectOneOrderByDescLimit($table, $column, $order_data)
{
    global $connection;
    $sql = "SELECT $column FROM $table";

    $sql = $sql . " ORDER BY $order_data DESC LIMIT 1";

    $stmt = executeQuery($sql);
    return $stmt->get_result()->fetch_assoc();
}


function selectAllWithOrder($table, $conditions, $orderCondition, $orderType)
{
    $orderType = strtoupper($orderType);
    $table = strtolower($table);
    global $connection;
    $sql = "SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " WHERE $key=?";
        } else {
            $sql = $sql . " AND $key=?";
        }
        $i++;
    }

    $sql = $sql . " ORDER BY $orderCondition $orderType";
    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function selectSpecificData($table, $pickConditions, $conditions = [])
{
    global $connection;
    $sql = "SELECT";
    $increamental = 0;
    foreach ($pickConditions as $value) {
        if ($increamental === 0) {
            $sql = $sql . " $value";
        } else {
            $sql = $sql . ", $value";
        }
        $increamental++;
    }
    $sql = $sql . " FROM $table";

    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }
    }

    $sql = $sql . " LIMIT 1";

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_assoc();
}

function selectAllSpecificData($table, $pickConditions, $conditions = [])
{
    global $connection;
    $sql = "SELECT";
    $increamental = 0;
    foreach ($pickConditions as $value) {
        if ($increamental === 0) {
            $sql = $sql . " $value";
        } else {
            $sql = $sql . ", $value";
        }
        $increamental++;
    }
    $sql = $sql . " FROM $table";

    if (empty($conditions)) {
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }
    }

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function update($table, $id, $conName, $data)
{
    $sql = "UPDATE $table SET ";
    $i = 0;
    foreach ($data as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " $key=?";
        } else {
            $sql = $sql . ", $key=?";
        }
        $i++;
    }

    $sql = $sql . " WHERE " . $conName . "=?";
    $data[$conName] = $id;
    $stmt = executeQuery($sql, $data);
    return $stmt->affected_rows;
}

function update2($table, $uniqueValue, $conName, $data)
{
    $sql = "UPDATE $table SET ";
    $i = 0;
    foreach ($data as $key => $value) {
        if ($i === 0) {
            $sql = $sql . " $key=?";
        } else {
            $sql = $sql . ", $key=?";
        }
        $i++;
    }

    $sql = $sql . " WHERE " . $conName . "=" . $uniqueValue;
    $stmt = executeQuery($sql, $data);
    return $stmt->affected_rows;
}

function delete($table, $id, $consName)
{
    $sql = "DELETE FROM $table WHERE " . $consName . "=?";
    $stmt = executeQuery($sql, [$consName => $id]);
    return $stmt->affected_rows;
}

function delete2($table, $id, $consName)
{
    $sql = "DELETE FROM $table WHERE " . $consName . "=? AND status = 0";
    $stmt = executeQuery($sql, [$consName => $id]);
    return $stmt->affected_rows;
}

function countRecords($table)
{
    global $connection;
    $sql = "SELECT COUNT(*) AS count FROM $table";
    $stmt = executeQuery($sql);
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'];
}

function sumRecordsWhere($sum, $table, $condition, $condition2)
{
    global $connection;
    $sql = "SELECT COUNT($sum) FROM $table WHERE product_name = '$condition' AND date = '$condition2'";
    $stmt = executeQuery($sql);
    $result = $stmt->get_result()->fetch_assoc();
    return $result["COUNT($sum)"];
}

function sumRecordsWhereSt($sum, $table, $condition, $condition2)
{
    global $connection;
    $sql = "SELECT COUNT($sum) FROM $table WHERE product_name = '$condition' AND status = '$condition2'";
    $stmt = executeQuery($sql);
    $result = $stmt->get_result()->fetch_assoc();
    return $result["COUNT($sum)"];
}

function sumRecordsWhere2($sum, $table, $condition, $condition2, $condition3)
{
    global $connection;
    $sql = "SELECT COUNT($sum) FROM $table WHERE product_name = '$condition' AND date = '$condition2'AND type = '$condition3'";
    $stmt = executeQuery($sql);
    $result = $stmt->get_result()->fetch_assoc();
    return $result["COUNT($sum)"];
}

function sumRecordsWhereOrder($sum, $table, $condition, $condition2, $order)
{
    global $connection;
    $sql = "SELECT COUNT($sum) FROM $table WHERE product_name = '$condition' AND date = '$condition2' ORDER BY $condition2 $order";
    $stmt = executeQuery($sql);
    $result = $stmt->get_result()->fetch_assoc();
    return $result["COUNT($sum)"];
}

// function to insert a new row into an arbitrary table, with the columns filled with the values 
// from an associative array and completely SQL-injection safe

function insert($table, $record)
{
    global $connection;
    $cols = array();
    $vals = array();
    foreach (array_keys($record) as $col) $cols[] = sprintf("`%s`", $col);
    foreach (array_values($record) as $val) $vals[] = sprintf("'%s'", mysqli_real_escape_string($connection, $val));

    mysqli_query($connection, sprintf("INSERT INTO `%s`(%s) VALUES(%s)", $table, implode(", ", $cols), implode(", ", $vals)));

    return mysqli_insert_id($connection);
}

// date functions to find individual components of date 
// and add or subtract from date
function getYear($date)
{
    $date = DateTime::createFromFormat("Y-m-d", $date);
    return $date->format("Y");
}

function getMonth($date)
{
    $date = DateTime::createFromFormat("Y-m-d", $date);
    return $date->format("m");
}

function getDay($date)
{
    $date = DateTime::createFromFormat("Y-m-d", $date);
    return $date->format("d");
}

function addYear($date, $period)
{
    $valueDate = date("Y-m-d", strtotime($date . "+$period year"));
    return $valueDate;
}

function addMonth($date, $period)
{
    $valueDate = date("Y-m-d", strtotime($date . "+$period month"));
    return $valueDate;
}

function addWeek($date, $period)
{
    $valueDate = date("Y-m-d", strtotime($date . "+$period week"));
    return $valueDate;
}

function addDay($date, $period)
{
    $valueDate = date("Y-m-d", strtotime($date . "+$period day"));
    return $valueDate;
}

function appendAccountNo($accountNo, $length)
{
    $appendedAccount = '******' . substr($accountNo, $length);
    return $appendedAccount;
}
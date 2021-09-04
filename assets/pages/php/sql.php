<?php
require_once('../../../config.php');
$server_host = $config['DB_HOST'];
$server_port = $config['DB_PORT'];
$server_username = $config['DB_USERNAME'];
$server_password = $config['DB_PASSWORD'];
$server_dbname = $config['DB_DATABASE'];
$hash_options = [
    'cost' => 10
];

/*function myCustomErrorHandler(int $errNo, string $errMsg, string $file, int $line)
{
    echo "<h1>Error occurred:</h1><br/>";
}

set_error_handler('myCustomErrorHandler');
*/

function empty_input_sign_up($user_username, $user_email, $user_account_type, $user_password, $user_password_repeat)
{
    if (empty($user_username) || empty($user_email) || empty($user_account_type) || empty($user_password) || empty($user_password_repeat)) {
        return true;
    } else {
        return false;
    }
}

function empty_input_sign_in($user_email, $user_password)
{
    if (empty($user_email) || empty($user_password)) {
        return true;
    } else {
        return false;
    }
}

function invalid_username($user_username)
{
    if (!preg_match("/^[a-zA-Z0-9]*$/", $user_username)) {
        return true;
    } else {
        return false;
    }
}

function invalid_email($user_email)
{
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function invalid_account_type($user_account_type)
{
    if ($user_account_type !== "client" && $user_account_type !== "specialist") {
        return true;
    } else {
        return false;
    }
}

function password_match($user_password, $user_password_repeat)
{
    if ($user_password === $user_password_repeat) {
        return true;
    } else {
        return false;
    }
}

function error_input_sign_up($conn, $user_username, $user_email)
{
    $sql_query = "select * from users where username = $1 or email = $2;";
    $stmt = pg_prepare($conn, "check_sign_up", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "check_sign_up", array($user_username, $user_email)) or die("Connection error");
    $error_username = false;
    $error_email = false;
    while ($row = pg_fetch_assoc($data)) {
        if ($row['username'] === $user_username) {
            $error_username = true;
        }
        if ($row['email'] === $user_email) {
            $error_email = true;
        }
    }
    if ($error_username and $error_email) {
        return "Invalid username and email";
    } else if ($error_username and !$error_email) {
        return "Invalid username";
    } else if (!$error_username and $error_email) {
        return "Invalid email";
    } else {
        return false;
    }
}

function server_connect()
{
    global $server_host, $server_port, $server_username, $server_password, $server_dbname;
    $connection_string = "host={$server_host} port={$server_port} dbname={$server_dbname} user={$server_username}
 password={$server_password}";
    $conn = pg_connect($connection_string) or die("<h3>Connection error</h3>");
    return $conn;
}

function sign_in($user_email, $user_password)
{
    $conn = server_connect();
    $sql_query = "select password, user_type from users where email = $1;";
    $stmt = pg_prepare($conn, "sign_in", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "sign_in", array($user_email)) or die("Connection error");
    pg_close($conn) or die("Connection error");
    if (pg_num_rows($data) == 0) {
        return false;
    }
    $row = pg_fetch_assoc($data);
    if (!password_verify($user_password, $row['password'])) {
        return false;
    }
    return $row['user_type'];
}

function invalid_specialist_place_of_operation($user_email)
{
    $conn = server_connect();
    $sql_query = "select specialists.place_of_operation from specialists, users where specialists.user_id = users.id 
    and users.email = $1;";
    $stmt = pg_prepare($conn, "check_specialist", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "check_specialist", array($user_email)) or die("Connection error");
    pg_close($conn) or die("Connection error");
    if (pg_num_rows($data) == 0) {
        return false;
    }
    $row = pg_fetch_assoc($data);
    if ($row['place_of_operation'] === null) {
        return true;
    }
    return false;
}

function invalid_polygons($data)
{
    if (empty($data) && !is_array($data)) {
        return true;
    }
    array_filter(array_keys($data), function ($element) {
        if (!is_int($element)) {
            return true;
        }
    });
    array_filter(array_values($data), function ($element) {
        if (!is_array($element)) {
            return true;
        }
        array_filter($element, function ($element_second) {
            if (!is_array($element_second)) {
                return true;
            }
            array_filter($element_second, function ($element_third) {
                if (!is_float($element_third) && !is_int($element_third)) {
                    return true;
                }
            });
        });
    });
    return false;
}

function create_user($user_username, $user_email, $user_account_type, $user_password)
{
    $conn = server_connect();
    if (!$error = error_input_sign_up($conn, $user_username, $user_email)) {
        global $hash_options;
        $user_password = password_hash($user_password, PASSWORD_BCRYPT, $hash_options);
        $sql_query = "insert into users (username, email, password, user_type) values ($1, $2, $3, $4);";
        $stmt = pg_prepare($conn, "sign_up", $sql_query) or die("Connection error");
        $result = pg_execute($conn, "sign_up", array($user_username, $user_email, $user_password, '{' . ($user_account_type) . '}')) or die("Connection error");
        $sql_query = "select id from users where username = $1;";
        $stmt = pg_prepare($conn, "sign_up_get_id", $sql_query) or die("Connection error");
        $result = pg_execute($conn, "sign_up_get_id", array($user_username)) or die("Connection error");
        $user_id = pg_fetch_assoc($result)['id'];
        if ($user_account_type === "client") {
            $sql_query = "insert into clients (user_id) values ($1);";
            $stmt = pg_prepare($conn, "sign_up_client", $sql_query) or die("Connection error");
            $result = pg_execute($conn, "sign_up_client", array($user_id)) or die("Connection error");
        } elseif ($user_account_type === "specialist") {
            $sql_query = "insert into specialists (user_id) values ($1);";
            $stmt = pg_prepare($conn, "sign_up_specialist", $sql_query) or die("Connection error");
            $result = pg_execute($conn, "sign_up_specialist", array($user_id)) or die("Connection error");
        } elseif ($user_account_type === "admin") {
            $sql_query = "insert into admins (user_id) values ($1);";
            $stmt = pg_prepare($conn, "sign_up_admin", $sql_query) or die("Connection error");
            $result = pg_execute($conn, "sign_up_admin", array($user_id)) or die("Connection error");
        }
        pg_close($conn) or die("Connection error");
        return true;
    } else {
        return $error;
    }
}

function update_specialist_place_of_operation($user_email, $data)
{
    $conn = server_connect();
    $sql_query = "update specialists set place_of_operation = $1 where user_id = (select id from users where email = $2);";
    $stmt = pg_prepare($conn, "update_place_of_operation", $sql_query) or die("Connection error");
    $result = pg_execute($conn, "update_place_of_operation", array($data, $user_email)) or die("Connection error");
    pg_close($conn) or die("Connection error");
}

function to_pg_array($data)
{
    settype($data, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($data as $temp) {
        if (is_array($temp)) {
            $result[] = to_pg_array($temp);
        } else {
            $temp = str_replace('"', '\\"', $temp); // escape double quote
            if (!is_numeric($temp)) // quote only non-numeric values
                $temp = '"' . $temp . '"';
            $result[] = $temp;
        }
    }
    return '(' . implode(",", $result) . ')'; // format
}

function create_order($client_email, $date_of_creation, $place_of_operation, $status, $expire_date, $video_type)
{
    $conn = server_connect();
    $sql_query = "insert into orders (client_id, date_of_creation, place_of_operation, status, expire_date, video_type) values ((select id from clients where user_id = (select id from users where email = $1)), $2, $3, $4, $5, $6);";
    $stmt = pg_prepare($conn, "create_order", $sql_query) or die("Connection error");
    $result = pg_execute($conn, "create_order", array($client_email, $date_of_creation, $place_of_operation, $status, $expire_date, $video_type)) or die("Connection error");
    pg_close($conn) or die("Connection error");
}

function client_show_orders($client_email, $sql_offset)
{
    $conn = server_connect();
    $sql_query = "select id, date_of_creation, date_of_completion, status, expire_date, video_type from 
    orders where client_id = (select id from clients where user_id = (select id from users where email = $1)) and
    status != 'canceled' limit $2 offset $3;";
    $stmt = pg_prepare($conn, "client_show_orders", $sql_query) or die("Connection error");
    $RECORDS_PER_PAGE = 3;
    $data = pg_execute($conn, "client_show_orders", array(
        $client_email, $RECORDS_PER_PAGE,
        ($sql_offset * $RECORDS_PER_PAGE)
    )) or die("Connection error");
    pg_close($conn) or die("Connection error");
    if ($sql_offset != 0) {
        $id_it = $sql_offset * $RECORDS_PER_PAGE + 1;
    } else {
        $id_it = 1;
    }
    $pattern = "/[+](\w)*$/";
    while ($row = pg_fetch_assoc($data)) {
        $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
        $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
        $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
        if ($row['status'] === "waiting" || $row['status'] === "processing") {
            echo
            "<form action='orderInfo.php' method='post' id='form_" . $id_it . "'>";
        } else {
            echo
            "<form action='orderView.php' method='post' id='form_" . $id_it . "'>";
        }
        echo
        "<a href='#' onclick='document.getElementById(" . "\"form_" . $id_it . "\"" . ").submit();' class='text-decoration-none text-light'>
            <div class='row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv'>
                <div class='col border'>
                    <span class='fw-bold'>Id</span>
                    <hr class='m-0'>
                    <span>" . "<input name='order_info' type='text' class='d-none' value='" . $row['id'] . "'/>" . $id_it . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>VideoType</span>
                    <hr class='m-0'>
                    <span>" . $row["video_type"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Creation</span>
                    <hr class='m-0'>
                    <span>" . $row["date_of_creation"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Expire</span>
                    <hr class='m-0'>
                    <span>" . $row["expire_date"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Completion</span>
                    <hr class='m-0'>
                    <span>" . $row["date_of_completion"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Status</span>
                    <hr class='m-0'>
                    <span>" . $row["status"] . "</span>
                </div>
            </div>
        </a>
        </form>";
        $id_it += 1;
    }
}

function client_get_total_pages($client_email)
{
    $conn = server_connect();
    $sql_query = "select total_pages from clients where user_id = (select id from users where email = $1);";
    $stmt = pg_prepare($conn, "client_get_pages", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_get_pages", array($client_email)) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_assoc($data);
    return $row['total_pages'];
}

/*function client_get_total_orders($client_email)
{
    $conn = server_connect();
    $sql_query = "select count(*) from orders where client_id = (select id from clients where 
    user_id = (select id from users where email = $1)) and status != 'canceled';";
    $stmt = pg_prepare($conn, "client_get_orders", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_get_orders", array($client_email)) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    return $row[0];
}*/

function client_order_info($client_email, $order_id)
{
    $conn = server_connect();
    $sql_query = "select date_of_creation, date_of_completion, status, expire_date, video_type, 
    place_of_operation from orders where client_id = (select id from clients where 
    user_id = (select id from users where email = $1)) and id = $2 and status != 'canceled';";
    $stmt = pg_prepare($conn, "client_order_info", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_order_info", array($client_email, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $pattern = "/[+](\w)*$/";
    $row = pg_fetch_assoc($data);
    $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
    $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
    $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
    return array(
        "order_creation_date" => $row["date_of_creation"], "order_completion_date" => $row["date_of_completion"],
        "order_status" => $row["status"], "order_expire_date" => $row["expire_date"],
        "order_video_type" => $row["video_type"], "order_place_info" => $row["place_of_operation"]
    );
}

function client_invalid_current_order($order_id, $client_email)
{
    $conn = server_connect();
    $sql_query = "select exists(select id from orders where id = $1 and client_id = 
    (select id from clients where user_id = (select id from users where email = $2)) and
    status != 'canceled');";
    $stmt = pg_prepare($conn, "client_invalid_current_order", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "client_invalid_current_order",
        array($order_id, $client_email)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    if ($row[0] === 'f') {
        return true;
    }
    return false;
}

function client_completed_reported_declined_order_info($client_email, $order_id)
{
    $conn = server_connect();
    $sql_query = "select date_of_creation, date_of_completion, status, expire_date, video_type, 
    place_of_operation, video_source, rating, report_message from orders where client_id = (select id from clients where 
    user_id = (select id from users where email = $1)) and id = $2 and (status = 'completed' or
    status = 'reported' or status = 'declined');";
    $stmt = pg_prepare($conn, "client_completed_reported_declined_order_info", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_completed_reported_declined_order_info", array($client_email, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $pattern = "/[+](\w)*$/";
    $row = pg_fetch_assoc($data);
    $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
    $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
    $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
    return array(
        "order_creation_date" => $row["date_of_creation"], "order_completion_date" => $row["date_of_completion"],
        "order_status" => $row["status"], "order_expire_date" => $row["expire_date"],
        "order_video_type" => $row["video_type"], "order_place_info" => $row["place_of_operation"],
        "order_video_source" => $row["video_source"], "order_rating" => $row["rating"],
        "order_report_message" => $row["report_message"]
    );
}

function client_invalid_completed_reported_declined_order($order_id, $client_email)
{
    $conn = server_connect();
    $sql_query = "select exists(select id from orders where id = $1 and client_id = 
    (select id from clients where user_id = (select id from users where email = $2)) and
    (status = 'completed' or status = 'reported' or status = 'declined'));";
    $stmt = pg_prepare($conn, "client_invalid_completed_reported_declined_order", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "client_invalid_completed_reported_declined_order",
        array($order_id, $client_email)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    if ($row[0] === 'f') {
        return true;
    }
    return false;
}

function client_cancel_order($order_id)
{
    $conn = server_connect();
    $sql_query = "update orders set status = 'canceled' where id = $1;";
    $stmt = pg_prepare($conn, "client_cancel_order", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_cancel_order", array($order_id)) or
        die("Connection error");
    $sql_query = "delete from chat_rooms where order_id = $1;";
    $stmt = pg_prepare($conn, "client_cancel_order_1", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_cancel_order_1", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function client_rate_order($order_id, $star_rating)
{
    $conn = server_connect();
    $sql_query = "update orders set rating = $1 where id = $2;";
    $stmt = pg_prepare($conn, "client_rate_order", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_rate_order", array($star_rating, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function client_report_order($order_id, $report_message)
{
    $conn = server_connect();
    $sql_query = "update orders set report_message = $1, status = 'reported' where id = $2;";
    $stmt = pg_prepare($conn, "client_report_order", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "client_report_order", array($report_message, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function get_account_info($user_email)
{
    $conn = server_connect();
    $sql_query = "select username, user_type from users where email = $1;";
    $stmt = pg_prepare($conn, "get_account_info", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "get_account_info", array($user_email)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $pattern = array('/^\{/', '/\}$/', '/,/');
    $row = pg_fetch_assoc($data);
    $row["user_type"] = preg_replace($pattern, array('', '', ''), $row["user_type"]);
    return array(
        "username" => $row["username"], "user_type" => $row["user_type"]
    );
}

function change_password($user_email, $user_new_password)
{
    $conn = server_connect();
    global $hash_options;
    $user_new_password = password_hash($user_new_password, PASSWORD_BCRYPT, $hash_options);
    $sql_query = "update users set password = $1 where email = $2;";
    $stmt = pg_prepare($conn, "change_password", $sql_query) or die("Connection error");
    $result = pg_execute($conn, "change_password", array($user_new_password, $user_email)) or die("Connection error");
    pg_close($conn) or die("Connection error");
}

function to_array($polygon)
{
    $polygon = preg_replace(array('/{/', '/}/'), '', $polygon);
    $polygon = explode('","', $polygon);
    $polygon_convert = function ($arr) {
        $arr = preg_replace(array('/^\(\(/', '/\)\)$/', '/^"\(/', '/\)"$/'), array('(', ')', '', ''), $arr);
        $arr = explode('),(', $arr);
        $arr = preg_replace(array('/\(/', '/\)/'), array('', ''), $arr);
        $arr_convert = function ($sub_arr) {
            $sub_arr = explode(',', $sub_arr);
            $array_to_float = function ($sub_arr_value) {
                return floatval($sub_arr_value);
            };
            $sub_arr = array_map($array_to_float, $sub_arr);
            return $sub_arr;
        };
        $arr = array_map($arr_convert, $arr);
        return $arr;
    };
    $polygon = array_map($polygon_convert, $polygon);
    return $polygon;
}

function specialist_show_orders($sql_offset)
{
    $conn = server_connect();
    $sql_query = "select * from orders where status = 'waiting' limit $1 offset $2;";
    $stmt = pg_prepare($conn, "specialist_show_orders", $sql_query) or die("Connection error");
    $RECORDS_PER_PAGE = 4;
    $data = pg_execute(
        $conn,
        "specialist_show_orders",
        array($RECORDS_PER_PAGE, ($sql_offset * $RECORDS_PER_PAGE))
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    if ($sql_offset != 0) {
        $id_it = $sql_offset * $RECORDS_PER_PAGE + 1;
    } else {
        $id_it = 1;
    }
    $pattern = "/[+](\w)*$/";
    while ($row = pg_fetch_assoc($data)) {
        $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
        $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
        $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
        echo
        "<form action='orderInfo.php' method='post' id='form_" . $id_it . "'>
        <a href='#' onclick='document.getElementById(" . "\"form_" . $id_it . "\"" . ").submit();' class='text-decoration-none text-light'>
            <div class='row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv'>
                <div class='col border'>
                    <span class='fw-bold'>Id</span>
                    <hr class='m-0'>
                    <span>" . "<input name='order_info' type='text' class='d-none' value='" . $row['id'] . "'/>" . $id_it . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>VideoType</span>
                    <hr class='m-0'>
                    <span>" . $row["video_type"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Expire</span>
                    <hr class='m-0'>
                    <span>" . $row["expire_date"] . "</span>
                </div>
            </div>
        </a>
        </form>";
        $id_it += 1;
    }
}

function specialist_get_total_pages()
{
    $conn = server_connect();
    $sql_query = "select count(*) from orders where status = 'waiting';";
    $data = pg_query($conn, $sql_query) or die("Connection error");
    $row = pg_fetch_row($data);
    $RECORDS_PER_PAGE = 4;
    $total_pages = ceil($row[0] / $RECORDS_PER_PAGE);
    return $total_pages;
}

function specialist_invalid_order($order_id)
{
    $conn = server_connect();
    $sql_query = "select exists(select id from orders where id = $1 and status = 'waiting');";
    $stmt = pg_prepare($conn, "specialist_invalid_order", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "specialist_invalid_order",
        array($order_id)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    if ($row[0] === 'f') {
        return true;
    }
    return false;
}

function specialist_order_info($specialist_email, $order_id)
{
    $conn = server_connect();
    $sql_query = "select specialists.place_of_operation as specialist_place_of_operation, orders.expire_date, 
    orders.video_type, orders.place_of_operation from orders, specialists where 
    specialists.user_id = (select id from users where email = $1) and orders.id = $2;";
    $stmt = pg_prepare($conn, "specialist_order_info", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_order_info", array($specialist_email, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $pattern = "/[+](\w)*$/";
    $row = pg_fetch_assoc($data);
    $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
    return array(
        "order_expire_date" => $row["expire_date"], "order_video_type" => $row["video_type"],
        "order_place_info" => $row["place_of_operation"],
        "specialist_place_info" => $row["specialist_place_of_operation"]
    );
}

function specialist_get_current_total_pages($specialist_email)
{
    $conn = server_connect();
    $sql_query = "select count(*) from orders where status = 'processing' and specialist_id = 
    (select id from specialists where user_id = (select id from users where email = $1));";
    $stmt = pg_prepare($conn, "specialist_get_current_pages", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "specialist_get_current_pages",
        array($specialist_email)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    $RECORDS_PER_PAGE = 4;
    $total_pages = ceil($row[0] / $RECORDS_PER_PAGE);
    return $total_pages;
}

function specialist_show_current_orders($specialist_email, $sql_offset)
{
    $conn = server_connect();
    $sql_query = "select * from orders where specialist_id = (select id from specialists where user_id = 
    (select id from users where email = $1)) and status = 'processing' LIMIT $2 OFFSET $3;";
    $stmt = pg_prepare($conn, "specialist_show_current_orders", $sql_query) or die("Connection error");
    $RECORDS_PER_PAGE = 4;
    $data = pg_execute(
        $conn,
        "specialist_show_current_orders",
        array($specialist_email, $RECORDS_PER_PAGE, ($sql_offset * $RECORDS_PER_PAGE))
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $id_it = 1;
    $pattern = "/[+](\w)*$/";
    while ($row = pg_fetch_assoc($data)) {
        $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
        $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
        $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
        echo
        "<form action='currentOrderInfo.php' method='post' id='form_" . $id_it . "'>
        <a href='#' onclick='document.getElementById(" . "\"form_" . $id_it . "\"" . ").submit();' class='text-decoration-none text-light'>
            <div class='row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv'>
                <div class='col border'>
                    <span class='fw-bold'>Id</span>
                    <hr class='m-0'>
                    <span>" . "<input name='order_info' type='text' class='d-none' value='" . $row['id'] . "'/>" . $id_it . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>VideoType</span>
                    <hr class='m-0'>
                    <span>" . $row["video_type"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Expire</span>
                    <hr class='m-0'>
                    <span>" . $row["expire_date"] . "</span>
                </div>
            </div>
        </a>
        </form>";
        $id_it += 1;
    }
}

function specialist_accept_order($specialist_email, $order_id)
{
    $conn = server_connect();
    $sql_query = "update orders set status = 'processing', specialist_id = 
    (select id from specialists where user_id = (select id from users where email = $1)) where id = $2;";
    $stmt = pg_prepare($conn, "specialist_accept_order", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_accept_order", array($specialist_email, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function specialist_order_limit($specialist_email)
{
    $conn = server_connect();
    $sql_query = "select current_orders_num from specialists where user_id = 
    (select id from users where email = $1);";
    $stmt = pg_prepare($conn, "specialist_order_limit", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_order_limit", array($specialist_email)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    $MAXIMUM_NUMBER_OF_ORDERS = 3;
    if ($row[0] < $MAXIMUM_NUMBER_OF_ORDERS) {
        return false;
    }
    return true;
}

function specialist_invalid_current_order($order_id, $specialist_email)
{
    $conn = server_connect();
    $sql_query = "select exists(select id from orders where id = $1 and specialist_id = 
    (select id from specialists where user_id = (select id from users where email = $2)) and 
    status = 'processing');";
    $stmt = pg_prepare($conn, "specialist_invalid_current_order", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "specialist_invalid_current_order",
        array($order_id, $specialist_email)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    if ($row[0] === 'f') {
        return true;
    }
    return false;
}

function specialist_current_order_info($specialist_email, $order_id)
{
    $conn = server_connect();
    $sql_query = "select expire_date, 
    video_type, place_of_operation from orders where 
    specialist_id = (select id from specialists where user_id = (select id from users where email = $1)) and id = $2;";
    $stmt = pg_prepare($conn, "specialist_current_order_info", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_current_order_info", array($specialist_email, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $pattern = "/[+](\w)*$/";
    $row = pg_fetch_assoc($data);
    $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
    return array(
        "order_expire_date" => $row["expire_date"], "order_video_type" => $row["video_type"],
        "order_place_info" => $row["place_of_operation"]
    );
}

function specialist_accomplish_order($order_id, $video_source)
{
    $conn = server_connect();
    $date_now_string = date("Y/m/d H:i:s");
    $date_now = date_create($date_now_string);
    $date_now = date_format($date_now, 'Y-m-d H:i:s');
    $sql_query = "update orders set date_of_completion = $1, video_source = $2, 
    status = 'completed' where id = $3;";
    $stmt = pg_prepare($conn, "specialist_accomplish_order", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_accomplish_order", array($date_now, $video_source, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function specialist_show_order_history($specialist_email, $sql_offset)
{
    $conn = server_connect();
    $sql_query = "select * from orders where specialist_id = (select id from specialists where user_id = 
    (select id from users where email = $1)) and (status = 'completed' or status = 'declined' or status = 'reported') 
    LIMIT $2 OFFSET $3;";
    $stmt = pg_prepare($conn, "specialist_show_order_history", $sql_query) or die("Connection error");
    $RECORDS_PER_PAGE = 3;
    $data = pg_execute(
        $conn,
        "specialist_show_order_history",
        array($specialist_email, $RECORDS_PER_PAGE, ($sql_offset * $RECORDS_PER_PAGE))
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $id_it = 1;
    $pattern = "/[+](\w)*$/";
    while ($row = pg_fetch_assoc($data)) {
        $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
        $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
        $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
        echo
        "<form action='currentOrderInfo.php' method='post' id='form_" . $id_it . "'>
        <a href='#' onclick='document.getElementById(" . "\"form_" . $id_it . "\"" . ").submit();' class='text-decoration-none text-light'>
            <div class='row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv'>
                <div class='col border'>
                    <span class='fw-bold'>Id</span>
                    <hr class='m-0'>
                    <span>" . "<input name='order_info' type='text' class='d-none' value='" . $row['id'] . "'/>" . $id_it . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>VideoType</span>
                    <hr class='m-0'>
                    <span>" . $row["video_type"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Expire</span>
                    <hr class='m-0'>
                    <span>" . $row["expire_date"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Completion</span>
                    <hr class='m-0'>
                    <span>" . $row["date_of_completion"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Status</span>
                    <hr class='m-0'>
                    <span>" . $row["status"] . "</span>
                </div>
            </div>
        </a>
        </form>";
        $id_it += 1;
    }
}

function specialist_get_history_total_pages($specialist_email)
{
    $conn = server_connect();
    $sql_query = "select count(*) from orders where status = 'completed' and specialist_id = 
    (select id from specialists where user_id = (select id from users where email = $1));";
    $stmt = pg_prepare($conn, "specialist_get_history_pages", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "specialist_get_history_pages",
        array($specialist_email)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    $RECORDS_PER_PAGE = 4;
    $total_pages = ceil($row[0] / $RECORDS_PER_PAGE);
    return $total_pages;
}

function specialist_cancel_order($order_id)
{
    $conn = server_connect();
    $sql_query = "update orders set specialist_id = NULL, status = 'waiting' where id = $1;";
    $stmt = pg_prepare($conn, "specialist_cancel_order", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_cancel_order", array($order_id)) or
        die("Connection error");
    $sql_query = "delete from chat_rooms where order_id = $1;";
    $stmt = pg_prepare($conn, "specialist_cancel_order_1", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_cancel_order_1", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function specialist_get_rating($specialist_email)
{
    $conn = server_connect();
    $sql_query = "select sum(rating), count(*) from orders where specialist_id = (select id from specialists where 
    user_id = (select id from users where email = $1)) and status = 'completed' and rating is not NULL;";
    $stmt = pg_prepare($conn, "specialist_get_rating", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "specialist_get_rating", array($specialist_email)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    if ($row[1] != 0) {
        $rating = round($row[0] / $row[1], 1);
        return $rating;
    } else {
        return false;
    }
}

function admin_get_total_pages()
{
    $conn = server_connect();
    $sql_query = "select count(*) from orders where status = 'reported';";
    $data = pg_query($conn, $sql_query) or die("Connection error");
    $row = pg_fetch_row($data);
    $RECORDS_PER_PAGE = 4;
    $total_pages = ceil($row[0] / $RECORDS_PER_PAGE);
    return $total_pages;
}

function admin_show_orders($sql_offset)
{
    $conn = server_connect();
    $sql_query = "select * from orders where status = 'reported' limit $1 offset $2;";
    $stmt = pg_prepare($conn, "admin_show_orders", $sql_query) or die("Connection error");
    $RECORDS_PER_PAGE = 4;
    $data = pg_execute(
        $conn,
        "admin_show_orders",
        array($RECORDS_PER_PAGE, ($sql_offset * $RECORDS_PER_PAGE))
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    if ($sql_offset != 0) {
        $id_it = $sql_offset * $RECORDS_PER_PAGE + 1;
    } else {
        $id_it = 1;
    }
    $pattern = "/[+](\w)*$/";
    while ($row = pg_fetch_assoc($data)) {
        $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
        $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
        $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
        echo
        "<form action='orderInfo.php' method='post' id='form_" . $id_it . "'>
        <a href='#' onclick='document.getElementById(" . "\"form_" . $id_it . "\"" . ").submit();' class='text-decoration-none text-light'>
            <div class='row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv'>
                <div class='col border'>
                    <span class='fw-bold'>Id</span>
                    <hr class='m-0'>
                    <span>" . "<input name='order_info' type='text' class='d-none' value='" . $row['id'] . "'/>" . $id_it . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>VideoType</span>
                    <hr class='m-0'>
                    <span>" . $row["video_type"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Creation</span>
                    <hr class='m-0'>
                    <span>" . $row["date_of_creation"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Expire</span>
                    <hr class='m-0'>
                    <span>" . $row["expire_date"] . "</span>
                </div>
                <div class='col border'>
                    <span class='fw-bold'>Completion</span>
                    <hr class='m-0'>
                    <span>" . $row["date_of_completion"] . "</span>
                </div>
            </div>
        </a>
        </form>";
        $id_it += 1;
    }
}

function admin_invalid_reported_order($order_id)
{
    $conn = server_connect();
    $sql_query = "select exists(select id from orders where id = $1 and
    status = 'reported');";
    $stmt = pg_prepare($conn, "admin_invalid_reported_order", $sql_query) or die("Connection error");
    $data = pg_execute(
        $conn,
        "admin_invalid_reported_order",
        array($order_id)
    ) or die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    if ($row[0] === 'f') {
        return true;
    }
    return false;
}

function admin_reported_order_info($order_id)
{
    $conn = server_connect();
    $sql_query = "select date_of_creation, date_of_completion, status, expire_date, video_type, 
    place_of_operation, video_source, report_message from orders where id = $1 and status = 'reported';";
    $stmt = pg_prepare($conn, "admin_reported_order_info", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "admin_reported_order_info", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $pattern = "/[+](\w)*$/";
    $row = pg_fetch_assoc($data);
    $row["date_of_creation"] = preg_replace($pattern, "", $row["date_of_creation"]);
    $row["expire_date"] = preg_replace($pattern, "", $row["expire_date"]);
    $row["date_of_completion"] = preg_replace($pattern, "", $row["date_of_completion"]);
    return array(
        "order_creation_date" => $row["date_of_creation"], "order_completion_date" => $row["date_of_completion"],
        "order_status" => $row["status"], "order_expire_date" => $row["expire_date"],
        "order_video_type" => $row["video_type"], "order_place_info" => $row["place_of_operation"],
        "order_video_source" => $row["video_source"], "order_report_message" => $row["report_message"]
    );
}

function admin_approve_report($order_id)
{
    $conn = server_connect();
    $sql_query = "update orders set status = 'declined' where id = $1;";
    $stmt = pg_prepare($conn, "admin_approve_report", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "admin_approve_report", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function admin_decline_report($order_id)
{
    $conn = server_connect();
    $sql_query = "update orders set status = 'completed' where id = $1;";
    $stmt = pg_prepare($conn, "admin_approve_report", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "admin_approve_report", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function pg_to_array($arr)
{
    $arr = preg_replace(array('/{/', '/}/'), array('', ''), $arr);
    if (empty($arr) || $arr === null) {
        return false;
    }
    $arr = explode(',', $arr);
    return $arr;
}

function set_chat($order_id, $user_type)
{
    $conn = server_connect();
    $sql_query = "select * from chat_rooms where order_id = $1;";
    $stmt = pg_prepare($conn, "set_chat", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "set_chat", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_assoc($data);
    $pattern = array('/"/', "/[:]..[+](\w)*$/");
    $row['message'] = pg_to_array($row['message']);
    $row['message_sender'] = pg_to_array($row['message_sender']);
    $row['message_time'] = pg_to_array($row['message_time']);
    if ($row['message'] !== false) {
        for ($i = 0; $i < count($row['message']); $i++) {
            $row["message_time"][$i] = preg_replace($pattern, "", $row["message_time"][$i]);
            if ($row['message_sender'][$i] === 'specialist') {
                if ($user_type === 'specialist') {
                    echo
                    "<div class='specialist-message-div bg-primary float-end mb-3 d-inline-block rounded-3'>
            <p class='px-1 text-info'>" . "me" . " " . $row['message_time'][$i] . "</p>
            <p class='px-1 text-white'>" . $row['message'][$i] . "</p>
        </div>";
                } else {
                    echo
                    "<div class='specialist-message-div bg-secondary mb-3 d-inline-block rounded-3'>
            <p class='px-1 text-warning'>" . "specialist" . " " . $row['message_time'][$i] . "</p>
            <p class='px-1 text-white'>" . $row['message'][$i] . "</p>
        </div>";
                }
            } elseif ($row['message_sender'][$i] === 'client') {
                if ($user_type === 'client') {
                    echo
                    "<div class='client-message-div bg-primary float-end mb-3 d-inline-block rounded-3'>
            <p class='px-1 text-info'>" . "me" . " " . $row['message_time'][$i] . "</p>
            <p class='px-1 text-white'>" . $row['message'][$i] . "</p>
        </div>";
                } else {
                    echo
                    "<div class='client-message-div bg-secondary mb-3 d-inline-block rounded-3'>
            <p class='px-1 text-warning'>" . "client" . " " . $row['message_time'][$i] . "</p>
            <p class='px-1 text-white'>" . $row['message'][$i] . "</p>
        </div>";
                }
            }
        }
        return count($row['message']);
    }
    return 0;
}

function check_chat($order_id, $message_num)
{
    $conn = server_connect();
    $sql_query = "select * from chat_rooms where order_id = $1;";
    $stmt = pg_prepare($conn, "check_chat", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "check_chat", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_assoc($data);
    $row['message'] = pg_to_array($row['message']);
    if ($row['message'] !== false) {
        if (count(($row['message'])) > $message_num) {
            $new_message = array();
            $row['message_sender'] = pg_to_array($row['message_sender']);
            $row['message_time'] = pg_to_array($row['message_time']);
            $pattern = array('/"/', "/[:]..[+](\w)*$/");
            for ($i = $message_num; $i < count($row['message']); $i++) {
                $row["message_time"][$i] = preg_replace($pattern, "", $row["message_time"][$i]);
                array_push($new_message, array($row['message'][$i], $row['message_sender'][$i], $row['message_time'][$i]));
            }
            return array($new_message, count($row['message']));
        }
    }
    return false;
}

function update_chat($order_id, $message, $message_sender)
{
    $conn = server_connect();
    $date_now_string = date("Y/m/d H:i:s");
    $date_now = date_create($date_now_string);
    $date_now = date_format($date_now, 'Y-m-d H:i:s');
    $sql_query = "update chat_rooms set message = array_append(message, $1),
     message_sender = array_append(message_sender, $2), message_time = array_append(message_time, $3) where order_id = $4;";
    $stmt = pg_prepare($conn, "update_chat", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "update_chat", array($message, $message_sender, $date_now, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function create_chat_room($order_id, $client_id, $specialist_id)
{
    $conn = server_connect();
    $sql_query = "insert into chat_rooms (client_id, specialist_id, order_id) values($1, $2, $3);";
    $stmt = pg_prepare($conn, "create_chat_room", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "create_chat_room", array($client_id, $specialist_id, $order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
}

function order_get_client_specialist_id($order_id)
{
    $conn = server_connect();
    $sql_query = "select client_id, specialist_id from orders where id = $1;";
    $stmt = pg_prepare($conn, "order_get_client_id", $sql_query) or die("Connection error");
    $data = pg_execute($conn, "order_get_client_id", array($order_id)) or
        die("Connection error");
    pg_close($conn) or die("Connection error");
    $row = pg_fetch_row($data);
    return array($row[0], $row[1]);
}

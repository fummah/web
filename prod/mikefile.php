<?php
$txt_file = fopen('file.txt','r');
$input_startdate="2022-04-02";
$input_enddate="2022-04-20";
$myroom="2022-04-20";
$servername = "sql14.cpt4.host-h.net";
$username = "kmgapqawmv_144";
$password = "0C58G40o3U1gTJbVt3H4";
try {
    $conn = new PDO("mysql:host=$servername;dbname=kmgapqawmv_wpc6e3", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$stmt = $conn->prepare("SELECT order_item_id,order_item_name FROM `wp_hotel_booking_order_items` ORDER BY order_item_id DESC LIMIT 1");
    while ($line = fgets($txt_file)) {
        echo checkDates($line);
        if((int)checkDates($line)<1) {
            $det = explode(";", $line);
            $roomname = $det[0];
            $start_date = $det[1];
            $start_date = substr($start_date, 0, 4) . "-" . substr($start_date, 4, 2) . "-" . substr($start_date, 6, 2);
            $end_date = $det[2];
            $end_date = substr($end_date, 0, 4) . "-" . substr($end_date, 4, 2) . "-" . substr($end_date, 6, 2);

            $check_in_date=strtotime($start_date);$check_out_date=strtotime($end_date);
            $product_id = 1909;
            if ($roomname == "LUXURY ONE-BEDROOM") {
                $roomname = "Luxury One-Bedroom";
                $product_id = 1981;
            } elseif ($roomname == "STANDARD ONE-BEDROOM") {
                $roomname = "Standard One-Bedroom";
                $product_id = 1909;
            } elseif ($roomname = "SUPERIOR TWO-BEDROOM") {
                $roomname = "Superior Two-Bedroom";
                $product_id = 1568;
            }

            instertOrder($roomname, "line_item", 200007, 2372);
            $hotel_booking_order_item_id = getId($roomname);
            $arr = alldet(0, 0, 0, 1, $check_in_date, $check_out_date, $product_id);
            foreach ($arr as $key => $value) {
                instertMeta($hotel_booking_order_item_id, $key, $value,$line);
            }

            /*
                    if (checkDatesx($input_startdate, $input_enddate, $start_date, $end_date)) {
                        echo "$input_startdate >= $start_date && $input_startdate <= $end_date) or ($input_enddate >= $start_date && $input_enddate <= $end_date <br>";
                        echo ($line) . "It is there choose anothe one <br>" . $roomname . "<br>" . $start_date . "<br>" . $end_date . "<hr>";
                    } else {
                        echo "The date is not there, you can book <br>";
                        echo "$input_startdate >= $start_date && $input_startdate <= $end_date) or ($input_enddate >= $start_date && $input_enddate <= $end_date <hr>";
                    }
            */
        }
        else{
            echo "Duplicate <hr>";
        }
    }
    fclose($txt_file);
}
catch(Exception $e)
{
    echo "Error : ".$e->getMessage();
}
function instertMeta($hotel_booking_order_item_id,$meta_key,$meta_value,$identifier)
{
    global $conn;
    $stmt=$conn->prepare('INSERT INTO `wp_hotel_booking_order_itemmeta`(`hotel_booking_order_item_id`, `meta_key`, `meta_value`,`identifier`) VALUES (:hotel_booking_order_item_id,:meta_key,:meta_value,:identifier)');
    $stmt->bindParam(':hotel_booking_order_item_id', $hotel_booking_order_item_id, PDO::PARAM_STR);
    $stmt->bindParam(':meta_key', $meta_key, PDO::PARAM_STR);
    $stmt->bindParam(':meta_value', $meta_value, PDO::PARAM_STR);
    $stmt->bindParam(':identifier', $identifier, PDO::PARAM_STR);
    $stmt->execute();
}
function getId($order_item_name)
{
    global $conn;
    $stmt=$conn->prepare('SELECT MAX(order_item_id) FROM wp_hotel_booking_order_items WHERE order_item_name=:order_item_name');
    $stmt->bindParam(':order_item_name', $order_item_name, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
function checkDates($identifier)
{
    global $conn;
    $stmt=$conn->prepare('SELECT *FROM wp_hotel_booking_order_itemmeta WHERE identifier=:identifier');
    $stmt->bindParam(':identifier', $identifier, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount();
}
function instertOrder($order_item_name,$order_item_type,$order_item_parent,$order_id)
{
    global $conn;
    $stmt=$conn->prepare('INSERT INTO `wp_hotel_booking_order_items`(`order_item_name`,`order_item_type`,`order_item_parent`,`order_id`) VALUES (:order_item_name,:order_item_type,:order_item_parent,:order_id)');
    $stmt->bindParam(':order_item_name', $order_item_name, PDO::PARAM_STR);
    $stmt->bindParam(':order_item_type', $order_item_type, PDO::PARAM_STR);
    $stmt->bindParam(':order_item_parent', $order_item_parent, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
    $stmt->execute();
}


function checkDatesx($startdate,$enddate,$start_db,$end_db)
{
    $tr=false;
    $daterange1 = array($startdate, $enddate);
    $daterange2 = array($start_db, $end_db);

    $range_min = new DateTime(min($daterange1));
    $range_max = new DateTime(max($daterange1));

    $start = new DateTime(min($daterange2));
    $end = new DateTime(max($daterange2));

    if ($start >= $range_min && $end <= $range_max) {
        $tr=true;
    } else {
        $tr=false;
    }
    return $tr;
}

function alldet($tax_total,$total,$subtotal,$qty,$check_in_date,$check_out_date,$product_id)
{
    $arr=array("tax_total"=>$tax_total,"total"=>$total,"subtotal"=>$subtotal,"qty"=>$qty,"check_in_date"=>$check_in_date,"check_out_date"=>$check_out_date,"product_id"=>$product_id);
    return $arr;
}
?>
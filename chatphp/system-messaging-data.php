<?php

include("../php/dateconverter_function.php");

function str_openssl_dec($str, $iv)
{
    $key = '1234567890vishal%$%^%$$#$#';
    $cipher = "AES-128-CTR";
    $options = 0;
    $str = openssl_decrypt($str, $cipher, $key, $options, $iv);
    return $str;
}

$mess = "";
$iv = "";
$msg = "";

while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {

    $row2 = null; // Initialize $row2 before the if statement

    $stmt2 = $newconnection->openConnection()->prepare("SELECT * FROM messages WHERE (incoming_msg_id = :unique_id OR outgoing_msg_id = :unique_id)
                AND (outgoing_msg_id = :outgoing_id OR incoming_msg_id = :outgoing_id) ORDER BY msg_id DESC LIMIT 1");
    $stmt2->bindParam(':unique_id', $row->unique_id, PDO::PARAM_INT);
    $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt2->execute();

    if ($stmt2->rowCount() > 0) {
        $row2 = $stmt2->fetch(PDO::FETCH_OBJ);
        $result = $row2->msg;
        $v = $row2->iv;
        $iv = hex2bin($v);
        $mess = str_openssl_dec($result, $iv);

        $datetime = $row2->msg_datetime;
        $formattedendDateandTime = formatDateandTimeToWords($datetime);
        $hour = date("h:i A", strtotime($row2->msg_datetime));

        if (strlen($mess) > 22) {
            $mess = substr($mess, 0, 15) . '...';
        }

        $you = ($row2->outgoing_msg_id == $outgoing_id) ? "You: " : "";

        $output .= '<a href="#" class="chat-link" data-id="' . $row->unique_id . '" data-usertype="' . $row->usertype . '" data-action="1">
                        <div class="content">
                            <img src="' . $row->profile . '" alt="">
                            <div class="details">
                                <span>' . $row->firstname . " " . $row->lastname . '</span>
                                <p>' . $you . $mess .'<small>• '. $hour . '</small></p>
                            </div>
                        </div>
                    </a>';
    } else {
        $mess = "No message available";

        // Output for the case where no message is available
        $output .= '<a href="#" class="chat-link" data-id="' . $row->unique_id . '" data-usertype="' . $row->usertype . '">
                        <div class="content">
                            <img src="' . $row->profile . '" alt="">
                            <div class="details">
                                <span>' . $row->firstname . " " . $row->lastname . '</span>
                                <p>' . $mess . '</p>
                            </div>
                        </div>
                    </a>';
    }
}
?>

<?php
/**
 * Created by @OnyxTM.
 * User: Morteza Bagher Telegram id : @mench
 * Date: 11/12/2016
 * Time: 09:19 PM
 */



include 'config.php';


define('API_KEY','XXX:XXX');
$admin = "ADMIN ID";
$channeluse = "CHANNEL USERNAME";

//

function addSubscriber($id, $chatID) {
    global $mysqli;
    $res = $mysqli->query("SELECT * FROM IDs WHERE user_id = '$id'");
    $num = $res->num_rows;

    $res2 = $mysqli->query("SELECT * FROM subscribers WHERE subscriber = '$id' AND subscribed = '$chatID'");
    $num2 = $res2->num_rows;
    if($num2 == 0) {
        if($num != 0) {
            $mysqli->query("UPDATE IDs SET count = count + 1 WHERE user_id = '$id'");
            $mysqli->query("INSERT INTO subscribers (subscriber, subscribed)VALUES('$id', '$chatID')");
            return "با موفقیت زیرمجوعه این دوستت شدی";
        } else {
            return "متاسفانه این دوستمون که میخوای زیرمجموعش بشی ثبت نام نکرده";
        }
    } else {
        return "متاسفانه نمیتوانید زیرمجموعه این دوستمون باشید";
    }
}


function insertUser($user_id, $first_name, $last_name, $username){
    global $mysqli;
    $res = $mysqli->query(" SELECT * FROM IDs WHERE user_id = '$user_id' ");
    $num = $res->num_rows;
    if($num == 0) {
        $mysqli->query(" INSERT INTO IDs (user_id, first_name, last_name, username)VALUES('$user_id', '$first_name', '$last_name', '$username') ");
    } else {
        $mysqli->query(" UPDATE IDs SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE user_id = '$user_id' ");
    }
}

function bridge($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
function bridge2($token,$method,$datas=[]){
    $url = "https://api.telegram.org/bot".$token."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function rp($Number){
    $Rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $Number);
    return $Rand;
}


$update = json_decode(file_get_contents('php://input'));
$txt = $update->message->text;
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$channel_forward = $update->channel_post->forward_from;
$channel_text = $update->channel_post->text;
$from = $update->message->from->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$msgid = $update->callback_query->message->message_id;
$first_name = $update->message->chat->first_name;
$last_name = $update->message->chat->last_name;
$username = $update->message->chat->username;

$photo = $update->message->photo;
$fp = $photo[count($photo)-1]->file_id;



$bool = file_get_contents("$chat_id/bool.txt");
$bools= explode("\n",$bool);

$btnmen = json_encode(['inline_keyboard'=>[
    [['text'=>'Channel | کانال' ,'url'=>'t.me/countmsg']],[['text'=>'Advertising | تبلیغات' ,'callback_data'=>'ads']],
    [['text'=>'Source Bot | سورس ربات' ,'callback_data'=>'botsrc']],
    [['text'=>'Inline mode | حالت اینلاین' ,'switch_inline_query'=>'']]
]]);

$startbot = "سلام دوست من به ربات سین ساز خوش اومدید پیامت رو بفرس تا سین دارش کنم." ;
$startmen = "سلام دوست عزیز من 👮🏻
پیام خود را ارسال کنید تا به صورت 👁‍🗨 (ویو) دریافت کنید.
ساخته شده توسط : <code>@mench</code>
همین حالا توکن رباتت رو که از باتفادر دریافت کردی رو برای من بفرست 
فقط بخش مربوط به توکن که شبیه
<code>123456789:VDJSDOFKsdalkjdfsKLFDSF</code>
میباشد را برای من ارسال کنید.
(مخصوص فارسی زبانان)

همین حالا دوستانتان را به این ربات هیجان انگیز دعوت کنید:
https://t.me/countmsgbot?start=$chat_id
برای دریافت آمار دریافتی کسانی که از طریق شما عضو ربات شدند دستور /countme را ارسال کنید.
➖➖➖➖➖➖➖
Hello my dear friend 👮🏻
If you send your message to 👁🗨 (View) get.
Made by: <code>@mench</code>
you Now Like Friend And Send Welcom To Bot
https://t.me/countmsgbot?start=$chat_id
Give Count Login This Url Send /countme";

$time = file_get_contents("http://api.bridge-ads.ir/td?td=time");
$date = file_get_contents("http://api.bridge-ads.ir/td?td=date");

$reply = $update->message->reply_to_message;
$tokeen = json_decode(file_get_contents("https://api.telegram.org/bot".$txt."/getMe"));
if($tokeen->ok == true){
    $res12 = $mysqli->query("SELECT * FROM IDs WHERE user_id='$chat_id'");
    while($row12 = $res12->fetch_assoc()){
        $count = $row12["count"];
        $id = $tokeen->result->id;
        if($count >= "20"){
            $sql = "INSERT INTO token (tok, admin, start, id) VALUES ('$txt', $chat_id, '$startbot' ,$id)";
            $mysqli->query($sql);
            if(!is_dir("bot/$id")) {
                mkdir("bot/$id");
                file_put_contents("bot/$id/channel.txt", "");
                file_put_contents("bot/$id/start.txt", $startbot);
                file_put_contents("bot/$id/bool.txt", "false#-!false#-!false");
                file_put_contents("bot/$id/token.txt", "$txt");
                file_put_contents("bot/$id/admin.txt", "$chat_id");
                file_put_contents("bot/$id/step.txt", "NULL");
                file_put_contents("bot/$id/Member.txt", "$chat_id\n");
                $bot = file_get_contents("bot.txt");
                file_put_contents("bot/$id/index.php", "$bot");
                bridge("sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "ربات شما ثبت شد.
            @" . $tokeen->result->username . "
            برای دریافت راهنما و تنظیم متن شروع و آیدی کانال در ربات خود دستور /help را ارسال کنید 😉"
                ]);

                bridge2($txt, "sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "ربات شما به @COUNTMSGBOT متصل شد 
            برای دریافت راهنما دستور /help را ارسال کنید"
                ]);

                bridge2($txt, "setwebhook", [
                    'url' => "https://binaam.000webhostapp.com/bot/countbot/bot/$id/index.php"
                ]);
                $nc = $count - 20;
                $mysqli->query(" UPDATE IDs SET count = '$nc' WHERE user_id = '$chat_id' ");
            }
        }elseif(is_dir("bot/$id")) {
            $bot12 = file_get_contents("bot.txt");
            file_put_contents("bot/$id/index.php",$bot12);
            file_put_contents("bot/$id/step.txt", "NULL");
            bridge("sendMessage", [
                'chat_id' => $chat_id,
                'text' => "ربات بروز شد
                @".$tokeen->result->username
            ]);
            bridge2($txt, "setwebhook", [
                'url' => "https://binaam.000webhostapp.com/bot/countbot/bot/$id/index.php"
            ]);
        }else{
            bridge("sendMessage", [
                'chat_id' => $chat_id,
                'text' => "با استفاده از لینک زیر 20 نفر را به ربات دعوت کن تا این بخش فعال بشه
                    t.me/countmsgbot?start=$chat_id
                    $count < 20"
            ]);
        }
    }

}else if ($txt == "/start") {
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        'text' => $startmen,
        'parse_mode' => "HTML",
        'reply_markup' => $btnmen
    ]);
    bridge("forwardmessage", [
        'chat_id' => $chat_id,
        'from_chat_id' => "@ch_jockdoni",
        'message_id' => "791"
    ]);
//        mkdir($chat_id);
    copy('bool.txt', $chat_id);

//        function insertUser($user_id, $first_name, $last_name, $username){
//            global $mysqli;
    insertUser($chat_id,$first_name,$last_name,$username);
}elseif(preg_match('/^\/([Cc]ountme)/',$txt)){
    $count = $mysqli->query("SELECT count FROM IDs WHERE user_id=$chat_id");
    while ($row33 = $count->fetch_assoc()){
        $coungme = $row33['count'];

        bridge("SendMessage",[
            'chat_id'=>"$chat_id",
            'text'=>"شما تا کنون  $coungme  نفر را دعوت کردید"
        ]);
    }
}elseif(preg_match('/^\/([Ss]tart )/',$txt)){
    $teext = addSubscriber(str_replace("/start ","",$txt), $chat_id);
    bridge("SendMessage",[
        'chat_id'=>"$chat_id",
        'text'=>$teext."
            $startmen",
        'parse_mode'=>"HTML"
    ]);
    insertUser($chat_id,$first_name,$last_name,$username);
} else if ($data == "ads") {
    bridge("editmessagetext", [
        'chat_id' => $chatid,
        'message_id' => $msgid,
        'text' => "تبلیغات فارسی:
[کانال جوکدونی😊](https://telegram.me/joinchat/EzUIy0AnWqQZgiA_w-I7lA)
👈برای ثبت تبلیغ [اینجا](http://telegram.me/mench) کلیک کنید
➖➖➖➖➖
English Ad:
NULL
Sign 👈Bray ad [here](http://telegram.me/mench) Click",
        'parse_mode' => "Markdown",
        'disaple_web_page_preview' => true,
        'reply_markup' => json_encode(['inline_keyboard' => [
            [['text' => 'Home | خانه', 'callback_data' => 'menu']]
        ]])
    ]);
} else if ($data == "botsrc") {
    bridge("editmessagetext", [
        'chat_id' => $chatid,
        'message_id' => $msgid,
        'text' => "توکن ربات خود را که شبیه توکن های زیر است را برای من ارسال کنید تا برایتان رباتتان را بسازم
333222111:fdkjflsSDFLSDmdlsjkjJvjfjknfFDnfjdk
",
        'reply_markup' => json_encode(['inline_keyboard' => [
            [['text' => 'Home | خانه', 'callback_data' => 'menu']],
            [['text' => 'Source | سورس', 'url' => 'https://github.com/onyxtm/CountBot']]
        ]])
    ]);

} else if ($data == "menu") {
    bridge("editmessagetext", [
        'chat_id' => $chatid,
        'message_id' => $msgid,
        'text' => $startmen,
        'parse_mode' => "HTML",
        'reply_markup' => $btnmen
    ]);
} else if (preg_match('/^\/([Ss]tate)/', $txt) && $from == $admin) {
    $user = file_get_contents('CountMem.txt');
    $member_id = explode("\n", $user);
    $member_count = count($member_id) - 1;
    bridge('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "👥 تعداد کاربران جدید ربات شما : $member_count",
        'parse_mode' => 'HTML'
    ]);
} else if (preg_match('/^\/([Ss]endtoall)/', $txt) && $from == $admin) {
    $strh = str_replace("/sendtoall", "", $txt);
    $ttxtt = file_get_contents('CountMem.txt');
    $membersidd = explode("\n", $ttxtt);
    for ($y = 0; $y < count($membersidd); $y++) {
        bridge("sendMessage", [
            'chat_id' => $membersidd[$y],
            "text" => $strh,
            "parse_mode" => "HTML"
        ]);
    }
    $memcout = count($membersidd) - 1;
    bridge("sendMessage", [
        'chat_id' => $admin,
        "text" => "پیام شما به $memcout نفر ارسال شد.",
        "parse_mode" => "HTML"
    ]);
} else if (preg_match('/^\/([Ff]ortoall)/', $txt) && $from == $admin) {
    $ttxtt = file_get_contents('CountMem.txt');
    $membersidd = explode("\n", $ttxtt);

    for ($y = 0; $y < count($membersidd); $y++) {
        bridge("forwardmessage", [
            'chat_id' => $membersidd[$y],
            'from_chat_id' => $chat_id,
            'message_id' => $update->message->reply_to_message->message_id
        ]);
    }

    $memcout = count($membersidd) - 1;
    bridge("sendMessage", [
        'chat_id' => $admin,
        "text" => "پیام شما به $memcout نفر ارسال شد.",
        "parse_mode" => "HTML"
    ]);
} else if (isset($update->message->reply_to_message)) {
    bridge("forwardMessage", [
        'chat_id' => $txt,
        'disable_notification' => true,
        'from_chat_id' => $chat_id,
        'message_id' => $update->message->reply_to_message->message_id
    ]);
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        "text" => "پیام ارسای شد به $txt",
        "parse_mode" => "HTML"
    ]);
} else {
    $to_channel = bridge("forwardMessage", [
        'chat_id' => $channeluse,
        'from_chat_id' => $chat_id,
        'message_id' => $message_id
    ])->result->message_id;

    bridge("forwardMessage", [
        'chat_id' => $chat_id,
        'from_chat_id' => $channeluse,
        'message_id' => $to_channel
    ]);
    bridge("forwardMessage", [
        'chat_id' => $chat_id,
        'from_chat_id' => "@ch_jockdoni",
        'message_id' => "791"
    ]);
}


$user = file_get_contents('CountMem.txt');
$members = explode("\n", $user);
if (!in_array($chat_id, $members)) {
    $add_user = file_get_contents('CountMem.txt');
    $add_user .= $chat_id . "\n";
    file_put_contents('CountMem.txt', $add_user);
}

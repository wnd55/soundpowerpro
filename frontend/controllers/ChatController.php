<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 23.08.19
 * Time: 16:07
 */

namespace frontend\controllers;



use yii\base\Controller;


class ChatController extends Controller
{



    public function actionChatIntake()
    {
        $purifier = new \HTMLPurifier();
        $cleanUname = $purifier->purify($_POST['uname']);
        if (isset($cleanUname)) {
            $_SESSION['uname'] = $cleanUname;
            echo "success";
            exit();
        }


    }


    public function actionChatPost()
    {
        $text = "";

        $purifier = new \HTMLPurifier();
        $cleanText = $purifier->purify($_POST['text']);
        $text = $_SESSION['uname'] . " : " . $cleanText . "\n";
        $handle = fopen("chat.txt", "a");
        fwrite($handle, $text);
        fclose($handle);
        exit();


    }


    public function actionServer()
    {

        header('Content-Type: text/event-stream');
        header("Cache-Control: no-cache"); // Prevent Caching
        $_SESSION['last_line'] = "";
        $data = file_get_contents("chat.txt");
// Trim the last new line character from the data
        $trimmed = rtrim($data, "\n");
// Create an array in which each new line is an element
        $data_array = explode("\n", $trimmed);
// Grab just the last line from the end of the array
        $last_line = end($data_array);
// If $last_line is not equal to 'last_line' session variable
        if ($last_line != $_SESSION['last_line']) {
            echo "data: $last_line\n\n";
            // Update last line session variable
            $_SESSION['last_line'] = $last_line;
        }
// Do not make retry less than 15000(15 seconds) on shared hosting
// And limit simultaneous users to prevent exceeding server resources
        echo "retry: 15000\n";
        ob_flush();
        flush();


    }
}
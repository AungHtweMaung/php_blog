<?php
// post method နဲ့ညီရင် form ထဲက token နဲ့ session ထဲက token ညီလား စစ်မယ်
// if not same, show invalid csrf token, 
// if same, after executing form post method, unset session token. 
// token will always be new while submitting post method.  
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION["_token"], $_POST["_token"])) {
        echo "Invalid CSRF token";
        die();
    } else {
        unset($_SESSION["_token"]);
    }
}


// csrf token သုံးတာက hacker, attacker တွေက ဆင်တူရိုးမှား သူတို့ရဲ့ form တွေကနေ ငါတို့ရဲ့ form action အတိုင်းထည့်ပေးလိုက်ပြီး
// သူတို့ရဲ့ data တွေငါတို့ဆီကို ဝင်မလာအောင် token generate လုပ်ပြီး protect လုပ်တာ
// token မရှိရင် create လုပ်ပြီး session မှာသိမ်းမယ် 
if (empty($_SESSION['_token'])) {
    if (function_exists('random_bytes')) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    } else if (function_exists('mcrypt_create_iv')) {
        $_SESSION['_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}



// xss attck တွေ protect လုပ်တာ
// user ထည့်လိုက်တဲ့ data တွေပြန်ပြတဲ့အခါ html char တွေကို ignore လုပ်ပြီး text အတိုင်းပြန်ပြတာ
function escape($html)
{
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

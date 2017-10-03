<!doctype html>
<html lang="en">
<head>
    <title>Test</title>
    <meta charset="utf-8">
    <style>
      body {
        margin-top: 20px;
      }
      table {
        border-collapse:collapse;
      }
      th, td {
        border: solid 1px black;
        padding: 2px;
      }
      .blue {
        background-color: blue;
      }
      .green {
        background-color: lightgreen;
      }
      .red {
        background-color: red;
      }
      .summary {
        position:fixed;
        top: 0;
        left: 0;
        background-color: yellow;
        padding: 5px;
        border:solid 1px orange;
      }
    </style>
</head>
<body>
<?php
  function fDiv ($p_text) {
    echo ("<div><pre>$p_text</pre></div>");
  }

  function fErr ($p_text) {
    echo ("<div style='color:red'><pre>$p_text</pre></div>");
  }

  function fTest ($p_test, $p_src, $p_key_enc, $p_key_enc_type, $p_key_dec, $p_key_dec_type, $p_result) {
    $v_enc_txt = null;
    switch ($p_key_enc_type) {
      case "public":
        openssl_public_encrypt ($p_src, $v_enc_txt, $p_key_enc);
        break;
      case "private":
        openssl_private_encrypt ($p_src, $v_enc_txt, $p_key_enc);
        break;
      default:
        fErr ("Unknown p_key_enc_type");
    }
    $v_dec_txt = null;
    switch ($p_key_dec_type ) {
      case "public":
        openssl_public_decrypt ($v_enc_txt, $v_dec_txt, $p_key_dec);
        break;
      case "private":
        openssl_private_decrypt ($v_enc_txt, $v_dec_txt, $p_key_dec);
        break;
      default:
        fErr ("Unknown p_key_dec_type");
    }

    $error = true;
    $equaltest = false;
    switch ($p_result) {
      case "equal":
        $equaltest = true;
        if ($p_src === $v_dec_txt) {
          $error = false;
        }
        break;
      case "encoded null":
        if (is_null ($v_enc_txt) && !is_null ($v_dec_txt)) {
          $error = false;
        }
        break;
      case "decoded null":
        if (!is_null ($v_enc_txt) && is_null ($v_dec_txt)) {
          $error = false;
        }
        break;
      case "both null":
        if (is_null ($v_enc_txt) && is_null ($v_dec_txt)) {
          $error = false;
        }
        break;
      case "different":
        if ($p_src !== $v_dec_txt) {
          $error = false;
        }
        break;
      default:
        fErr ("$p_test : Unknown expected result");
    }
    if ($equaltest) {
      echo ("<tr class='blue'>");
    } else {
      echo ("<tr>");
    }
    echo ("<td>$p_test</td><td>$p_key_enc_type</td><td>" . substr ($p_key_enc, 10, strpos ($p_key_enc, "KEY-----") - 7) . "</td><td>$p_key_dec_type</td><td>" . substr ($p_key_dec, 10, strpos ($p_key_dec, "KEY-----") - 7) . "</td><td>$p_result</td>");
    if (!$error) {
      //echo ("<td class='green'>ok</td><td></td>");
      echo ("<td class='green'>ok</td><td>Source text  : $p_src|<br>Encoded text : " . base64_encode ($v_enc_txt) . "|<br>Decoded text : $v_dec_txt|</td>");
      echo ("</tr>");
      return 0;
    } else {
      echo ("<td class='red'>ERROR</td><td>Source text  : $p_src|<br>Encoded text : " . base64_encode ($v_enc_txt) . "|<br>Decoded text : $v_dec_txt|</td>");
      echo ("</tr>");
      return 1;
    }
  }

  $v_private_key = "-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDuCOIkfCx+M8lj
em+lBD91HAzErzScp/QD1d1vHr+fCajBC/adgHlDPWugnewuLyx4oterDHLocqMS
Gw6m2GaaqokjvzYDI7d9XvDcJ8yOBsodF8u4GurKEI6Dakiw7BtSKQpSsp4xQPEO
yLNycfhzLvrF9I6os5nbB4wW7WYBqmDWV19AKAQhOJBv1vvVgdUAZsxaVyQ6OLwm
7GsB3AbW/QReAu+dN0TpgSmXAHDjeZyUtTV1ju6OXINmGCwgpKEcOX+VMfCBM9U4
aYqEXs8aE8slAwNRPfTQBv2hjAoW5T/VkqnVyT4t1v7evv6xheZfOX7aZ56h42jx
n7HtLGMBAgMBAAECggEBAJV6SKDGxZ3+4VGx+lgzAbKGCdKdf7l8kvSxoZt9QLIb
e7i2zYh8vCmocWTspvsdrpyXDj4g1Cv6iUL+cMX/kPdBbltfYQi5rilxrGlkKMVr
qBJ69fhIFvcLTKNj5AAOVN3UXeIuvr6JXJd/G4kb3vxeHSage9Ge71gnNibTr8IM
yQ9fSnrKQ9bqJTD+AaHQ0sCf/TY7vG9H5g3b/dg7cpul/qLdGAXx1k+ZPhpTEQLn
wm1yQa4Vhf7G8c2Vzcf+miGuYWxnTNCC5dG0bE+ZzV2huNWWXuWFervAX8X9COY3
ZbluvgpLUIQTZM37L/sO8VUiaD+eblEqAr7xYUqSV0ECgYEA/tb/0rkFYn7gYhir
FAYP68drJcUOBmnoknj24mV6oPq9WXNUlj72WATUjIy4BGAEMJRftWQgaqGJtkfx
GooiDJQKe/9EXyeD8+A72Wu0cvYW12VYGfrJZIAxbStAcHUkmgskQFf5AMPDsD78
xvRGc9vz7LpF8tZ2WpFYgyybSdkCgYEA7x5MdXsYHXGQdMkYvip71DnbADx3DMN3
SXYDPsYC/fkbiBojV8NZI8XkcXmrmYF4NnemMLmWDTafWuO4gvF7GKa1ylwElouo
PVPo6BpjR59/4dUCLJyjOjuS4nzyM5DxdZij8rAlh19QkPS3fOCXsYmJjQ/caeeT
0nlbnXmzQWkCgYABnhvBmqsOFQAXn1B2sBMpy/pCIx4TrFhtlZb7mOlOXEkXaEQB
MCUgKeevfLUuUe204Tw6SohqJNxa69n78SSGL+phHx2v/PA5fBLlLmnW0PkUJPCK
Oa1NlgK6Tqv6CsRQtgFk5yoNdzCmzb+NA7/uvFawCf6nq/TUXlfRfVb/AQKBgBPB
1iFy3Ug5hO0RXJkB259qdFztogyM7gNE2/nI1KB1f7/cIQV0X54kEL8LhZE2cF9X
vYSTIQHQfJQ+8pTnLPuZcrXYqoxh17HRiBH9dCyL0j4vonFbM+Vw3K8YEc8O79R+
mzVHNqKbzoVbovmPeRkzOwlLwTOC7eTbICW/037ZAoGAQmz9s7adC4FnOaMod5+E
ubtBv6JiJI/ZOP62gMSAQpxtytjDK34Sw8VzWOjCm+aC6jRO/46EnbNjp+GcU3EJ
pqiRQTY18iic/m9lRq75mY6LhlMZ8Nxm/yvXpOmRWwEbqUypChuYtBST1uhZrkdt
NSQQYH+A0YWoG8qBjcDmIX0=
-----END PRIVATE KEY-----";
  $v_public_key = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7gjiJHwsfjPJY3pvpQQ/
dRwMxK80nKf0A9Xdbx6/nwmowQv2nYB5Qz1roJ3sLi8seKLXqwxy6HKjEhsOpthm
mqqJI782AyO3fV7w3CfMjgbKHRfLuBrqyhCOg2pIsOwbUikKUrKeMUDxDsizcnH4
cy76xfSOqLOZ2weMFu1mAapg1ldfQCgEITiQb9b71YHVAGbMWlckOji8JuxrAdwG
1v0EXgLvnTdE6YEplwBw43mclLU1dY7ujlyDZhgsIKShHDl/lTHwgTPVOGmKhF7P
GhPLJQMDUT300Ab9oYwKFuU/1ZKp1ck+Ldb+3r7+sYXmXzl+2meeoeNo8Z+x7Sxj
AQIDAQAB
-----END PUBLIC KEY-----";
  $v_crafted_public_key = "-----BEGIN PUBLIC KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDuCOIkfCx+M8lj
em+lBD91HAzErzScp/QD1d1vHr+fCajBC/adgHlDPWugnewuLyx4oterDHLocqMS
Gw6m2GaaqokjvzYDI7d9XvDcJ8yOBsodF8u4GurKEI6Dakiw7BtSKQpSsp4xQPEO
yLNycfhzLvrF9I6os5nbB4wW7WYBqmDWV19AKAQhOJBv1vvVgdUAZsxaVyQ6OLwm
7GsB3AbW/QReAu+dN0TpgSmXAHDjeZyUtTV1ju6OXINmGCwgpKEcOX+VMfCBM9U4
aYqEXs8aE8slAwNRPfTQBv2hjAoW5T/VkqnVyT4t1v7evv6xheZfOX7aZ56h42jx
n7HtLGMBAgMBAAECggEBAJV6SKDGxZ3+4VGx+lgzAbKGCdKdf7l8kvSxoZt9QLIb
e7i2zYh8vCmocWTspvsdrpyXDj4g1Cv6iUL+cMX/kPdBbltfYQi5rilxrGlkKMVr
qBJ69fhIFvcLTKNj5AAOVN3UXeIuvr6JXJd/G4kb3vxeHSage9Ge71gnNibTr8IM
yQ9fSnrKQ9bqJTD+AaHQ0sCf/TY7vG9H5g3b/dg7cpul/qLdGAXx1k+ZPhpTEQLn
wm1yQa4Vhf7G8c2Vzcf+miGuYWxnTNCC5dG0bE+ZzV2huNWWXuWFervAX8X9COY3
ZbluvgpLUIQTZM37L/sO8VUiaD+eblEqAr7xYUqSV0ECgYEA/tb/0rkFYn7gYhir
FAYP68drJcUOBmnoknj24mV6oPq9WXNUlj72WATUjIy4BGAEMJRftWQgaqGJtkfx
GooiDJQKe/9EXyeD8+A72Wu0cvYW12VYGfrJZIAxbStAcHUkmgskQFf5AMPDsD78
xvRGc9vz7LpF8tZ2WpFYgyybSdkCgYEA7x5MdXsYHXGQdMkYvip71DnbADx3DMN3
SXYDPsYC/fkbiBojV8NZI8XkcXmrmYF4NnemMLmWDTafWuO4gvF7GKa1ylwElouo
PVPo6BpjR59/4dUCLJyjOjuS4nzyM5DxdZij8rAlh19QkPS3fOCXsYmJjQ/caeeT
0nlbnXmzQWkCgYABnhvBmqsOFQAXn1B2sBMpy/pCIx4TrFhtlZb7mOlOXEkXaEQB
MCUgKeevfLUuUe204Tw6SohqJNxa69n78SSGL+phHx2v/PA5fBLlLmnW0PkUJPCK
Oa1NlgK6Tqv6CsRQtgFk5yoNdzCmzb+NA7/uvFawCf6nq/TUXlfRfVb/AQKBgBPB
1iFy3Ug5hO0RXJkB259qdFztogyM7gNE2/nI1KB1f7/cIQV0X54kEL8LhZE2cF9X
vYSTIQHQfJQ+8pTnLPuZcrXYqoxh17HRiBH9dCyL0j4vonFbM+Vw3K8YEc8O79R+
mzVHNqKbzoVbovmPeRkzOwlLwTOC7eTbICW/037ZAoGAQmz9s7adC4FnOaMod5+E
ubtBv6JiJI/ZOP62gMSAQpxtytjDK34Sw8VzWOjCm+aC6jRO/46EnbNjp+GcU3EJ
pqiRQTY18iic/m9lRq75mY6LhlMZ8Nxm/yvXpOmRWwEbqUypChuYtBST1uhZrkdt
NSQQYH+A0YWoG8qBjcDmIX0=
-----END PRIVATE KEY-----";
  $v_crafted_private_key = "-----BEGIN PRIVATE KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7gjiJHwsfjPJY3pvpQQ/
dRwMxK80nKf0A9Xdbx6/nwmowQv2nYB5Qz1roJ3sLi8seKLXqwxy6HKjEhsOpthm
mqqJI782AyO3fV7w3CfMjgbKHRfLuBrqyhCOg2pIsOwbUikKUrKeMUDxDsizcnH4
cy76xfSOqLOZ2weMFu1mAapg1ldfQCgEITiQb9b71YHVAGbMWlckOji8JuxrAdwG
1v0EXgLvnTdE6YEplwBw43mclLU1dY7ujlyDZhgsIKShHDl/lTHwgTPVOGmKhF7P
GhPLJQMDUT300Ab9oYwKFuU/1ZKp1ck+Ldb+3r7+sYXmXzl+2meeoeNo8Z+x7Sxj
AQIDAQAB
-----END PUBLIC KEY-----";

  //fTest ($p_test, $p_src, $p_key_enc, $p_key_enc_type, $p_key_dec, $p_key_dec_type, $p_result);
/*  $v_ko += fTest ("Test 1a", "My super secret text to be encrypted", $v_public_key , "public" , $v_private_key, "private", "equal");
  $v_ko += fTest ("Test 2a", "My super secret text to be encrypted", $v_private_key, "private", $v_public_key , "public" , "equal");

  $v_ko += fTest ("Test 4a", "My super secret text to be encrypted", $v_public_key , "public" , $v_public_key , "public" , "decoded null");
  $v_ko += fTest ("Test 4b", "My super secret text to be encrypted", $v_public_key , "public" , $v_public_key , "public" , "different");

  $v_ko += fTest ("Test 6a", "My super secret text to be encrypted", $v_public_key , "private", $v_public_key , "private", "both null");
  $v_ko += fTest ("Test 6b", "My super secret text to be encrypted", $v_public_key , "private", $v_public_key , "private", "different");
*/

  error_reporting(0);

  $v_src_txt = "My super secret text to be encrypted";
//  $v_src_txt = "Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text Very long text ";

  $v_ko = 0;
  $first_line = "<div>&nbsp;</div><table><thead><tr><th>Test</th><th>Encoding Function</th><th>Encoding Key</th><th>Decoding Function</th><th>Decoding Key</th><th>Expected</th><th>Result</th><th>Details</th></tr></thead><tbody>";
  $last_line = "</tbody></table>";

  $prefix = "Test Normal (Original) Keys ";

  echo ($first_line);
  $i = 100;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 200;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 300;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_public_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_public_key, "public" , "equal");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_public_key, "public" , "equal");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 400;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_private_key, "private", "equal");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_private_key, "private", "equal");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_private_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_private_key, "public" , "different");
  echo ($last_line);

  $prefix = "Test Crafted Keys ";

  echo ($first_line);
  $i = 100;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 200;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 300;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 400;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_private_key, "public" , "different");
  echo ($last_line);

  $prefix = "Test Crafted Private Key And Normal Public Key ";

  echo ($first_line);
  $i = 100;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_crafted_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_crafted_private_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 200;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 300;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "private", $v_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_private_key, "public" , $v_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 400;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_crafted_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_crafted_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "private", $v_crafted_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_crafted_private_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_crafted_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_crafted_private_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_public_key, "public" , $v_crafted_private_key, "public" , "different");
  echo ($last_line);

  $prefix = "Test Normal Private Key And Crafted Public Key ";

  echo ($first_line);
  $i = 100;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_private_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 200;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_crafted_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_crafted_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 300;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_crafted_public_key, "private", "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_crafted_public_key, "public" , "decoded null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "private", $v_crafted_public_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_crafted_public_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_crafted_public_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_crafted_public_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_private_key, "public" , $v_crafted_public_key, "public" , "different");
  echo ($last_line);

  echo ($first_line);
  $i = 400;
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "private", $v_private_key, "public" , "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_private_key, "private", "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_private_key, "private", "different");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_private_key, "public" , "both null");
  $v_ko += fTest ($prefix . $i ++, $v_src_txt, $v_crafted_public_key, "public" , $v_private_key, "public" , "different");
  echo ($last_line);

  if ($v_ko > 0) {
    echo ("<div class='summary'>$v_ko error(s)</div>");
  } else {
    echo ("<div class='summary'>No error</div>");
  }
?>
</body>
</html>

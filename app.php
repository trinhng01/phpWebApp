<?php
/**
 * Trinh Nguyen
 * Server-side Web Programming
 * 04/14/2019: Upload File
 * 04/21/2019: Sign up + Log in + Authentication
 * IDE: PhpStorm
 */

$signupLink = 'http://localhost:63342/phpWebApp/signup.php?_ijt=rn8ltjgq1n80427m7i8ldacouq';
$loginLink = 'http://localhost:63342/phpWebApp/authenticate.php?_ijt=rn8ltjgq1n80427m7i8ldacouq';
echo <<<_END
    <html>
    <head>
    <title>Welcome</title>
    <html>
    <head>
    <style>
    .button {
      display: inline-block;
      border-radius: 4px;
      background-color: #f44336;
      border: none;
      color: #FFFFFF;
      text-align: center;
      font-size: 20px;
      padding: 10px 24px;
      width: 200px;
      transition: all 0.5s;
      cursor: pointer;
      margin: 5px;
      
    }
    
    .button span {
      cursor: pointer;
      display: inline-block;
      position: relative;
      transition: 0.5s;
    }
    
    .button span:after {
      content: '     >>';
      position: absolute;
      opacity: 0;
      top: 0;
      right: -20px;
      transition: 0.5s;
    }
    
    .button:hover span {
      padding-right: 25px;
    }
    
    .button:hover span:after {
      opacity: 1;
      right: 0;
    }
    </style>
    </head>
    <body>

    <button class="button" style="vertical-align:middle" onclick="window.location.href = '$loginLink';"><span>Log in</span></button>
    <button class="button" style="vertical-align:middle" onclick="window.location.href = '$signupLink';"><span>Sign up</span></button>
    
    </body>
    </html>
_END;
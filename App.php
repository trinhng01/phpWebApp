<?php
/**
 * Trinh Nguyen
 * Server-side Web Programming
 * 04/14/2019: Upload File
 * 04/21/2019: Sign up + Log in + Authentication
 * 04/28/2019: Fix sanitizing MySQL string
 * IDE: PhpStorm
 */


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

    <form action="userAuth.php" method="POST">
       <button class="button" style="vertical-align:middle"><span>Log in</span></button>
    </form>
    
    <form action="signUp.php" method="POST">
       <button class="button" style="vertical-align:middle"><span>Sign up</span></button>
    </form>
    
    
    </body>
    </html>
_END;
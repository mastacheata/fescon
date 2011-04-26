<?php

  /********************************************************
    FesterHead's PHP fsockopen and Outgoing Connection Test

    Version 1.1
    15-Oct-2010

    Instructions:
      In the configurables section, enter a host name (or IP) and a port number.
      Save the file to a PHP enabled web server and access it.
      PASS or FAIL displayed to screen

    Common ports to test are:
      3306  MySQL
      1221  SAM HTTP Handler

    CONFIGURABLES
    ******************************************************/

    $host_name_or_IP = "www.yourwebsite.com";

    $host_port = "1221";


 /********************************************************
  *
  * Copyright (C) Steve Kunitzer (FesterHead)
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
  * (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  *
  ********************************************************/


  // DO NOT EDIT BELOW THIS LINE.
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>FesterHead's PHP fsockopen and Outgoing Connection Test</title>
    <style type="text/css">
    <!--
      body
      {
        font-family:verdana;
      }
      h1.failed {
        color: #FF0000;
      }
      h1.passed {
        color: #00FF00;
      }
      a:link {
        color: #000099;
        text-decoration: underline;
      }
      li {
        padding: 20px 0 0 0;
      }
    -->
    </style>
  </head>
  <body>
    <h1>FesterHead's PHP fsockopen and Outgoing Connection Test</h2>
    <?php
      $fp = fsockopen( $host_name_or_IP, $host_port, $errno, $errstr, 5 );
      if ( !$fp ) {
        echo "<h1 class='failed'>FAILED</h1>Connection to <i>$host_name_or_IP</i> on port <i>$host_port</i> <b>failed</b>!";
        ?>
          <br/>
          <br/>
          Common causes of failure are:
          <br/>
          <ul>
            <li>
              Webserver does not allow outgoing activity on port <?php echo $host_port; ?>
              <ul><li><b>Action:</b> contact the web host.</li></ul>
            </li>
            <li>
              <?php echo $host_name_or_IP; ?> is not listening for connections on port <?php echo $host_port; ?>
              <ul><li><b>Action:</b> verify at <?php echo $host_name_or_IP; ?> that port <?php echo $host_port; ?> is opened in any firewall or router configuration.</li></ul>
            </li>
            <li>
              <?php echo $host_name_or_IP; ?> is not forwarding port <?php echo $host_port; ?> to the correct machine
              <ul><li><b>Action:</b> verify at <?php echo $host_name_or_IP; ?> that port <?php echo $host_port; ?> is forwarded to the correct internal machine for that runs the service.</li></ul>
            </li>
          </ul>
          <br/>
          <br/>
          For FREE HELP in setting up a router of firewall refere to the <a href="http://portforward.com/" title="Port Forward Guide" alt="Port Forward Guide">Port Forward Guide</a>.
        <?php
      }
      else {
        echo "<h1 class='passed'>PASSED</h1>Connection to <i>$host_name_or_IP</i> on port <i>$host_port</i> <b>succeeded</b>!";
        fclose( $fp );
      }
    ?>
  </body>
</html>

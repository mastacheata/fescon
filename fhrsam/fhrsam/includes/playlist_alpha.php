<?php

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

 /*******************************************************
  * Available variables:
  *
  *   $fhrsam_db       : a connection to the SAM database
  *   $fhrsam_conf_var : plugin configuration elements
  *
  *   $the_mode : integer : 1-5 corresponding to a mode; for alph listing $the_mode is 1
  *   $the_id   : integer : the artist ID, album ID, or song ID based on $the_mode; for alpha listing $the_id represent an alpha character
  *******************************************************/

  if( $the_id < 1 || $the_id > 27 )
  {
  ?>
    <br/>
    <br/>
    Invalid ID.
    <?php
  }
  $the_letter = chr ( 64 + $the_id );
  $the_regex = "REGEXP '^$the_letter'";
  if ( $the_id == 27 )
  {
     $the_letter = "a # or special character";
     $the_regex = "REGEXP '^[^a-zA-Z]'";
  }
  $artists = $fhrsam_db->get_results( "select artist, fhr_artistid from songlist where upper( artist ) " . $the_regex . " group by artist order by artist", ARRAY_A );
  ?>
  <br/>
  <br/>
  <?php _e( $the_letter ) ; ?> &raquo;
  <br/>
  <br/>
  <?php
    $counter = 0;
    foreach ($artists as $artist)
    {
      $counter++;
      ?>
        <div class="fhrsam_plugin_row"><span class="label"><?php printf( "%03s. ", $counter ) ; ?></span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_artist_link( $artist ); ?></span></div>
        <?php
    }

?>

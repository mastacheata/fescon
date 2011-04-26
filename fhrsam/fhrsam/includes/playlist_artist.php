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
  *   $the_mode : integer : 1-5 corresponding to a mode; for artist listing $the_mode is 2
  *   $the_id   : integer : the artist ID, album ID, or song ID based on $the_mode; for artist listing $the_id is an artist ID
  *******************************************************/

  $albums = $fhrsam_db->get_results( "select album, artist, picture, fhr_albumid, fhr_artistid from songlist where fhr_artistid = " . $the_id . " group by album order by album", ARRAY_A );

  $counter = 0;
  foreach ($albums as $album)
  {
    $counter++;
    if ( $counter == 1 )
    {
      ?>
        <br/>
        <br/>
        <?php _e( substr( $album["artist"], 0, 1 ) ); ?> &raquo; <?php gen_artist_link( $album ); ?> &raquo;
        <br/>
        <br/>
      <?php
    }
    ?>
      <div class="fhrsam_plugin_row"><span class="label"><?php printf( "%02s. ", $counter ) ; ?></span><span class="sepa">&nbsp;</span><span class="formw"><?php disp_cover_image( $album, true ); ?><?php gen_album_link( $album ); ?></span></div>
    <?php
  }

?>

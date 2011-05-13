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
  *   $the_mode : integer : 1-5 corresponding to a mode; for album listing $the_mode is 3
  *   $the_id   : integer : the artist ID, album ID, or song ID based on $the_mode; for album listing $the_id is an album ID
  *******************************************************/

  $song_info = $fhrsam_db->get_row( "select fhr_artistid, fhr_albumid, artist, album, albumyear, genre, picture from songlist where fhr_albumid = " . $the_id . " order by trackno", ARRAY_A );
  $songs = $fhrsam_db->get_results( "select id, replace(replace(filename, '\\\\', '/'), 'D:/FHR Archive/', 'preview/') as preview, fhr_artistid, fhr_albumid, artist, album, title, albumyear, genre, picture, trackno, info from songlist where fhr_albumid = " . $the_id . " order by trackno", ARRAY_A );

  // Generate a "Buy from Amazon" link and harvest the ASIN
  $buy_fromAmazon = gen_buy_amazon( $song_info["artist"], $song_info["fhr_albumid"], $song_info["album"] );

  $genre1 = explode( ":", $song_info["genre"] );
  $genre2 = explode( ",", $genre1[1] );

  $categories = $fhrsam_db->get_results( "select name from songlist sl, category c, categorylist cl where sl.id = cl.songid and sl.fhr_albumid = " . $song_info["fhr_albumid"] . " and cl.categoryid = c.id group by name order by name asc", ARRAY_A );

  ?>
  <br/>
  <br/>
  <?php _e( substr( $song_info["artist"], 0, 1 ) ); ?> &raquo; <?php gen_artist_link( $song_info ); ?> &raquo; <?php gen_album_link( $song_info ); ?> &raquo;
  <br/>
  <br/>
  <div id="fhrsam_plugin_col_one">
    <div style="text-align: center;"><?php disp_cover_image( $song_info ); ?></div>
    <div style="text-align: center;"><?php _e( $buy_fromAmazon ); ?></div>
    <hr/>
    <b>Genre:</b> <?php _e( $genre1[0] ); ?>
  </div>
  <div id="fhrsam_plugin_col_two">
    <div class="fhrsam_plugin_row"><span class="label">Artist:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_artist_link( $song_info ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label">Album:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_album_link( $song_info ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label">Year:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $song_info["albumyear"] ); ?></span></div>
    <br/>
    <?php foreach ($songs as $song) { ?>
      <div class="fhrsam_plugin_row">
        <span class="label"><?php printf( "%03s. ", $song["trackno"] ) ; ?></span>
        <span class="sepa">&nbsp;</span>
        <span class="formw">
          <?php gen_request_link( $song, true ); ?><span class="sepa">&nbsp;</span><?php gen_song_link( $song ); ?>
          <br/>
          <?php disp_song_preview( $song ); ?>
        </span>
      </div>
    <?php } ?>
  </div>
  <?php

?>

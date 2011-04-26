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
  *   $the_mode : integer : 1-5 corresponding to a mode; for title listing $the_mode is 4
  *   $the_id   : integer : the artist ID, album ID, or song ID based on $the_mode; for title listing $the_id is a song ID
  *******************************************************/

  $the_song = $fhrsam_db->get_row( "select *, id, artist, fhr_albumid, fhr_artistid, album, replace(replace(filename, '\\\\', '/'), 'D:/FHR Archive/', 'preview/') as preview, ( select count(*) as number_ratings from fhr_song_ratings where song_id = " . $the_id . " and rating not in ( 0 ) ) as number_ratings from songlist where id = " . $the_id, ARRAY_A );
  $genre1 = explode( ":", $the_song["genre"] );
  $genre2 = explode( ",", $genre1[1] );
  $buy_fromAmazon = gen_buy_amazon( $the_song["artist"], $the_song["fhr_albumid"], $the_song["album"] );
  $amazon_rating = get_amazon_rating( $the_song["artist"], $the_song["album"], $the_song["id"], $the_song["fhr_albumid"] );
  $categories = $fhrsam_db->get_results( "select name from category c, categorylist cl where cl.songid = " . $the_song["id"] . " and cl.categoryid = c.id order by name asc", ARRAY_A );
  ?>
  <br/>
  <br/>
  <?php _e( substr( $the_song["artist"], 0, 1 ) ); ?> &raquo; <?php gen_artist_link( $the_song ); ?> &raquo; <?php gen_album_link( $the_song ); ?> &raquo; <?php gen_song_link( $the_song ); ?>
  <br/>
  <br/>
  <div id="fhrsam_plugin_col_one">
    <div style="text-align: center;"><?php disp_cover_image( $the_song ); ?></div>
    <div style="text-align: center;"><?php _e( $buy_fromAmazon ); ?></div>
    <hr/>
    <b>Genre:</b> <?php _e( $genre1[0] ); ?>
    <?php ( count( $genre2 ) > 0 ) ? disp_genres( $genre2 ) : _e( "n/a" ); ?>
    <hr/>
    <b>Categories:</b>
    <?php disp_categories( $categories ); ?>
  </div>
  <div id="fhrsam_plugin_col_two">
    <div class="fhrsam_plugin_row"><span class="label">Artist:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_artist_link( $the_song ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label">Album:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_album_link( $the_song ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label">Title:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_song_link( $the_song ); ?></span></div>
    <br/>
    <?php if(strlen($now_playing["composer"]) > 0) { ?>
      <div class="fhrsam_plugin_row"><span class="label">Composer:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $the_song["composer"] ); ?></span></div>
    <?php } ?>
    <div class="fhrsam_plugin_row"><span class="label">Rating:</span><span class="sepa">&nbsp;</span><span class="formw"><?php ( $now_playing["rating"] <= 0 ) ? _e( $amazon_rating ) : _e( $the_song["rating"] ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label">Duration:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( convert_duration( $the_song["duration"] ) ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label">Year:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $the_song["albumyear"] ); ?></span></div>
    <br/>
    <div class="fhrsam_plugin_row"><span class="label">Added:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( date( get_option('date_format'), strtotime($the_song["date_added"])) ); ?></span></div>
    <div class="fhrsam_plugin_row"><span class="label"># Plays:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $the_song["count_played"] ); ?></span></div>
    <?php if($the_song["count_played"] > 0) { ?>
      <div class="fhrsam_plugin_row"><span class="label">Last Played:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( date( get_option('date_format') . " " . get_option('time_format'), strtotime($the_song["date_played"])) ); ?></span></div>
    <?php } ?>
    <div class="fhrsam_plugin_row"><span class="label"># Requests:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $the_song["count_requested"] ); ?></span></div>
    <?php if($the_song["count_requested"] > 0) { ?>
      <div class="fhrsam_plugin_row"><span class="label">Last Request:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( date( get_option('date_format') . " " . get_option('time_format'), strtotime($the_song["last_requested"])) ); ?></span></div>
    <?php } ?>
    <br/>
    <?php if( get_option( $fhrsam_conf_var["requests_enabled"] ) && ( get_option($fhrsam_conf_var["requests_role"] ) == 'anyone' || current_user_has_at_least( get_option($fhrsam_conf_var["requests_role"] ) ) ) ) { ?>
      <div class="fhrsam_plugin_row"><span class="label">Request:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_request_link( $the_song ); ?></span></div>
    <?php } ?>
    <?php if ( get_option( $fhrsam_conf_var["show_preview"] ) ) { ?>
      <div class="fhrsam_plugin_row"><span class="label">Preview:</span><span class="sepa">&nbsp;</span><span class="formw"><?php disp_song_preview( $the_song ); ?></span></div>
    <?php } ?>
    <br/>
    <b>Lyrics:</b>
    <?php disp_song_lyrics( $the_song["lyrics"] ); ?>
    <?php disp_lyrics_disc( ); ?>
  </div>
  <br style="clear: both;" />
  <br style="clear: both;" />
  <?php

?>

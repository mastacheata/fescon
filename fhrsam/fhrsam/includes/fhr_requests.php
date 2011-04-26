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
  *   $the_mode : integer : 1-5 corresponding to a mode; shouldn't need to use for requests but $the_mode is 5
  *   $the_id   : integer : the artist ID, album ID, or song ID based on $the_mode; for requests $the_id is a song ID
  *******************************************************/

  // what is the category ID to add the request?
  $request_cat_id = 2;

  // get user info
  global $current_user;
  get_currentuserinfo();

  // validate song ID exists
  $song_info = $fhrsam_db->get_row( "select id, artist, album, title, fhr_artistid, fhr_albumid, count_requested from songlist where id = " . $the_id, ARRAY_A );

  if ( empty( $song_info ) )
  {
    echo "Sorry, that song does not exist.";
  }
  else
  {
    // insert song ID into custom category
    $fhrsam_db->insert( 'categorylist', array( 'categoryid' => $request_cat_id, 'songid' => $the_id ), array( '%d', '%d' ) );

    // insert information into fhr_requests table
    $fhrsam_db->insert( 'fhr_requests', array( 'song_id' => $the_id, 'user_id' => $current_user->ID, 'requested' => current_time( 'mysql', $gmt = 0 ) ), array( '%d', '%d', '%s' ) );

    // update request info
    $fhrsam_db->update( 'songlist', array( 'count_requested' => $song_info['count_requested'] + 1, 'last_requested' => current_time( 'mysql', $gmt = 0 ) ), array( 'id' => $the_id ), array( '%d', '%s' ), array( '%d' ) )

    // display something useful to the user
    ?>
      <br/>
      <br/>
      <?php _e( substr( $song_info["artist"], 0, 1 ) ); ?> &raquo; <?php gen_artist_link( $song_info ); ?> &raquo; <?php gen_album_link( $song_info ); ?> &raquo; <?php gen_song_link( $song_info ); ?>
      <br/>
      <br/>
      Song requested.
    <?php
  }

?>

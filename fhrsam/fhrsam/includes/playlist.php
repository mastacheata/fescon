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

function fhrsamPlaylist()
{
  global $fhrsam_db, $fhrsam_conf_var;
  fhrsam_connect_db();

  $the_mode = $_GET["mode"];
  $the_id   = $_GET["id"];


  $has_mode = false;
  if( !empty( $the_mode ) || ( $the_mode <= 0 ) )
  {
    switch ( $the_mode )
    {
      case 1:  // alpha
      case 2:  // artist
      case 3:  // album
      case 4:  // title
      case 5:  // request
        $has_mode = true;
        break;
    }

  }

  $has_id = false;
  if( !empty( $the_id ) && ( preg_match("/^([0-9]+)$/D", $the_id) ) )
  {
    $has_id = true;
  }

  // Display an alpha list
  for ( $counter = 0; $counter <= 26; $counter++ )
  {
    if ( $counter == 0 )
    {
	echo is_dir('D:\') ? 'JA' : 'NOE';

      ?>Artist begins with <a href="?mode=1&amp;id=27">#</a><?php
    }
    else
    {
      ?>, <a href="?mode=1&amp;id=<?php echo( $counter ); ?>"><?php echo( chr( 64 + $counter ) ); ?></a><?php
    }

  }

  if ( !$has_mode || !$has_id )
  {
    ?>
      <br/>
      <br/>
      Select a link above to begin browsing the <?php echo( $configuration->station_name ); ?> playlist.
    <?php
  }
  else
  {
    switch ( $the_mode )
    {
      case 1:  // alpha
        include "playlist_alpha.php";
        break;
      case 2:  // artist
        include "playlist_artist.php";
        break;
      case 3:  // album
        include "playlist_album.php";
        break;
      case 4:  // title
        include "playlist_title.php";
        break;
      case 5:  // request
        include "requests.php";
        break;
      default: // invalid
        include "playlist_invalid.php";
    }
  }
  ?>
  <br style="clear: both;" />
  <br style="clear: both;" />
  <?php
}

add_shortcode('fhrsamPlaylist','fhrsamPlaylist');

?>

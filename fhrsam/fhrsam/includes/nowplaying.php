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

function fhrsamNowPlaying()
{
  global $fhrsam_db, $fhrsam_conf_var;
  fhrsam_connect_db();

  // **************************************************************************
  // Coming up section

  $coming_up = $fhrsam_db->get_results( "select * from fhr_coming_up_view", ARRAY_A );

  // **************************************************************************
  // Current show section

  $current_show = $fhrsam_db->get_row( "select * from fhr_current_show_view", ARRAY_A );

  // **************************************************************************
  // Now playing section

  $now_playing = $fhrsam_db->get_row( "select * from fhr_now_playing_view", ARRAY_A );

  $genre1 = explode( ":", $now_playing["genre"] );
  $genre2 = explode( ",", $genre1[1] );

  $categories = $fhrsam_db->get_results( "select name from category c, categorylist cl where cl.songid = " . $now_playing["id"] . " and cl.categoryid = c.id order by name asc", ARRAY_A );
  $secs_remain = ( round( $now_playing["duration"] / 1000 ) - ( strtotime($now_playing["database_time"]) - strtotime($now_playing["date_played"] ) ) );
  $buy_fromAmazon = gen_buy_amazon( $now_playing["artist"], $now_playing["fhr_albumid"], $now_playing["album"] );
  $amazon_rating = get_amazon_rating( $now_playing["artist"], $now_playing["album"], $now_playing["id"], $now_playing["fhr_albumid"] );

  // **************************************************************************


?>
    <!--
      db date: <?php _e( $now_playing["database_time"] ); ?>

      date played: <?php _e( $now_playing["date_played"] ); ?>

      duration: <?php _e( $now_playing["duration"] / 1000 ); ?>

      secs played: <?php _e( strtotime($now_playing["database_time"]) - strtotime($now_playing["date_played"] ) ); ?>

      remain: <?php _e( $secs_remain ); ?>

    -->
    <script type="text/javascript">
      var reload = false;

      function countDown()
      {
        countDownTime--;
        if ( document.getElementById( "countDownText" ) )
        {
          document.getElementById( "countDownText" ).innerHTML = secsToMins( countDownTime );
        }
        if ( countDownTime == 0 )
        {
          clearInterval( countdown_timer );
          document.location.reload();
          return;
        }
        else if ( countDownTime < 0 )
        {
          countDownTime = 10;
        }
      }
      function secsToMins( theValue )
      {
        if( theValue <= 0 )
        {
          return( "reloading..." );
        }
        var theMin = Math.floor( theValue / 60 );
        var theSec = ( theValue % 60 );
        if ( theSec < 10 )
        {
          theSec = "0" + theSec;
        }
        return( theMin + ":" + theSec );
      }
      var countDownTime = <?php _e( $secs_remain ); ?>;
      countdown_timer = setInterval ( "countDown();", 1000 );
    </script>

<div class="fhrsam_plugin_row"><span class="label">Coming Up:</span><span class="sepa">&nbsp;</span><span class="formw"><?php disp_coming_up( $coming_up ); ?></span></div>
<div class="fhrsam_plugin_row"><span class="label">Current Show:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $current_show["title"] ); ?> - <?php _e( $current_show["description"] ); ?></span></div>
<br/>
<hr/>
<div id="fhrsam_plugin_col_one">
  <div style="text-align: center;"><?php disp_cover_image( $now_playing ); ?></div>
  <div style="text-align: center;"><?php _e( $buy_fromAmazon ); ?></div>
  <hr/>
  <b>Genre:</b> <?php _e( $genre1[0] ); ?>
  <?php ( count( $genre2 ) > 0 ) ? disp_genres( $genre2 ) : _e( "n/a" ); ?>
  <hr/>
  <b>Categories:</b>
  <?php disp_categories( $categories ); ?>
</div>

<div id="fhrsam_plugin_col_two">
  <div class="fhrsam_plugin_row"><span class="label">Artist:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_artist_link( $now_playing ); ?></span></div>
  <div class="fhrsam_plugin_row"><span class="label">Album:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_album_link( $now_playing ); ?></span></div>
  <div class="fhrsam_plugin_row"><span class="label">Title:</span><span class="sepa">&nbsp;</span><span class="formw"><?php gen_song_link( $now_playing ); ?></span></div>
  <br/>
  <?php if(strlen($now_playing["composer"]) > 0) { ?>
    <div class="fhrsam_plugin_row"><span class="label">Composer:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $now_playing["composer"] ); ?></span></div>
  <?php } ?>
  <div class="fhrsam_plugin_row"><span class="label">Rating:</span><span class="sepa">&nbsp;</span><span class="formw"><?php ( $now_playing["rating"] <= 0 ) ? _e( $amazon_rating ) : _e( $now_playing["rating"] ); ?></span></div>
  <div class="fhrsam_plugin_row"><span class="label">Duration:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( convert_duration( $now_playing["duration"] ) ); ?> (Remain: <b id="countDownText"></b>)</span></div>
  <div class="fhrsam_plugin_row"><span class="label">Year:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $now_playing["albumyear"] ); ?></span></div>
  <br/>
  <div class="fhrsam_plugin_row"><span class="label">Added:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( date( get_option('date_format'), strtotime($now_playing["date_added"])) ); ?></span></div>
  <div class="fhrsam_plugin_row"><span class="label"># Plays:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $now_playing["count_played"] ); ?></span></div>
  <div class="fhrsam_plugin_row"><span class="label">Last Played:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( date( get_option('date_format') . " " . get_option('time_format'), strtotime($now_playing["date_played"])) ); ?></span></div>
  <div class="fhrsam_plugin_row"><span class="label"># Requests:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( $now_playing["count_requested"] ); ?></span></div>
  <?php if($now_playing["count_requested"] > 0) { ?>
    <div class="fhrsam_plugin_row"><span class="label">Last Request:</span><span class="sepa">&nbsp;</span><span class="formw"><?php _e( date( get_option('date_format') . " " . get_option('time_format'), strtotime($now_playing["last_requested"])) ); ?></span></div>
  <?php } ?>
  <br/>
  <b>Lyrics:</b>
  <?php disp_song_lyrics( $now_playing["lyrics"] ); ?>
  <?php disp_lyrics_disc( ); ?>
</div>
<br style="clear: both;" />
<br style="clear: both;" />

<?php
}

add_shortcode('fhrsamNowPlaying','fhrsamNowPlaying');

?>

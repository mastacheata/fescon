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

function fhrsam_connect_db()
{
  global $fhrsam_db, $fhrsam_conf_var;

  if ( ! isset($fhrsam_db) )
  {
    $fhrsam_db = new wpdb( get_option( $fhrsam_conf_var["database_username"] ),
                           get_option( $fhrsam_conf_var["database_password"] ),
                           get_option( $fhrsam_conf_var["database_schema"] ),
                           get_option( $fhrsam_conf_var["database_server"] ) );
  }
}

function convert_duration( $duration )
{
  $return_value = "";

  $ss = round( $duration / 1000 );
  $mm = ( int )( $ss / 60 );
  $ss = ( $ss % 60 );

  if( $ss < 10 )
  {
    $ss = "0" . $ss;
  }

  $return_value = $mm . ":" . $ss;

  return $return_value;
}

function gen_artist_link( $the_array )
{
  global $fhrsam_conf_var;

  _e( '<a href="' . get_page_link( get_option( $fhrsam_conf_var["page_id_playlist"] ) ) . '?mode=2&amp;id=' . $the_array["fhr_artistid"] . '" title="View list of albums by ' . esc_html( $the_array["artist"] ) . '">' . esc_html( $the_array["artist"] ) . '</a>' );
}

function gen_album_link( $the_array  )
{
  global $fhrsam_conf_var;

  _e( '<a href="' . get_page_link( get_option( $fhrsam_conf_var["page_id_playlist"] ) ) . '?mode=3&amp;id=' . $the_array["fhr_albumid"] . '" title="View list of songs from ' . esc_html( $the_array["album"] ) . ' by ' . esc_html( $the_array["artist"] ) . '">' . esc_html( $the_array["album"] ) . '</a>' );
}

function gen_song_link( $the_array )
{
  global $fhrsam_conf_var;

  _e( '<a href="' . get_page_link( get_option( $fhrsam_conf_var["page_id_playlist"] ) ) . '?mode=4&amp;id=' . $the_array["id"] . '" title="View ' . esc_html( $the_array["title"] ) . ' info from ' . esc_html( $the_array["album"] ) . ' by ' . esc_html( $the_array["artist"] ) . '">' . esc_html( $the_array["title"] ) . '</a>' );
}

function gen_request_link( $the_array, $small_image = false )
{
  global $fhrsam_conf_var;

  if( get_option( $fhrsam_conf_var["requests_enabled"] ) && ( get_option($fhrsam_conf_var["requests_role"] ) == 'anyone' || current_user_has_at_least( get_option($fhrsam_conf_var["requests_role"] ) ) ) )
  {
    if ( get_option( $fhrsam_conf_var["requests_type"] ) == 'other' )
    {
      _e( '<a href="' . get_page_link( get_option( $fhrsam_conf_var["page_id_playlist"] ) ) . '?mode=5&amp;id=' . $the_array["id"] . '"><img src="' . plugins_url( 'fhrsam/media/' . ( ( $small_image ) ? 'r.png' : 'request.png' ) ) . '" title="request" alt="request"/></a>' );
      return;
    }
    else
    {
      $song_requester_id = 0;
      $custom_template = "";
      if( get_option( $fhrsam_conf_var["requests_type"] ) == 'hosted' )
      {
        $song_requester_id = get_option( $fhrsam_conf_var["requests_fhr_id"] );
        if( ( preg_match("/^([0-9]+)$/D", $song_requester_id) ) && ( $song_requester_id > 0 ) )
        {
          $custom_template = ", " . $song_requester_id;
        }
      }
      _e( '<a href="javascript:fhrsam_requests( ' . $the_array["id"] . ', \'' . get_option( $fhrsam_conf_var["sam_host"] ) . '\', ' . get_option( $fhrsam_conf_var["sam_port"] ) . $custom_template . ' );"><img src="' . plugins_url( 'fhrsam/media/' . ( ( $small_image ) ? 'r.png' : 'request.png' ) ) . '" title="request" alt="request"/></a>' );
    }
  }
}

function disp_cover_image( $the_array, $thumbnail = false )
{
  global $fhrsam_conf_var;

  if( $thumbnail )
  {
    gen_thumbnail( $the_array["picture"] );
  }

  _e( '<img src="' . ( ( $thumbnail ) ? get_option( $fhrsam_conf_var["thumb_art_url"] ) : get_option( $fhrsam_conf_var["cover_art_url"] ) ) . $the_array["picture"] . '" title="' . esc_html( $the_array["album"] ) . '" alt="' . esc_html( $the_array["album"] ) . '" border="0" />' );
}

function disp_song_preview( $the_array )
{
  global $fhrsam_conf_var;

  if( get_option( $fhrsam_conf_var["show_preview"] ) )
  {
    _e( '<object type="application/x-shockwave-flash" data="' . plugins_url( 'fhrsam/media/player.swf' ) . '" id="audioplayer' . $the_array["id"] . '" height="24" width="250"><param name="movie" value="' . plugins_url( 'fhrsam/media/player.swf' ) . '"><param name="FlashVars" value="playerID=' . $the_array["id"] . '&amp;bg=0xf8f8f8&amp;leftbg=0xeeeeee&amp;lefticon=0x666666&amp;rightbg=0xcccccc&amp;rightbghover=0x999999&amp;righticon=0x666666&amp;righticonhover=0xFFFFFF&amp;text=0x666666&amp;slider=0x666666&amp;track=0xFFFFFF&amp;border=0x666666&amp;loader=0x9FFFB8&amp;soundFile=../../' . $the_array["preview"] . '"><param name="quality" value="high"><param name="menu" value="false"><param name="wmode" value="transparent"></object>' );
  }
}

function disp_song_lyrics( $lyrics )
{
  if( substr_count( nl2br( $lyrics ), '<br />' ) <= 3 )
  {
    $the_height = "height:50px;";
  }
  else
  {
    $the_height = "height:150px;";
  }

  if ( $lyrics == "***" || $lyrics == "n/a" )
  {
    $lyrics = "Lyrics not available.  Please help by finding them.";
  }

  _e( '<div id="fhrsam_plugin_lyrics" style="' . $the_height . '">' . nl2br( esc_html( $lyrics ) ) . '</div>' );
}

function disp_lyrics_disc( )
{
  global $fhrsam_conf_var;

  _e( '<div id="fhrsam_plugin_lyrics_disclaimer">' . get_option( $fhrsam_conf_var["station_name"] ) . ' in no way claims ownership of any lyrics.  Lyrics are property and copyright of their respective owners and are provided for educational and archival purposes only.</div>' );
}

function disp_categories( $categories )
{
  _e( "<ul>" );
  foreach ( $categories as $category )
  {
    _e( "<li>" . esc_html( $category["name"] ) . "</li>" );
  }
  _e( "</ul>" );
}

function disp_genres( $genres )
{
  _e( "<ul>" );
  foreach ( $genres as $genre )
  {
    if ( strlen( $genre ) <= 0 )
    {
      _e( "<li>n/a</li>" );
    }
    else
    {
      _e( "<li>" . esc_html( $genre ) . "</li>" );
    }
  }
  _e( "</ul>" );
}

function disp_coming_up( $songs )
{
  $counter = 0;
  foreach ($songs as $song)
  {
    if ( $counter > 0 )
    {
      echo( " | " );
    }

    if ( $song["request_id"] > 0)
    {
      _e( "~" );
    }

    //_e( esc_html( $song["artist"] ) );
    gen_artist_link( $song );

    if ( $song["request_id"] > 0)
    {
      _e( "~" );
    }

    $counter++;
  }
}

function get_amazon_rating( $artist, $album, $songid, $albumid )
{
  global $fhrsam_db, $fhrsam_conf_var;

  if ( !get_option( $fhrsam_conf_var["amazon_buy"] ) )
  {
    return;
  }

  if ( empty ( $artist ) || empty ( $album ) || empty ( $songid ) )
  {
    return;
  }

  $result = $fhrsam_db->get_row( "select rating from songlist where id = $songid", ARRAY_A );
  $rating = $result["rating"];

  if( $rating <= 0 )
  {
    $pos = strpos($album, "(");
    if ($pos !== false)
    {
      $album = substr( $album, 0, $pos );
    }

    $public_key = get_option($fhrsam_conf_var["amazon_access_key"]);
    $private_key = get_option($fhrsam_conf_var["amazon_private_key"]);

    $pxml = aws_signed_request(get_option($fhrsam_conf_var["amazon_region"]),
                               array("Operation"     => "ItemSearch",
                                     "Title"         => $album,
                                     "SearchIndex"   => "Music",
                                     "Artist"        => $artist,
                                     "AssociateTag"  => get_option($fhrsam_conf_var["amazon_associate_tag"]),
                                     "ResponseGroup" => "Reviews"), $public_key, $private_key);

    if ($pxml !== False)
    {
      $rating = $pxml->Items->Item[0]->CustomerReviews->AverageRating;

      $zero_ratings = $fhrsam_db->get_results("select id from songlist where rating <= 0 and fhr_albumid = $albumid");
      foreach ($zero_ratings as $zero_rating)
      {
        $fhrsam_db->update( 'songlist', array( 'rating' => $rating ), array( 'id' => $zero_rating->id ), array( '%f' ), array( '%d' ) );
      }
    }
  }

  return $rating;
}

function gen_buy_amazon( $artist, $album_id, $album )
{
  global $fhrsam_db, $fhrsam_conf_var;

  if ( !get_option( $fhrsam_conf_var["amazon_buy"] ) )
  {
    return;
  }

  if ( empty ( $artist ) || empty ( $album_id ) || empty ( $album ) )
  {
    return;
  }

  $result = $fhrsam_db->get_row( "select a.amazon_asin as asin, a.amazon_url as url from fhr_amazon_asin a where a.fhr_albumid = $album_id", ARRAY_A );
  $asin = $result["asin"];
  $url = $result["url"];

  if ( empty( $asin ) )
  {

    $pos = strpos($album, "(");
    if ($pos !== false)
    {
      $album = substr( $album, 0, $pos );
    }

    $public_key = get_option($fhrsam_conf_var["amazon_access_key"]);
    $private_key = get_option($fhrsam_conf_var["amazon_private_key"]);

    $pxml = aws_signed_request(get_option($fhrsam_conf_var["amazon_region"]),
                               array("Operation"     => "ItemSearch",
                                     "Title"         => $album,
                                     "SearchIndex"   => "Music",
                                     "Artist"        => $artist,
                                     "AssociateTag"  => get_option($fhrsam_conf_var["amazon_associate_tag"]),
                                     "ResponseGroup" => "Small"), $public_key, $private_key);

    if ( ($pxml === False) || ($pxml->Items->Request->Errors->Error))
    {
      $fhrsam_db->insert( 'fhr_amazon_asin', array( 'fhr_albumid' => $album_id, 'amazon_asin' => 'NOT FOUND' ), array( '%d', '%s' ) );
      return "<img src='" . plugins_url( 'fhrsam/media/buyfromamazon.gif' ) . "' style='opacity:0.4;filter:alpha(opacity=40)' title='Amazon ASIN not found' alt='Amazon ASIN not found'/>";
    }
    else
    {
      $asin = $pxml->Items->Item[0]->ASIN;
      $url = $pxml->Items->Item[0]->DetailPageURL;
      $fhrsam_db->insert( 'fhr_amazon_asin', array( 'fhr_albumid' => $album_id, 'amazon_asin' => $asin, 'amazon_url' => $url ), array( '%d', '%s', '%s' ) );
    }
  }
  else if ( $asin == "NOT FOUND" )
  {
    return "<img src='" . plugins_url( 'fhrsam/media/buyfromamazon.gif' ) . "' style='opacity:0.4;filter:alpha(opacity=40)' title='Amazon ASIN not found' alt='Amazon ASIN not found'/>";
  }

  return "<a href='" . $url . "' target='_blank' title='Buy from Amazon and support ". get_option( $fhrsam_conf_var["station_name"] ) . "'><img src='" . plugins_url( 'fhrsam/media/buyfromamazon.gif' ) . "' border='0' alt='Buy from Amazon and support ". get_option( $fhrsam_conf_var["station_name"] ) . "'/></a>";
}

 function aws_signed_request($region, $params, $public_key, $private_key)
 {
     /*
     Copyright (c) 2009 Ulrich Mierendorff

     Permission is hereby granted, free of charge, to any person obtaining a
     copy of this software and associated documentation files (the "Software"),
     to deal in the Software without restriction, including without limitation
     the rights to use, copy, modify, merge, publish, distribute, sublicense,
     and/or sell copies of the Software, and to permit persons to whom the
     Software is furnished to do so, subject to the following conditions:

     The above copyright notice and this permission notice shall be included in
     all copies or substantial portions of the Software.

     THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
     THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
     FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
     DEALINGS IN THE SOFTWARE.
     */

     // some paramters
     $method = "GET";
     $host = "ecs.amazonaws.".$region;
     $uri = "/onca/xml";

     // additional parameters
     $params["Service"] = "AWSECommerceService";
     $params["AWSAccessKeyId"] = $public_key;
     // GMT timestamp
     $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
     // API version
     $params["Version"] = "2009-03-31";

     // sort the parameters
     ksort($params);

     // create the canonicalized query
     $canonicalized_query = array();
     foreach ($params as $param=>$value)
     {
         $param = str_replace("%7E", "~", rawurlencode($param));
         $value = str_replace("%7E", "~", rawurlencode($value));
         $canonicalized_query[] = $param."=".$value;
     }
     $canonicalized_query = implode("&", $canonicalized_query);

     // create the string to sign
     $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;

     // calculate HMAC with SHA256 and base64-encoding
     $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));

     // encode the signature for the request
     $signature = str_replace("%7E", "~", rawurlencode($signature));

     // create request
     $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;

     // do request
     $response = @file_get_contents($request);

     if ($response === False)
     {
         return False;
     }
     else
     {
         // parse XML
         $pxml = simplexml_load_string($response);
         if ($pxml === False)
         {
             return False; // no xml
         }
         else
         {
             return $pxml;
         }
     }
 }

function gen_thumbnail( $input_image )
{

  global $fhrsam_conf_var;

  $input_file = get_option( $fhrsam_conf_var["cover_art_dir"] ) . $input_image;
  $output_file = get_option( $fhrsam_conf_var["thumb_art_dir"] ) . $input_image;

  $cover_art_thumbnail_height = get_option( $fhrsam_conf_var["thumb_height"] );
  $cover_art_thumbnail_width = get_option( $fhrsam_conf_var["thumb_width"] );

  if ( file_exists( $input_file ) && !file_exists( $output_file ) )
  {
    // Get the image file extension
    $file_extension = substr( strrchr( $input_file, '.' ), 1 );

    if ( preg_match( '/jpg|jpeg/', $file_extension ) )
    {
      $src_img = imagecreatefromjpeg($input_file);
    }
    else if ( preg_match( '/png/', $file_extension ) )
    {
      $src_img = imagecreatefrompng( $input_file );
    }
    else if ( preg_match( '/gif/', $file_extension ) )
    {
      $src_img = imagecreatefromgif( $input_file );
    }
    else
    {
      return;
    }

    // Sanity check to make sure we have an image
    if (!$src_img)
    {
      return;
    }

    // Get the dimensions of the original image by using imageSX() and imageSY()
    //   and calculate the dimensions of the thumbnail accordingly keeping the correct aspect ratio.
    $old_x = imageSX( $src_img );
    $old_y = imageSY( $src_img );

    if ($old_x > $old_y)
    {
      $thumb_w = $cover_art_thumbnail_width;
      $thumb_h = $old_y * ( $cover_art_thumbnail_height / $old_x );
    }
    else if ( $old_x < $old_y )
    {
      $thumb_w = $old_x * ( $cover_art_thumbnail_width / $old_y );
      $thumb_h = $cover_art_thumbnail_height;
    }
    else
    {
      $thumb_w = $cover_art_thumbnail_width;
      $thumb_h = $cover_art_thumbnail_height;
    }

    // Create the image as a true color version using imagecreatetruecolor()
    //   and resize and copy the original image into the new thumbnail image on the top left position.
    $dst_img = imagecreatetruecolor( $thumb_w, $thumb_h );
    imagecopyresampled( $dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y );

    // Write the correct thumbnail image type
    if ( preg_match( '/jpg|jpeg/', $file_extension ) )
    {
      imagejpeg($dst_img, $output_file);
    }
    else if ( preg_match( '/png/', $file_extension ) )
    {
      imagepng( $dst_img, $output_file );
    }
    else if ( preg_match( '/gif/', $file_extension ) )
    {
      imagegif( $dst_img, $output_file );
    }

    // Destroy the two image objects to free memory
    imagedestroy( $dst_img );
    imagedestroy( $src_img );
  }
}

function current_user_has_at_least( $role )
{
  $the_roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' );
  foreach ( $the_roles as $the_role )
  {
    if ( current_user_can( $the_role ) )
    {
      return true;
    }
    if ( $role == $the_role )
    {
      break;
    }
  }
  return false;
}

?>

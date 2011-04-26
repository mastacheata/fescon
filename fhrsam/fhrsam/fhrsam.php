<?php
/*
Plugin Name: FesterHead's SAM Plugin
Plugin URI: http://www.festerhead.com
Description: Plugin which adds shortcodes that can be used on pages to display SAM information.
Version: 0.1
Author: FesterHead (Steve Kunitzer)
Author URI: http://www.festerhead.com
*/

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

// CONFIG
global $fhrsam_db, $fhrsam_conf_var;

$fhrsam_conf_var = array (
  "database_server"       => "fhrsam_db_server",
  "database_schema"       => "fhrsam_db_schema",
  "database_username"     => "fhrsam_db_username",
  "database_password"     => "fhrsam_db_password",

  "sam_host"              => "fhrsam_sam_host",
  "sam_port"              => "fhrsam_sam_port",

  "requests_enabled"      => "fhrsam_requests_enabled",
  "requests_role"         => "fhrsam_requests_role",
  "requests_type"         => "fhrsam_requests_type",
  "requests_fhr_id"       => "fhrsam_requests_fhr_id",

  "station_name"          => "fhrsam_station_name",
  "show_preview"          => "fhrsam_show_preview",

  "page_id_now_playing"   => "fhrsam_page_id_now_playing",
  "page_id_playlist"      => "fhrsam_page_id_playlist",

  "amazon_buy"            => "fhrsam_amazon_buy",
  "amazon_associate_tag"  => "fhrsam_amazon_associate_tag",
  "amazon_access_key"     => "fhrsam_amazon_access_key",
  "amazon_private_key"    => "fhrsam_amazon_private_key",
  "amazon_region"         => "fhrsam_amazon_region",

  "cover_art_dir"         => "fhrsam_cover_art_dir",
  "cover_art_url"         => "fhrsam_cover_art_url",
  "thumb_art_dir"         => "fhrsam_thumb_art_dir",
  "thumb_art_url"         => "fhrsam_thumb_art_url",
  "thumb_height"          => "fhrsam_thumb_height",
  "thumb_width"           => "fhrsam_thumb_width",

  "preserve_settings"     => "fhrsam_preserve_settings"
);

require_once( dirname( __FILE__ ) . '/includes/addremove.php' );
require_once( dirname( __FILE__ ) . '/includes/options.php' );
require_once( dirname( __FILE__ ) . '/includes/functions.php' );
require_once( dirname( __FILE__ ) . '/includes/nowplaying.php' );
require_once( dirname( __FILE__ ) . '/includes/playlist.php' );

wp_enqueue_style( 'fhrsam-css', plugins_url( '/css/fhrsam.css', __FILE__ ) );
wp_enqueue_script( 'fhrsam-js', plugins_url( '/js/fhrsam.js', __FILE__ ) );

?>

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

function fhrsam_plugin_options()
{
    global $fhrsam_conf_var, $wpdb, $current_user;

    $np_page_id_row = $wpdb->get_row("select id from $wpdb->posts where post_type='page' and post_content like '[fhrsamNowPlaying]%' order by post_date_gmt desc");
    $np_page_id = 0;
    if( !empty( $np_page_id_row->id ) )
    {
      $np_page_id = $np_page_id_row->id;
    }
    update_option( $fhrsam_conf_var["page_id_now_playing"], $np_page_id );

    $pl_page_id_row = $wpdb->get_row("select id from $wpdb->posts where post_type='page' and post_content like '[fhrsamPlaylist]%' order by post_date_gmt desc");
    $pl_page_id = 0;
    if( !empty( $pl_page_id_row->id ) )
    {
      $pl_page_id = $pl_page_id_row->id;
    }
    update_option( $fhrsam_conf_var["page_id_playlist"], $pl_page_id );

    if( empty( $np_page_id_row->id ) ) echo "foo";

    $hidden_field_name = 'fhrsam_submit_hidden';
    if( $_POST[ $hidden_field_name ] == 'Y' )
    {
      foreach ( $fhrsam_conf_var as $fhrsam_variable )
      {
        update_option( $fhrsam_variable, $_POST[ $fhrsam_variable ] );
      }
      ?><div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
    <?php } ?>

<div class="wrap">
<h2>FesterHead's SAM Wordpress Plugin</h2>
<p>
  FHRSAM is a free plugin developed by <a href="http://www.festerhead.com" title="FesterHead">FesterHead</a> (Steve Kunitzer).
  <br/>
  It provides shorttags, <em>[fhrsamNowPlaying]</em> and <em>[fhrsamPlaylist]</em>, for pages to show Now Playing information and a Playlist/Request interface.
  <br/>
  See the <a href="http://www.festerhead.com/fhrsam/manual.pdf" title="MANUAL" target="_blank">MANUAL PDF</a> for more information and configuration description.
</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="10071304"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"> Use PayPal to donate or...<br/>Get me something from my <a href="http://amzn.com/w/2ZXLIVB4PMKZ" title="FesterHead's Amazon.com music wishlist">Amazon.com Music Wish List</a> (preferred)</form>
<br/>Donations encourage continued development.
<hr/>
<form name="form1" method="post" action="">
<h3>Database Information</h3>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<table class="form-table">
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["database_server"]; ?>"><?php _e('Server') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["database_server"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["database_server"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["database_server"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["database_schema"]; ?>"><?php _e('Schema') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["database_schema"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["database_schema"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["database_schema"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["database_username"]; ?>"><?php _e('Username') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["database_username"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["database_username"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["database_username"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["database_password"]; ?>"><?php _e('Password') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["database_password"]; ?>" type="password" id="<?php echo $fhrsam_conf_var["database_password"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["database_password"] ); ?>" class="regular-text code" /></td>
  </tr>
</table>
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
<hr/>
<h3>SAM Information</h3>
<table class="form-table">
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["sam_host"]; ?>"><?php _e('SAM host') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["sam_host"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["sam_host"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["sam_host"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["sam_port"]; ?>"><?php _e('SAM port') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["sam_port"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["sam_port"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["sam_port"] ); ?>" class="regular-text code" /></td>
  </tr>
</table>
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
<hr/>
<h3>Requests Information</h3>
<table class="form-table">
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["requests_enabled"]; ?>"><?php _e('Enable requests?') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["requests_enabled"]; ?>" type="checkbox" id="<?php echo $fhrsam_conf_var["requests_enabled"]; ?>" value="1" <?php checked( '1', get_option( $fhrsam_conf_var["requests_enabled"] ) ); ?> /><?php _e('Check to enable requests.') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["requests_role"]; ?>"><?php _e('What minimum role is needed to make requests?') ?></label></th>
    <td>
      <?php $requests_role = get_option($fhrsam_conf_var["requests_role"]);?>
      <select name="<?php echo $fhrsam_conf_var["requests_role"]; ?>" id="<?php echo $fhrsam_conf_var["requests_role"]; ?>">
        <option <?php if ($requests_role == "administrator") echo('selected' );?> value="administrator">Administrator</option>
        <option <?php if ($requests_role == "editor") echo('selected' );?> value="editor">Editor</option>
        <option <?php if ($requests_role == "author") echo('selected' );?> value="author">Author</option>
        <option <?php if ($requests_role == "contributer") echo('selected' );?> value="contributer">Contributer</option>
        <option <?php if ($requests_role == "subscriber") echo('selected' );?> value="subscriber">Subscriber</option>
        <option <?php if ($requests_role == "anyone" || empty($requests_role)) echo('selected' );?> value="anyone">Anyone</option>
      </select>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["requests_type"]; ?>"><?php _e('What type of request mechanism?') ?></label></th>
    <td>
      <?php $requests_type = get_option($fhrsam_conf_var["requests_type"]);?>
      <select name="<?php echo $fhrsam_conf_var["requests_type"]; ?>" id="<?php echo $fhrsam_conf_var["requests_type"]; ?>">
        <option <?php if ($requests_type == "nofrills" || empty($requests_type)) echo('selected' );?> value="nofrills">FesterHead's No Frills Song Requester Service</option>
        <option <?php if ($requests_type == "hosted") echo('selected' );?> value="hosted">FesterHead's Hosted Song Requester Service (supply ID)</option>
        <option <?php if ($requests_type == "other") echo('selected' );?> value="other">Other - I have smartitude! (edit code in request.php)</option>
      </select>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["requests_fhr_id"]; ?>"><?php _e('FesterHead\'s Hosted Song Requester Service ID') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["requests_fhr_id"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["requests_fhr_id"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["requests_fhr_id"] ); ?>" class="regular-text code" /> Used by FesterHead's Hosted Song Requester Service requests</td>
  </tr>
</table>
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
<hr/>
<h3>Amazon Information</h3>
<table class="form-table">
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["amazon_buy"]; ?>"><?php _e('Display Amazon.com buy links?') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["amazon_buy"]; ?>" type="checkbox" id="<?php echo $fhrsam_conf_var["amazon_buy"]; ?>" value="1" <?php checked( '1', get_option( $fhrsam_conf_var["amazon_buy"] ) ); ?> /><?php _e('Check to enable Amazon buy now links and ASIN value harvesting. (recommended)') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["amazon_associate_tag"]; ?>"><?php _e('Associate tag') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["amazon_associate_tag"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["amazon_associate_tag"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["amazon_associate_tag"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["amazon_access_key"]; ?>"><?php _e('Access key') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["amazon_access_key"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["amazon_access_key"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["amazon_access_key"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["amazon_private_key"]; ?>"><?php _e('Private key') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["amazon_private_key"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["amazon_private_key"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["amazon_private_key"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["amazon_asin_region"]; ?>"><?php _e('Amazon region') ?></label></th>
    <td>
      <?php $asin_region = get_option($fhrsam_conf_var["amazon_region"]);?>
      <select name="<?php echo $fhrsam_conf_var["amazon_region"]; ?>" id="<?php echo $fhrsam_conf_var["amazon_region"]; ?>">
        <option <?php if ($asin_region == "ca") echo('selected' );?> value="ca">ca</option>
        <option <?php if ($asin_region == "com" || empty($asin_region)) echo('selected' );?> value="com">com</option>
        <option <?php if ($asin_region == "co.uk") echo('selected' );?> value="co.uk">co.uk</option>
        <option <?php if ($asin_region == "de") echo('selected' );?> value="de">de</option>
        <option <?php if ($asin_region == "fr") echo('selected' );?> value="fr">fr</option>
        <option <?php if ($asin_region == "jp") echo('selected' );?> value="jp">jp</option>
      </select>
    Amazon region to harvest ASIN values and build buy now links.</td>
  </tr>
</table>
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
<hr/>
<h3>Cover Art Information</h3>
<table class="form-table">
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["cover_art_dir"]; ?>"><?php _e('Cover art directory for making thumbnails') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["cover_art_dir"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["cover_art_dir"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["cover_art_dir"] ); ?>" class="regular-text code" /> Specify full path.  Use / to separate folders.  End with a /</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["cover_art_url"]; ?>"><?php _e('Cover art directory for web access') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["cover_art_url"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["cover_art_dir"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["cover_art_url"] ); ?>" class="regular-text code" /> Can be absolute or relative path.  End with a /</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["thumb_art_dir"]; ?>"><?php _e('Thumbnail art directory for making thumbnails') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["thumb_art_dir"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["thumb_art_dir"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["thumb_art_dir"] ); ?>" class="regular-text code" /> Specify full path.  Use / to separate folders.  End with a /</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["thumb_art_url"]; ?>"><?php _e('Thumbnail art directory for web access') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["thumb_art_url"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["thumb_art_url"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["thumb_art_url"] ); ?>" class="regular-text code" /> Can be absolute or relative path.  End with a /</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["thumb_height"]; ?>"><?php _e('Thumbnail height') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["thumb_height"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["thumb_height"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["thumb_height"] ); ?>" class="regular-text code" /> In pixels.</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["thumb_width"]; ?>"><?php _e('Thumbnail width') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["thumb_width"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["thumb_width"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["thumb_width"] ); ?>" class="regular-text code" /> In pixels.</td>
  </tr>
</table>
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
<hr/>
<h3>Other Information</h3>
<table class="form-table">
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["station_name"]; ?>"><?php _e('Station Name') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["station_name"]; ?>" type="text" id="<?php echo $fhrsam_conf_var["station_name"]; ?>" value="<?php echo get_option( $fhrsam_conf_var["station_name"] ); ?>" class="regular-text code" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["page_id_now_playing"]; ?>"><?php _e('Now Playing page ID') ?></label></th>
    <td>
    <?php
      if( $np_page_id <= 0 )
      {
        ?>
          <em><b>Did not find a page with [fhrsamNowPlaying] shortcode!</b></em>
        <?php
      }
      else
      {
        ?>
          Found page ID <?php echo $np_page_id; ?> with [fhrsamNowPlaying] shortcode
        <?php
      }
    ?>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $fhrsam_conf_var["page_id_playlist"]; ?>"><?php _e('Playlist page ID') ?></label></th>
    <td>
    <?php
      if( $pl_page_id <= 0 )
      {
        ?>
          <em><b>Did not find a page with [fhrsamPlaylist] shortcode!</b></em>
        <?php
      }
      else
      {
        ?>
          Found page ID <?php echo $pl_page_id; ?> with [fhrsamPlaylist] shortcode
        <?php
      }
    ?>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["show_preview"]; ?>"><?php _e('Show Flash preview?') ?></label></th>
    <td><input disabled name="<?php echo $fhrsam_conf_var["show_preview"]; ?>" type="checkbox" id="<?php echo $fhrsam_conf_var["show_preview"]; ?>" value="1" <?php checked( '1', get_option( $fhrsam_conf_var["show_preview"] ) ); ?> /><?php _e('Check to display Flash song preview option.') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row"> <label for="<?php echo $fhrsam_conf_var["preserve_settings"]; ?>"><?php _e('Save on uninstall?') ?></label></th>
    <td><input name="<?php echo $fhrsam_conf_var["preserve_settings"]; ?>" type="checkbox" id="<?php echo $fhrsam_conf_var["preserve_settings"]; ?>" value="1" <?php checked( '1', get_option( $fhrsam_conf_var["preserve_settings"] ) ); ?> /><?php _e('Check to preserve plugin settings if deactivated. (recommended)') ?></td>
  </tr>
</table>
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
<hr/>
</form>
</div>

<?php

}

function fhrsam_plugin_menu()
{
  add_options_page('FHRSAM Plugin Options', 'FHRSAM Plugin', 'administrator', 'fhrsam_unique_identifier', 'fhrsam_plugin_options');
}

add_action('admin_menu', 'fhrsam_plugin_menu');

?>

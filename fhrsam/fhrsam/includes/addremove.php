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

function fhrsam_plugin_install()
{
  global $fhrsam_conf_var;

  if ( !get_option( $fhrsam_conf_var["preserve_settings"] ) )
  {
    foreach ( $fhrsam_conf_var as $fhrsam_variable )
    {
      add_option( $fhrsam_variable, '' );
    }
  }
}

register_activation_hook(__FILE__, 'fhrsam_plugin_install');

function fhrsam_plugin_uninstall()
{
  global $fhrsam_conf_var;

  if ( get_option( $fhrsam_conf_var["preserve_settings"] ) < 1 )
  {
    foreach ( $fhrsam_conf_var as $fhrsam_variable )
    {
      delete_option( $fhrsam_variable );
    }
  }
}

register_deactivation_hook(__FILE__, 'fhrsam_plugin_uninstall');

?>

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

//Using FesterHead's Song Requester service
function fhrsam_requests( songid, samhost, samport, song_requester_id )
{
  var window_options = "location=no, status=no, menubar=no, scrollbars=no, resizeable=yes, height=500, width=668";
  var path = "http://www.songrequester.com/request.php?songid=" + songid + "&samport=" + samport + "&samhost=" + samhost;
  if ( typeof song_requester_id != 'undefined' )
  {
    path = path + "&user=" + song_requester_id;
  }
  reqwin = window.open( path, "FesterHeadSongRequester", window_options );
}

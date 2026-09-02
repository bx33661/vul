<?php
# Modified for LG NAS
# 2008/11/14 Kown Hyug Jin (sonmapsi@lge.com)

# Mantis - a php based bugtracking system

# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
# Copyright (C) 2002 - 2007  Mantis Team   - mantisbt-dev@lists.sourceforge.net

# Mantis is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# Mantis is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Mantis.  If not, see <http://www.gnu.org/licenses/>.

# --------------------------------------------------------
# $Id: lang_api.php,v 1.40.4.1 2007-10-13 22:35:32 giallu Exp $
# --------------------------------------------------------

### Language (Internationalization) API ##

# Cache of localization strings in the language specified by the last
# lang_load call
$g_lang_strings = array();

# To be used in custom_strings_inc.php :
$g_active_language  = '';

# ------------------
# Loads the specified language and stores it in $g_lang_strings,
# to be used by lang_get
function lang_load( $p_lang ) {
	global $g_lang_strings, $g_active_language;

	$g_active_language  = $p_lang;
	if ( isset( $g_lang_strings[ $p_lang ] ) ) {
		return;
	}

	$t_lang_dir = dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR. "multilang" . DIRECTORY_SEPARATOR . 'multilang_data' . DIRECTORY_SEPARATOR;

	require_once( $t_lang_dir . 'strings_' . $p_lang . '.php' );

	$t_vars = get_defined_vars();

	foreach ( array_keys( $t_vars ) as $t_var ) {
		$t_lang_var = ereg_replace( '^s_', '', $t_var );
		if ( $t_lang_var != $t_var ) {
			$g_lang_strings[ $p_lang ][ $t_lang_var ] = $$t_var;
		}
	}
}

# Ensures that a language file has been loaded
function lang_ensure_loaded( $p_lang ) {
	global $g_lang_strings;

	if ( ! isset( $g_lang_strings[ $p_lang ] ) ) {
		lang_load( $p_lang );
	}
}

function lang_set_active_language( $wanted_lang )
{
	global $g_active_language;

	if ( $wanted_lang != $g_active_language )
	{
		lang_ensure_loaded( $wanted_lang );
	}
}

# ------------------
# Retrieves an internationalized string
#  This function will return one of (in order of preference):
#    1. The string in the current user's preferred language (if defined)
#    2. The string in English
function lang_get( $p_string, $p_lang = null ) {
	global $g_lang_strings;
	global $g_active_language;

	# If no specific language is requested, we'll
	#  try to determine the language from the users
	#  preferences

	if ( $p_lang == null )
	{
		$t_lang = $g_active_language;
	}
	else
	{
		$t_lang = $p_lang;
	}

	if ( null === $t_lang ) {
		$t_lang = 'en';
	}

	# Now we'll make sure that the requested language is loaded

	lang_ensure_loaded( $t_lang );

	# note in the current implementation we always return the same value
	#  because we don't have a concept of falling back on a language.  The
	#  language files actually *contain* English strings if none has been
	#  defined in the correct language
	# @@@ thraxisp - not sure if this is still true. Strings from last language loaded
	#      may still be in memeory if a new language is loaded.

	if ( lang_exists( $p_string, $t_lang ) ) {
		return $g_lang_strings[ $t_lang ][ $p_string];
	} else {
		if ( $t_lang == 'en' ) {
			return $p_string.' : STRING NOT DEFINED';
		} else {
			# if string is not found in a language other than english, then retry using the english language.
			return lang_get( $p_string, 'en' );
		}
	}
}

# ------------------
# Check the language entry, if found return true, otherwise return false.
function lang_exists( $p_string, $p_lang ) {
	global $g_lang_strings;

	return ( isset( $g_lang_strings[ $p_lang ] )
	&& isset( $g_lang_strings[ $p_lang ][ $p_string ] ) );
}
?>

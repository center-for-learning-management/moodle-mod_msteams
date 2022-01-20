<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * msteams module prepare the MoodleSession cookie to use samesite=none
 * In order for the embedded connector to O365, the MoodleSession-Cookie
 * must have SameSite=None.
 *
 * This cookie must be set by a PHP programme, and cannot be set by JavaScript.
 *
 * Each AJAX-request of Moodle will reset the Cookie to SameSite=Lax, so we need
 * to call this script regularly, until we came back from the MS Teams connector.
 *
 * @package    mod_msteams
 * @copyright  2022 Robert Schrenk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

header('Set-Cookie: MoodleSession=' . $_COOKIE['MoodleSession'] . '; path=/; httponly; SameSite=None; Secure', false);

?><script>
setTimeout(function() {location.reload();}, 1000);
</script>

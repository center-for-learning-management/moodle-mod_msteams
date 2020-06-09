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
 * Definition of log events
 *
 * @package    mod_msteams
 * @category   log
 * @copyright  2010 Petr Skoda (http://skodak.org) (mod_url)
 *             2020 Robert Schrenk (mod_msteams)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module'=>'msteams', 'action'=>'view', 'mtable'=>'msteams', 'field'=>'name'),
    array('module'=>'msteams', 'action'=>'view all', 'mtable'=>'msteams', 'field'=>'name'),
    array('module'=>'msteams', 'action'=>'update', 'mtable'=>'msteams', 'field'=>'name'),
    array('module'=>'msteams', 'action'=>'add', 'mtable'=>'msteams', 'field'=>'name'),
);

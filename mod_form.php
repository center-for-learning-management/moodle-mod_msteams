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
 * MS Teams configuration form
 *
 * @package    mod_msteams
 * @copyright  2020 Center for Learning Management (www.lernmanagement.at)
 * @author     Robert Schrenk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_msteams_mod_form extends moodleform_mod {
    function definition() {
        global $CFG, $DB, $PAGE, $USER;

        $mform = $this->_form;

        $config = get_config('msteams');
        $url = $config->meetingapplink . '?url=' . rawurlencode($CFG->wwwroot) . '&local=' . $USER->lang;

        $html = implode("\n", array(
            '<iframe id="meetingapp" class="hidden" src="' . $url . '" width="100%" height="500"></iframe>',
            '<iframe id="meetingappcookiehelper" class="hidden" src="' . $CFG->wwwroot . '/mod/msteams/cookie.php" width="100%" height="500"></iframe>',
            '<p id="meetingsuccess" class="alert alert-success hidden">' . get_string('urlsuccessfullycreated', 'mod_msteams') . '</p>',
        ));
        $mform->addElement('html', $html);
        $PAGE->requires->js_call_amd('mod_msteams/main', 'meetingcheck', array());

        //-------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), array('size'=>'48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $mform->addElement('text', 'externalurl', get_string('url', 'msteams'), array('readonly' => 'readonly', 'size'=>'48'));
        $mform->addHelpButton('externalurl', 'url', 'msteams');
        $mform->setType('externalurl', PARAM_RAW_TRIMMED);
        $mform->addRule('externalurl', null, 'required', null, 'client');
        $this->standard_intro_elements();
        $element = $mform->getElement('introeditor');
        $attributes = $element->getAttributes();
        $attributes['rows'] = 5;
        $element->setAttributes($attributes);

        //-------------------------------------------------------
        $this->standard_coursemodule_elements();

        //-------------------------------------------------------
        $this->add_action_buttons();
    }


    function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }

}

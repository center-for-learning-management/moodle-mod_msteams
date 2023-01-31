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

namespace mod_msteams\output;

defined('MOODLE_INTERNAL') || die();

use context_module;

class mobile {

    public static function mobile_course_view($args) {
        global $DB, $CFG, $OUTPUT, $USER;

        $cmid = $args['cmid'];

        // Verify course context.
        $cm = get_coursemodule_from_id('msteams', $cmid);
        if (!$cm) {
            print_error('invalidcoursemodule');
        }
        $course = $DB->get_record('course', array('id' => $cm->course));
        if (!$course) {
            print_error('coursemisconf');
        }
        require_course_login($course, false, $cm, true, true);
        $context = context_module::instance($cm->id);
        require_capability('mod/msteams:view', $context);
        $msteam = $DB->get_record('msteams', array('id'=>$cm->instance), '*', MUST_EXIST);
        msteams_view($msteam, $course, $cm, $context);
        $exturl = trim($msteam->externalurl);
        $outstring = "Success";
        if (empty($exturl) or $exturl === 'http://') {
            $outstring = get_string('invalidstoredurl', 'msteams');
        }
        else{
            $outstring ='<a href="'.$exturl.'">'.get_string("externalurl", 'msteams').'</a>';
        }

        return array(
            'templates'  => array(
                array(
                    'id'   => 'main',
                    'html' => '<h1 class="text-center">'.$outstring.'</h1>',
                ),
            ),
        );
    }
}
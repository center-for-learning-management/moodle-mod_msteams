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
 * MS Teams external API
 *
 * @package    mod_msteams
 * @category   external
 * @copyright  2015 Juan Leyva <juan@moodle.com> (mod_url)
 *             2020 Robert Schrenk (mod_msteams)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

/**
 * URL external functions
 *
 * @package    mod_url
 * @category   external
 * @copyright  2015 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */
class mod_msteams_external extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function view_msteams_parameters() {
        return new external_function_parameters(
            array(
                'msteamsid' => new external_value(PARAM_INT, 'msteams instance id')
            )
        );
    }

    /**
     * Trigger the course module viewed event and update the module completion status.
     *
     * @param int $msteamsid the msteams  instance id
     * @return array of warnings and status result
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function view_msteams($msteamsid) {
        global $DB, $CFG;
        require_once($CFG->dirroot . "/mod/msteams/lib.php");

        $params = self::validate_parameters(self::view_msteams_parameters(),
                                            array(
                                                'msteamsid' => $msteamsid
                                            ));
        $warnings = array();

        // Request and permission validation.
        $msteams = $DB->get_record('msteams', array('id' => $params['msteamsid']), '*', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($msteams, 'msteams');

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/msteams:view', $context);

        // Call the url/lib API.
        msteams_view($msteams, $course, $cm, $context);

        $result = array();
        $result['status'] = true;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function view_msteams_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_msteams_by_courses.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function get_msteams_by_courses_parameters() {
        return new external_function_parameters (
            array(
                'courseids' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'Course id'), 'Array of course ids', VALUE_DEFAULT, array()
                ),
            )
        );
    }

    /**
     * Returns a list of msteams in a provided list of courses.
     * If no list is provided all msteams that the user can view will be returned.
     *
     * @param array $courseids course ids
     * @return array of warnings and urls
     * @since Moodle 3.3
     */
    public static function get_msteams_by_courses($courseids = array()) {

        $warnings = array();
        $returnedmsteams = array();

        $params = array(
            'courseids' => $courseids,
        );
        $params = self::validate_parameters(self::get_msteams_by_courses_parameters(), $params);

        $mycourses = array();
        if (empty($params['courseids'])) {
            $mycourses = enrol_get_my_courses();
            $params['courseids'] = array_keys($mycourses);
        }

        // Ensure there are courseids to loop through.
        if (!empty($params['courseids'])) {

            list($courses, $warnings) = external_util::validate_courses($params['courseids'], $mycourses);

            // Get the urls in this course, this function checks users visibility permissions.
            // We can avoid then additional validate_context calls.
            $allteams = get_all_instances_in_courses("msteams", $courses);
            foreach ($allteams as $msteams) {
                $context = context_module::instance($msteams->coursemodule);
                // Entry to return.
                $msteams->name = external_format_string($msteams->name, $context->id);

                $options = array('noclean' => true);
                list($msteams->intro, $msteams->introformat) =
                    external_format_text($msteams->intro, $msteams->introformat, $context->id, 'mod_msteams', 'intro', null, $options);
                $msteams->introfiles = external_util::get_area_files($context->id, 'mod_msteams', 'intro', false, false);

                $returnedmsteams[] = $msteams;
            }
        }

        $result = array(
            'urls' => $returnedmsteams,
            'warnings' => $warnings
        );
        return $result;
    }

    /**
     * Describes the get_urls_by_courses return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_msteams_by_courses_returns() {
        return new external_single_structure(
            array(
                'msteams' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Module id'),
                            'coursemodule' => new external_value(PARAM_INT, 'Course module id'),
                            'course' => new external_value(PARAM_INT, 'Course id'),
                            'name' => new external_value(PARAM_RAW, 'URL name'),
                            'intro' => new external_value(PARAM_RAW, 'Summary'),
                            'introformat' => new external_format_value('intro', 'Summary format'),
                            'introfiles' => new external_files('Files in the introduction text'),
                            'externalurl' => new external_value(PARAM_RAW_TRIMMED, 'External URL'),
                            'timemodified' => new external_value(PARAM_INT, 'Last time the url was modified'),
                            'section' => new external_value(PARAM_INT, 'Course section id'),
                            'visible' => new external_value(PARAM_INT, 'Module visibility'),
                            'groupmode' => new external_value(PARAM_INT, 'Group mode'),
                            'groupingid' => new external_value(PARAM_INT, 'Grouping id'),
                        )
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }
}

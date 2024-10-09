<?php
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
 * Webservice to search users
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_formbuilder\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

defined('MOODLE_INTERNAL') || die();

class search_users extends external_api {

    /**
     * Parameters expected for the webservice.
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'search' => new external_value(PARAM_TEXT, 'search term'),
        ]);
    }

    /**
     * Structure expected to be returned by the webservice.
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure(
            array(
                'total' => new external_value(PARAM_INT, 'total user count'),
                'users' => new external_multiple_structure(
                    new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'id of the user'),
                        'fullname' => new external_value(PARAM_TEXT, 'fullname of the user'),
                        'idnumber' => new external_value(PARAM_TEXT, 'idnumber of the user'),
                    ])
                ),
            )
        );
    }

    /**
     * Execute the webservice search
     * @param $search
     * @return array
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     */
    public static function execute($search): array {

        global $DB;

        $params = self::validate_parameters(self::execute_parameters(), ['search' => $search]);

        $sqlparams = [
            'search' => '%' . $params['search'] . '%',
            'search2' => '%' . $params['search'] . '%',
            'search3' => '%' . $params['search'] . '%',
        ];
        $users = $DB->get_records_sql("SELECT *
                                             FROM {user}
                                            WHERE " . $DB->sql_like('firstname', ':search', false) . "
                                               OR " . $DB->sql_like('lastname', ':search2', false) . "
                                               OR " . $DB->sql_like(
                                                   $DB->sql_concat('firstname', "' '", 'lastname'),
                                                   ':search3',
                                                   false
                                                ) . "
                                         ORDER BY lastname, firstname", $sqlparams);

        $return = [];
        foreach ($users as $user) {
            $return[] = [
                'id' => $user->id,
                'fullname' => fullname($user),
                'idnumber' => $user->idnumber,
            ];
        }

        return [
            'total' => count($return),
            'users' => $return
        ];

    }

}
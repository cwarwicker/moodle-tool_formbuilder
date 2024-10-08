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
 * Categorypicker custom form field.
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_formbuilder\forms\fields;

global $CFG;
require_once($CFG->libdir . '/form/autocomplete.php');

class userpicker extends \MoodleQuickForm_autocomplete {

    /**
     * Construct the field
     * @param string $elementName Select name attribute
     * @param mixed $elementLabel Label(s) for the select
     * @param mixed $options Data to be used to populate options
     * @param mixed $attributes Either a typical HTML attribute string or an associative array. Special options
     *                          "tags", "placeholder", "ajax", "multiple", "casesensitive" are supported.
     */
    public function __construct($elementName = null, $elementLabel = null, $options = null, $attributes = null) {

        $attributes['multiple'] = true;
        $attributes['ajax'] = 'tool_formbuilder/form-user-picker';

        parent::__construct($elementName, $elementLabel, [], $attributes);

    }

    /**
     * Set the value into the field so it displays the correct text in the option pills.
     * @param string|array $value The value to set.
     * @return bool
     */
    public function setValue($value) {

        global $DB;

        $values = (array) $value;

        foreach ($values as $userid) {
            if (!$this->optionExists($userid)) {
                $user = $DB->get_record('user', ['id' => $userid]);
                if ($user) {
                    $label = fullname($user) . " ({$user->idnumber})";
                    $this->addOption($label, $user->id);
                }
            }
        }

        return $this->setSelected($values);

    }

}
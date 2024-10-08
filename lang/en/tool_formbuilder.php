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
 * Language strings.
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['context'] = 'Form context';
$string['context:category'] = '🔳 Course Category';
$string['context:course'] = '🎓 Course';
$string['context:global'] = '🌍 Global';
$string['context:user'] = '🙎🏻‍♂️ User';
$string['context_help'] = '<strong>' . $string['context:global'] . '</strong> - Any user with the tool_formbuilder/submitform capability can access the form.<br><strong>' . $string['context:category'] . '</strong> - Only users enrolled in a course within the specified course categor(y|ies) (and with the tool_formbuilder:submitform capability) can access the form.<br><strong>' . $string['context:course'] . '</strong> - Only users enrolled in the specified course(s) (and with the tool_formbuilder:submitform capability) can access the form.<br><strong>' . $string['context:user'] . '</strong> - Only the specified users (with the tool_formbuilder:submitform capability) can access the form.<br>';
$string['createnewform'] = 'Create new form';
$string['err:invalidcontext'] = 'Invalid form context';
$string['err:invalidtype'] = 'Invalid form type';
$string['err:required'] = 'The field is required';
$string['fields'] = 'Fields';
$string['formdetails'] = 'Form details';
$string['formsaved'] = 'Form saved successfully';
$string['manageforms'] = 'Manage forms';
$string['pluginname'] = 'Form Builder';
$string['restrict:category'] = 'Restrict to these categories';
$string['restrict:course'] = 'Restrict to these courses';
$string['restrict:user'] = 'Restrict to these users';
$string['type'] = 'Type';
$string['type:incremental'] = '🧾 Incremental form';
$string['type:incremental_help'] = 'A form which can be filled out multiple times per person, with each record stored and displayed in tabular format. E.g. Database entries';
$string['type:multi'] = '📚 Multi-Instance Form';
$string['type:multi_help'] = 'A form which can be filled out multiple times per person, with each record stored. E.g. Work Experience Logs';
$string['type:single'] = '📋 Single-Instance Form';
$string['type:single_help'] = 'One instance of the form which can be filled out once per person. E.g. Contact Details';
$string['type_help'] = '<strong>' . $string['type:single'] . '</strong> - ' . $string['type:single_help'] . '<br><strong>' . $string['type:multi'] . '</strong> - ' . $string['type:multi_help'] . '<br><strong>' . $string['type:incremental'] . '</strong> - ' . $string['type:incremental_help'] . '<br>';
$string['updateform'] = 'Update form';
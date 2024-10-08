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
 * Main index page
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../../config.php';

$context = \core\context\system::instance();

require_login();
require_capability('tool/formbuilder:manageforms', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/admin/tool/formbuilder/index.php'));
$PAGE->set_heading(get_string('manageforms', 'tool_formbuilder'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('tool_formbuilder/index', []);

echo $OUTPUT->footer();
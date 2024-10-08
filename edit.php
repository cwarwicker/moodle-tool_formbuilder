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
 * Create/edit a form
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../../config.php';

$context = \core\context\system::instance();

require_login();
require_capability('tool/formbuilder:manageforms', $context);

$id = optional_param('id', null, PARAM_INT);
$formobject = false;
$pagetitle = get_string('createnewform', 'tool_formbuilder');

// If we're editing a form, try to load it.
if ($id) {
    $formobject = \tool_formbuilder\form::get($id);
    $pagetitle = get_string('updateform', 'tool_formbuilder');
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/admin/tool/formbuilder/edit.php', ['id' => $id]));
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);

$editform = new \tool_formbuilder\forms\edit_form(null, $formobject);

// If we're editing an existing item, load the data into the edit form.
if ($formobject) {
    $editform->set_data($formobject->to_array());
}
// If we submitted the form, try to save it.
if ($editform->get_data()) {
    $form = \tool_formbuilder\form::load((array)$editform->get_data());
    if ($form->save()) {
        redirect(
            new moodle_url(
                '/admin/tool/formbuilder/index.php'
            ), get_string('formsaved', 'tool_formbuilder')
        );
    }
} else if ($editform->is_cancelled()) {
    redirect(new moodle_url('/admin/tool/formbuilder/'));
}

echo $OUTPUT->header();
echo $editform->display();
echo $OUTPUT->footer();
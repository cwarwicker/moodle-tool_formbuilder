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
namespace tool_formbuilder\forms;

use tool_formbuilder\form;

require_once $CFG->libdir . '/formslib.php';
require_once $CFG->dirroot . '/admin/tool/formbuilder/lib.php';


class edit_form extends \moodleform {

    /**
     * Define the form fields
     * @return void
     */
    protected function definition(): void {

        global $OUTPUT;

        // Register own custom field types.
        \tool_formbuilder_register_elements();

        // Hidden ID field.
        $this->_form->addElement('hidden', 'id');
        $this->_form->setType('id', PARAM_INT);

        // Form details section.
        $this->_form->addElement('header', 'heading', get_string('formdetails', 'tool_formbuilder'));

        // Form type.
        $options = [];
        foreach (form::TYPES as $type) {
            $options[$type] = get_string('type:' . $type, 'tool_formbuilder');
        }
        $this->_form->addElement('select', 'type', get_string('type', 'tool_formbuilder'), $options);
        $this->_form->addHelpButton('type', 'type', 'tool_formbuilder');

        // Form name.
        $this->_form->addElement('text', 'name', get_string('name'));
        $this->_form->setType('name', PARAM_TEXT);

        // Form description.
        $this->_form->addElement('editor', 'description', get_string('description'));

        // Restrict access section.
        $this->_form->addElement('header', 'heading', get_string('restrictaccess', 'availability'));

        // Form context.
        $options = [];
        foreach (form::CONTEXTS as $context) {
            $options[$context] = get_string('context:' . $context, 'tool_formbuilder');
        }
        $this->_form->addElement('select', 'context', get_string('context', 'tool_formbuilder'), $options);
        $this->_form->addHelpButton('context', 'context', 'tool_formbuilder');

        // Course category search.
        $options = [''];
        $options += \core_course_category::make_categories_list('moodle/category:manage');
        $this->_form->addElement('autocomplete', 'restrictbycategory', get_string('restrict:category', 'tool_formbuilder'),
            $options, ['multiple' => true]);

        // Course search.
        $this->_form->addElement('course', 'restrictbycourse', get_string('restrict:course', 'tool_formbuilder'), [
            'multiple' => true,
        ]);

        // User search.
        $this->_form->addElement('userpicker', 'restrictbyuser', get_string('restrict:user', 'tool_formbuilder'));

        // Form field section.
        $this->_form->addElement('header', 'heading', get_string('fields', 'tool_formbuilder'));

        // All form fields will be stored in a hidden input in JSON.
        $this->_form->addElement('hidden', 'fields');

        // Display the template which lets you add/edit the fields.
        // It's too complicated to make that part of the normal Moodle form.
        $fields = $this?->_customdata->fields;
        $this->_form->addElement('html', $OUTPUT->render_from_template(
            'tool_formbuilder/field-selector',
            [
                'fields' => $fields,
            ]
        ));

        // Show the relevant context restriction field based on the chosen context.
        $this->_form->hideIf('restrictbycategory', 'context', 'neq', 'category');
        $this->_form->hideIf('restrictbycourse', 'context', 'neq', 'course');
        $this->_form->hideIf('restrictbyuser', 'context', 'neq', 'user');

        // Validation rules.
        $this->_form->addRule('type', get_string('err:required', 'tool_formbuilder'), 'required', null, 'client');
        $this->_form->addRule('name', get_string('err:required', 'tool_formbuilder'), 'required', null, 'client');
        $this->_form->addRule('description', get_string('err:required', 'tool_formbuilder'), 'required', null, 'client');
        $this->_form->addRule('context', get_string('err:required', 'tool_formbuilder'), 'required', null, 'client');

        // Submit and cancel buttons.
        $this->add_action_buttons();

    }

    /**
     * Validate the submitted data with extra rules
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files): array {

        $errors = parent::validation($data, $files);

        // Make sure the type is valid.
        if (!in_array($data['type'], form::TYPES)) {
            $errors['type'] = get_string('err:invalidtype', 'tool_formbuilder');
        }

        // Make sure the context is valid.
        if (!in_array($data['context'], form::CONTEXTS)) {
            $errors['context'] = get_string('err:invalidcontext', 'tool_formbuilder');
        }

        return $errors;

    }

}

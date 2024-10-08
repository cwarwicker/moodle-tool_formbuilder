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
 * Form class
 *
 * This is the class for the formbuilder forms. Not to be confused with moodle forms.
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_formbuilder;

use core\exception\moodle_exception;

class form
{

    /**
     * Array of valid form types
     * @const array
     */
    const TYPES = ['single', 'multi', 'incremental'];

    /**
     * Array of valid form contexts
     * @const array
     */
    const CONTEXTS = ['global', 'category', 'course', 'user'];

    /**
     * Form ID
     * @var string|mixed|null
     */
    protected ?int $id {
        get {
            return $this->id;
        }
        set(?int $value) {
            $this->id = $value;
        }
    }

    /**
     * Form name
     * @var string|mixed|null
     */
    protected ?string $name {
        get {
            return $this->name;
        }
        set(?string $value) {
            $this->name = $value;
        }
    }

    /**
     * Form description
     * @var string|mixed|null
     */
    protected ?string $description {
        get {
            return $this->description;
        }
        set(?string $value) {
            $this->description = $value;
        }
    }

    /**
     * Form type
     * @var string|mixed|null
     * @throws moodle_exception
     */
    protected ?string $type {
        get {
            return $this->type;
        }
        set(?string $value) {
            if (!is_null($value) && !in_array($value, form::TYPES)) {
                throw new moodle_exception('err:invalidtype', 'tool_formbuilder', null, $value);
            }
            $this->type = $value;
        }
    }

    /**
     * Form context
     * @var string|mixed|null
     */
    protected ?string $context {
        get {
            return $this->context;
        }
        set(?string $value) {
            $this->context = $value;
        }
    }

    /**
     * Form restrictions
     * @var string|mixed|null
     */
    protected ?array $restrictions {
        get {
            return $this->restrictions;
        }
        set(?array $value) {
            $this->restrictions = $value;
        }
    }

    /**
     * Form fields
     * @var string|mixed|null
     */
    public ?array $fields {
        get {
            return $this->fields;
        }
        set(?array $value) {
            $this->fields = $value;
        }
    }

    /**
     * User ID of last modifier
     * @var int|null
     */
    protected ?int $usermodified {
        get {
            return $this->usermodified;
        }
        set(?int $value) {
            $this->usermodified = $value;
        }
    }

    /**
     * Timestamp of creation time
     * @var int|null
     */
    protected ?int $timecreated {
        get {
            return $this->timecreated;
        }
        set(?int $value) {
            $this->timecreated = $value;
        }
    }

    /**
     * Timestamp of modified time
     * @var int|null
     */
    protected ?int $timemodified {
        get {
            return $this->timemodified;
        }
        set(?int $value) {
            $this->timemodified = $value;
        }
    }

    /**
     * Construct the object
     * @param array $data
     */
    public function __construct(array $data) {

        if (is_string($data['restrictions'])) {
            $data['restrictions'] = json_decode($data['restrictions']);
        }

        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->context = $data['context'] ?? null;
        $this->restrictions = $data['restrictions'] ?? [];
        $this->fields = $data['fields'] ?? null;
        $this->usermodified = $data['usermodified'] ?? null;
        $this->timecreated = $data['timecreated'] ?? null;
        $this->timemodified = $data['timemodified'] ?? null;

    }

    /**
     * Load the formbuilder object from the moodleform data
     * Used to load from the create/edit form
     * @param array $data
     * @return form
     */
    public static function load(array $data): form {

        $data['description'] = $data['description']['text'];
        $data['restrictions'] = match($data['context']) {
            'global' => null,
            'category' => $data['restrictbycategory'],
            'course' => $data['restrictbycourse'],
            'user' => $data['restrictbyuser'],
        };
        $data['fields'] = (strlen($data['fields'])) ? json_encode($data['fields']) : null;

        return new form($data);

    }

    /**
     * Get a form by its ID
     * @param int $id
     * @return form|bool Form object or false
     * @throws \dml_exception
     */
    public static function get(int $id): form|bool {

        global $DB;

        $record = $DB->get_record('tool_formbuilder', ['id' => $id]);
        if ($record) {
            return new form((array)$record);
        } else {
            return false;
        }

    }

    /**
     * Save the record in the database
     * @return bool|int
     * @throws \dml_exception
     */
    public function save(): bool|int
    {
        if (!is_null($this->id) && $this->id > 0) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * Update a form in the database
     * @return int
     * @throws \dml_exception
     */
    protected function update(): int {

        global $DB;
        $DB->update_record('tool_formbuilder', [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'context' => $this->context,
            'restrictions' => json_encode($this->restrictions),
            'fields' => $this->fields,
        ]);
        return $this->id;

    }

    /**
     * Insert a new form into the database
     * @return bool|int
     * @throws \dml_exception
     */
    protected function insert(): bool|int {

        global $DB, $USER;
        $this->id = $DB->insert_record('tool_formbuilder', [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'context' => $this->context,
            'restrictions' => json_encode($this->restrictions),
            'fields' => $this->fields,
            'usermodified' => $USER->id,
            'timecreated' => time(),
            'timemodified' => time(),
        ]);
        return $this->id;

    }

    /**
     * Convert the object to an array for use in mform->set_data().
     * @return array
     */
    public function to_array(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => [
                'text' => $this->description,
                'format' => FORMAT_HTML,
            ],
            'type' => $this->type,
            'context' => $this->context,
            'restrictbycategory' => ($this->context === 'category') ? $this->restrictions : [],
            'restrictbycourse' => ($this->context === 'course') ? $this->restrictions : [],
            'restrictbyuser' => ($this->context === 'user') ? $this->restrictions : [],
            'fields' => $this->fields,
            'usermodified' => $this->usermodified,
            'timecreated' => $this->timecreated,
            'timemodified' => $this->timemodified,
        ];
    }

}
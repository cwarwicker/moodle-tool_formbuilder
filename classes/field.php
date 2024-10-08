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
 * Field class
 *
 * This is the class for fields within the formbuilder class.
 * Not to be confused with fields on moodle forms.
 *
 * @package tool_formbuilder
 * @copyright 2024 Conn Warwicker
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_formbuilder;

// Note: Types - info (static text), text, editor, checkbox, radio, select, date/time, file, userpicker, coursepicker, mycourses, rating, matrix, slider

abstract class field {

    /**
     * Random unique ID for the element id.
     * @var string
     */
    protected string $uid {
        get {
            return $this->id;
        }
        set(string $value) {
            $this->id = $value;
        }
    }

    /**
     * The name of the field
     * @var string
     */
    protected string $name {
        get {
            return $this->name;
        }
        set(string $value) {
            $this->name = $value;
        }
    }

    /**
     * The type of the field
     * @var string
     */
    protected string $type {
        get {
            return get_class($this);
        }
    }

    /**
     * Default value for the field if one not specified
     * @var string
     */
    protected string $default {
        get {
            return $this->default;
        }
        set(string $value) {
            $this->default = $value;
        }
    }

    /**
     * Array of options for the field (if applicable)
     * @var string
     */
    protected array $options {
        get {
            return $this->options;
        }
        set(array $value) {
            $this->options = $value;
        }
    }

    /**
     * Array of validation rules for the field (if applicable)
     * @var string
     */
    protected array $validation {
        get {
            return $this->validation;
        }
        set(array $value) {
            $this->validation = $value;
        }
    }

    /**
     * Value loaded into the field object
     * @var mixed
     */
    protected mixed $value {
        get {
            return $this->value ?? $this->default;
        }
        set(mixed $value) {
            $this->value = $value;
        }
    }

    /**
     * Validate the field value based on its validation rules
     * (To be overridden in subclasses)
     * @return array of errors
     */
    public function validate(): array {
        return [];
    }

    /**
     * Render the field as it's HTML element
     * (To be overridden in subclasses)
     * @return string
     */
    public function to_html(): string {
        return '';
    }

}
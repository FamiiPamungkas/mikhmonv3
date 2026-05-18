<?php

namespace classes;

class Html
{

    /**
     *
     * @param $value
     * @param $selected_value
     * @return string 'selected' jika $a dab $b sama.
     */
    public static function option_selected($value, $selected_value): string
    {
        return $value == $selected_value ? "selected" : "";
    }

    /**
     * @param $value
     * @param $label
     * @param string $selected_value
     * @return string tag option
     */
    public static function option($value, $label, $selected_value = ''): string
    {
        error_log("=== RENDER OPTION");
        return '<option value="' . $value . '" ' . self::option_selected($value, $selected_value) . '>' . $label . '</option>';
    }

    /**
     * @param $attributes array attributes
     * @return string attributes for html element
     */
    public static function create_element_attributes(array $attributes = array()): string
    {
        $attr = "";
        if (sizeof($attributes) > 0) {
            foreach ($attributes as $key => $val) {
                $attr .= " $key='$val'";
            }
        }
        return $attr;
    }

    /**
     * @param $value
     * @param $label
     * @param string $selected_value
     * @param array $attributes attributes for option attribute
     * @return string tag option
     */
    public static function option2($value, $label, string $selected_value = '', array $attributes = array()): string
    {
        $attr = self::create_element_attributes($attributes);
        $selected = self::option_selected($value, $selected_value);
        return "<option value='$value' $attr $selected>$label</option>";
    }

}
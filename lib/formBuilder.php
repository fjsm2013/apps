<?php
// Method to create a text input field
function createInput($type, $id, $name, $value = '', $required = false)
{
    $requiredAttribute = $required ? 'required' : '';
    return sprintf(
        "<div class='form-group'>
             <label for='%s'>%s:</label>
             <input type='%s' class='form-control' id='%s' name='%s' value='%s' %s>
         </div>",
        $id,
        ucfirst($name),
        $type,
        $id,
        $name,
        $value,
        $requiredAttribute
    );
}

// Method to create a select dropdown
function createSelect($id, $name, $options, $selectedValue = '', $required = false)
{
    $requiredAttribute = $required ? 'required' : '';
    $selectHtml = "<div class='form-group'>
                         <label for='$id'>" . ucfirst($name) . ":</label>
                         <select class='form-select' id='$id' name='$name' $requiredAttribute>";

    foreach ($options as $value => $label) {
        $selected = ($value == $selectedValue) ? 'selected' : '';
        $selectHtml .= "<option value='$value' $selected>$label</option>";
    }

    $selectHtml .= "</select></div>";
    return $selectHtml;
}

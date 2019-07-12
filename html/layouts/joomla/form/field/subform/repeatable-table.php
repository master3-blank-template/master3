<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
/**
 * Make thing clear
 * @var JForm   $tmpl             The Empty form for template
 * @var array   $forms            Array of JForm instances for render the rows
 * @var bool    $multiple         The multiple state for the form field
 * @var int     $min              Count of minimum repeating in multiple mode
 * @var int     $max              Count of maximum repeating in multiple mode
 * @var string  $fieldname        The field name
 * @var string  $control          The forms control
 * @var string  $label            The field label
 * @var string  $description      The field description
 * @var array   $buttons          Array of the buttons that will be rendered
 * @var bool    $groupByFieldset  Whether group the subform fields by it`s fieldset
 */
extract($displayData);

if (!$buttons['add'] && !$buttons['remove'] && !$buttons['move']) {
    $buttons = [];
}

// Add script
if ($multiple) {
    HTMLHelper::_('jquery.ui', array('core', 'sortable'));
    HTMLHelper::_('script', 'system/subform-repeatable.js', array('version' => 'auto', 'relative' => true));
}

// Build heading
$table_head = '';

if (!empty($groupByFieldset)) {
    foreach ($tmpl->getFieldsets() as $fieldset) {
        $table_head .= '<th>' . Text::_($fieldset->label);

        if (!empty($fieldset->description)) {
            $table_head .= '<br><small style="font-weight:normal">' . Text::_($fieldset->description) . '</small>';
        }

        $table_head .= '</th>';
    }

    $sublayout = 'section-byfieldsets';
} else {
    foreach ($tmpl->getGroup('') as $field) {
        $table_head .= '<th>' . strip_tags($field->label);
        $table_head .= '<br><small style="font-weight:normal">' . Text::_($field->description) . '</small>';
        $table_head .= '</th>';
    }

    $sublayout = 'section';

    // Label will not be shown for sections layout, so reset the margin left
    Factory::getDocument()->addStyleDeclaration(
        '.subform-table-sublayout-section .controls { margin-left: 0px }'
    );
}
?>

    <div class="subform-repeatable-wrapper subform-table-layout subform-table-sublayout-<?php echo $sublayout; ?>">
        <div class="subform-repeatable"
            data-bt-add="a.group-add" data-bt-remove="a.group-remove" data-bt-move="a.group-move"
            data-repeatable-element="tr.subform-repeatable-group"
            data-rows-container="tbody.subform-repeatable-container" data-minimum="<?php echo $min; ?>" data-maximum="<?php echo $max; ?>">

        <table class="adminlist table uk-table uk-table-striped uk-table-bordered">
            <thead>
                <tr>
                    <?php echo $table_head; ?>
                    <?php if (!empty($buttons)) { ?>
                    <th style="width:8%;">
                        <?php if (!empty($buttons['add'])) { ?>
                        <div class="btn-group">
                            <a class="uk-button uk-button-small uk-button-primary group-add btn btn-mini button btn-success" aria-label="<?php echo Text::_('JGLOBAL_FIELD_ADD'); ?>"><span class="icon-plus" aria-hidden="true"></span> </a>
                        </div>
                        <?php } ?>
                    </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody class="subform-repeatable-container">
            <?php
            foreach ($forms as $k => $form) {
                echo $this->sublayout($sublayout, array('form' => $form, 'basegroup' => $fieldname, 'group' => $fieldname . $k, 'buttons' => $buttons));
            }
            ?>
            </tbody>
        </table>
        <?php if ($multiple) { ?>
        <script type="text/subform-repeatable-template-section" class="subform-repeatable-template-section">
        <?php echo $this->sublayout($sublayout, array('form' => $tmpl, 'basegroup' => $fieldname, 'group' => $fieldname . 'X', 'buttons' => $buttons)); ?>
        </script>
        <?php } ?>
        </div>
    </div>

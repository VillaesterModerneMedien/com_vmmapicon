<?php
namespace Villaester\Component\Vmmapicon\Administrator\Field\ApiMapping;

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;

class ApiMappingField extends FormField
{
    protected $type = 'ApiMapping';

    protected function getInput()
    {
        // Mapping entfernt – keine Eingabe
        return '';
    }
}

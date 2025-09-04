<?php

	namespace Joomla\Plugin\System\Ytdataset\Type;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;

use Joomla\CMS\Router\Route;
use Joomla\Component\Categories\Administrator\Model\CategoryModel;
use Joomla\Component\Fields\Administrator\Model\FieldModel;
use Joomla\String\StringHelper;
use VmmdatabaseNamespace\Component\Vmmdatabase\Site\Model\DatasetModel;


class DatasetType
{
	
	public static function setFields($fieldname, $fieldtype, $label, $tab)
	{
		$array = [
			$fieldname => [
				'type' => $fieldtype,
				'metadata' => [
					'label' => $label,
					'group' => $tab
				],
				'extensions' => [
					'call' => [
						'func' => __CLASS__ . '::resolve',
						'args' => [
							'fieldname' => $fieldname,
						]
					]
				
				]

			],
		];
		
		return $array;
	}
	
	
    public static function config()
    {
	
	    $db    = Factory::getContainer()->get('DatabaseDriver');
	
	    $app       = Factory::getApplication();
	
	    $session = $app->getSession();
	    $datasetId = $session->get('com_vmmdatabase.edit.dataset.data.id');
	
	    // Get the FieldsModelField, we need it in a sec
	    $mvcFactory = $app->bootComponent('com_vmmdatabase')->getMVCFactory();
	
	    /** @var DatasetModel $datasetModel */
	    $datasetModel = $mvcFactory->createModel('Dataset', 'Site', ['ignore_request' => true]);
		
		$currentCategory = 0;
		
		if(is_object($datasetModel->getItem($datasetId)))
		{
			$currentCategory = $datasetModel->getItem($datasetId)->catid;
		}
		
		
	    $query = $db->getQuery(true);
		
	    $query
		    ->select(array('a.id', 'b.value, b.item_id'))
		    ->from($db->quoteName('#__fields', 'a'))
		    ->join('INNER', $db->quoteName('#__fields_values', 'b') . ' ON ' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.field_id'))
		    ->where($db->quoteName('a.name') . ' = ' . $db->quote('tab'));
		
	    $db->setQuery($query);

	    $fields = $db->loadObjectList();
		
		$query->clear();
	
	
	    // Get the FieldsModelField, we need it in a sec
	    $mvcFactory = $app->bootComponent('com_categories')->getMVCFactory();
	
	    /** @var CategoryModel $categoryModel */
	    $categoryModel = $mvcFactory->createModel('Category', 'Administrator', ['ignore_request' => true]);
	
	    $query
		    ->select(array('a.*', 'b.*'))
		    ->from($db->quoteName('#__fields_values', 'a'))
		    ->join('INNER', $db->quoteName('#__fields', 'b') . ' ON ' . $db->quoteName('a.field_id') . ' = ' . $db->quoteName('b.id'))
		    ->where($db->quoteName('b.name') . ' = ' . $db->quote('tab'));

	    $db->setQuery($query);

	    $fields = $db->loadObjectList();
		
		$fieldsData = [];
		
		foreach($fields as $field)
		{
			$catId = (int) $field->item_id;
			
			if($catId === $currentCategory)
			{
				$groupTitle = $categoryModel->getItem($catId)->title;
				
				$fieldsData[$field->item_id] = [
					'groupTitle' => $groupTitle,
					'fields'     => json_decode($field->value, true),
				];
			}
		}
		
		$return = [
			'fields' => [
				
				'dataset' => [
					'type' => 'String',
					'metadata' => [
						'label' => 'Dataset',
						'filters' => ['limit'],
					],
				],
				'title' => [
					'type' => 'String',
					'metadata' => [
						'label' => 'Title',
						'filters' => ['limit'],
					],
				],
			],
			
			'metadata' => [
				'type' => true,
				'label' => 'Dataset'
			],
		];
		
		$app = Factory::getApplication();
		
	    foreach ($fieldsData as $dataset)
	    {
		    $datasetTitle = $dataset['groupTitle'];
			foreach ($dataset['fields'] as $key => $tabsets)
			{
				$tabKeys = array_keys($tabsets);
				$tabTitle = $tabsets[$tabKeys[0]];
				$tabContents = $tabsets[$tabKeys[1]];
				
				foreach ($tabContents as $fieldsets)
				{
					
					$fieldsetKeys = array_keys($fieldsets);
					$fieldsetTitle = $fieldsets[$fieldsetKeys[0]];
					$fieldsetContents = $fieldsets[$fieldsetKeys[1]];
					
					foreach ($fieldsetContents as $fields)
					{
						$fieldsKeys = array_keys($fields);
						$fieldTitle = $fields[$fieldsKeys[0]];
						$fieldType = StringHelper::str_ireplace(' ', '-', StringHelper::strtolower($fields[$fieldsKeys[0]]));
						
						
						$return['fields'] = array_merge($return['fields'], self::setFields($fieldType, 'String', $fieldTitle, $datasetTitle . ': ' . $tabTitle . ' - ' . $fieldsetTitle));
						
					}
		
					
				}
			}
	    }
		
        return $return;
    }
	
	public static function resolve($root, array $args)
	{
		
		if(!empty($args))
		{
			$name = $args['fieldname'];
		}
		
		$dataset = json_decode($root->dataset, true);
		
		$value = array_column($dataset, $name);
		
		/*
		if (!empty($args['id'])) {
			$datasets = DatasetTypeProvider::get($args['id']);
		} else {
			$datasets = DatasetTypeProvider::query($args);
		}
		*/
		
		
		return $value[0];
		
	}

 
}

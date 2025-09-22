<?php

namespace Joomla\Plugin\System\Ytdataset\Type;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Router\Route;


class DatasetSafeType
{
	
	public static function setFields($fieldname, $fieldtype, $label)
	{
		$array = [
			$fieldname => [
				'type' => $fieldtype,
				'metadata' => [
					'label' => $label,
				],
				'extensions' => [
					'call' => [
						'func' =>   __CLASS__ . '::resolve',

					]
				],
			],
		];
		
		return $array;
	}
	
	
    public static function config()
    {
	    return [
		
		    'fields' => [
			
			    'custom_my_type' => [
				
				    'type' => 'MyType',
				
				    // Arguments passed to the resolver function
				    'args' => [
					
					    'id' => [
						    'type' => 'String'
					    ],
				
				    ],
				
				    'metadata' => [
					
					    // Label in the dynamic content select box
					    'label' => 'Custom MyType',
					
					    // Option group in the dynamic content select box
					    'group' => 'Custom',
					
					    // Fields to input arguments in the customizer
					    'fields' => [
						
						    // The array key corresponds to a key in the `args` array above
						    'id' => [
							
							    // Field label
							    'label' => 'Type ID',
							
							    // Field description
							    'description' => 'Input a type ID.',
							
							    // Default or custom field types can be used
							    'type' => 'text'
						
						    ],
					
					    ]
				
				    ],
				
				    'extensions' => [
					    'call' => __CLASS__ . '::resolve',
				    ],
			
			    ],
		
		    ]
	
	    ];
		
		
    }
	
	public static function resolve($root, array $args)
	{
		$fieldname = $args['fieldname'];
		
		$dataset = json_decode($root->dataset);
		
		$value = $dataset->{$fieldname};
		
		$tester = MyType::resolve($root, $args);
		
		/*
		if (!empty($args['id'])) {
			$datasets = DatasetTypeProvider::get($args['id']);
		} else {
			$datasets = DatasetTypeProvider::query($args);
		}
		*/
		
		//return $object . ' - ' . $title;
		
	}

 
}

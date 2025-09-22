<?php
	
	use Joomla\CMS\Factory;
	use Joomla\CMS\Object\CMSObject;
	use Joomla\Component\Fields\Administrator\Model\FieldModel;
	use VmmdatabaseNamespace\Component\Vmmdatabase\Administrator\Model\DatasetsModel;
	

	
	class DatasetTypeProvider
{
    /**
     * Gets the articles.
     *
     * @param int[] $ids
     * @param array $args
     *
     * @return CMSObject[]
     */
    public static function get($ids, array $args = [])
    {
		$app = Factory::getApplication();
	    // Get the FieldsModelField, we need it in a sec
	    $mvcFactory = $app->bootComponent('com_vmmdatabase')->getMVCFactory();
	
	    /** @var DatasetModel $model */
	    $model = $mvcFactory->createModel('Dataset', 'Site', ['ignore_request' => true]);
	    
        //$model = DatasetModel(['ignore_request' => true]);
        $model->setState('dataset.id', (int) $ids);
        $model->setState('filter.state', 1);
        $model->setState('params', new JRegistry());
	
	    $session = $app->getSession();
	    $session->set('com_vmmdatabase.edit.dataset.data.id', $ids);

        foreach ($args as $name => $value) {
            $model->setState($name, $value);
        }
		$item = $model->getItem();
        return $item;
    }
		
		/**
		 * Query articles.
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		public static function query(array $args = [])
		{
			
			$app       = Factory::getApplication();
			
			// Get the FieldsModelField, we need it in a sec
			$mvcFactory = $app->bootComponent('com_vmmdatabase')->getMVCFactory();
			/** @var \VmmdatabaseNamespace\Component\Vmmdatabase\Site\Model\CategoryModel $categoryModel */
			$categoryModel = $mvcFactory->createModel('Category', 'Site', ['ignore_request' => true]);
			
			if (!empty($args['order'])) {
				
				if ($args['order'] === 'rand') {
					$args['order'] = Factory::getDbo()->getQuery(true)->Rand();
				} elseif ($args['order'] === 'front') {
					$args['order'] = 'fp.ordering';
				} else {
					$args['order'] = "a.{$args['order']}";
				}
			}
			
			$props = [
				'offset' => 'list.start',
				'limit' => 'list.limit',
				'order' => 'list.ordering',
				'order_direction' => 'list.direction',
				'order_alphanum' => 'list.alphanum'
			];
			
			foreach (array_intersect_key($props, $args) as $key => $prop) {
				$categoryModel->setState($prop, $args[$key]);
			}
			return $categoryModel->getItems();
		}
}

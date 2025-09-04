<?php
	
	use Joomla\CMS\Factory;
	use Joomla\CMS\Object\CMSObject;
	use Joomla\Component\Fields\Administrator\Model\FieldModel;
	use VmmdatabaseNamespace\Component\Vmmdatabase\Site\Model\DatasetModel;

//use Joomla\Component\Dataset\Site\Model\DatasetModel;

	
	class MyTypeProvider
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

        foreach ($args as $name => $value) {
            $model->setState($name, $value);
        }
		$item = $model->getItem();
        return $item;
    }
}

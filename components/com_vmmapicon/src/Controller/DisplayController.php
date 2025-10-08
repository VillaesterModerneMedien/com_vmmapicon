<?php
namespace Villaester\Component\Vmmapicon\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Factory;

/**
 * Vmmapicon Component Controller.
 *
 * @since  1.0.0
 */
class DisplayController extends BaseController
{
    /**
     * The default view.
     *
     * @var    string
     * @since  1.6
     */
    protected $default_view = 'apis';

    /**
     * Constructor.
     *
     * @param   array                 $config   Optional config.
     * @param   MVCFactoryInterface   $factory  Factory.
     * @param   mixed                 $app      App.
     * @param   mixed                 $input    Input.
     */
    public function __construct($config = [], ?MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   Cacheable
     * @param   array    $urlparams  Safe URL params
     *
     * @return  static
     */
    public function display($cachable = false, $urlparams = [])
    {
        $safeurlparams = [
            'catid' => 'INT',
            'id' => 'INT',
            'cid' => 'ARRAY',
            'limit' => 'UINT',
            'limitstart' => 'UINT',
            'return' => 'BASE64',
            'filter' => 'STRING',
            'filter_order' => 'CMD',
            'filter_order_Dir' => 'CMD',
            'filter-search' => 'STRING',
            'index' => 'INT',
            'itemId' => 'STRING',
        ];

        parent::display($cachable, $safeurlparams);

        if (Factory::getApplication()->getIdentity()->get('id')) {
            $cachable = false;
        }

        return $this;
    }
}

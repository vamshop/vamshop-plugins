<?php

namespace Ckeditor\View\Helper;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\View\Helper;
use Croogo\Core\Router;

/**
 * Ckeditor Helper
 *
 * PHP version 5
 *
 * @category Ckeditor.Helper
 * @package  Ckeditor.View.Helper
 * @version  1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class CkeditorHelper extends Helper
{

    /**
     * Other helpers used by this helper
     *
     * @var array
     * @access public
     */
    public $helpers = [
        'Html',
        'Js',
    ];

    /**
     * Actions
     *
     * Format: ControllerName/action_name => settings
     *
     * @var array
     */
    public $actions = [];

    /**
     * beforeRender
     *
     * @param string $viewFile
     * @return void
     */
    public function beforeRender($viewFile)
    {
        $this->actions = array_keys(Configure::read('Wysiwyg.actions'));
        $action = Router::getActionPath($this->request, true);
        if (!empty($this->actions) && in_array($action, $this->actions)) {
            $this->Html->script('Ckeditor.wysiwyg', ['block' => true]);
            $this->Html->script('Ckeditor.ckeditor', ['block' => true]);

            $ckeditorActions = Configure::read('Wysiwyg.actions');
            if (!isset($ckeditorActions[$action])) {
                return;
            }
            $actionItems = $ckeditorActions[$action];
            $out = null;
            foreach ($actionItems as $actionItem) {
                $element = $actionItem['elements'];
                unset($actionItem['elements']);
                $config = empty($actionItem) ? '{}' : $this->Js->object($actionItem);
                $out .= sprintf('Croogo.Wysiwyg.Ckeditor.setup("%s", %s);', $element, $config);
            }
            $this->Html->scriptBlock($out, ['block' => 'scriptBottom']);
        }
    }
}

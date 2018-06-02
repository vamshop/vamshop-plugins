<?php

namespace Tinymce\View\Helper;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\View\Helper;
use Croogo\Core\Router;

/**
 * Tinymce Helper
 *
 * PHP version 5
 *
 * @category Tinymce.Helper
 * @package  Tinymce.View.Helper
 * @version  1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class TinymceHelper extends Helper
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
            $this->Html->script('Tinymce.wysiwyg', ['block' => true]);
            $this->Html->script('Tinymce.ckeditor', ['block' => true]);

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

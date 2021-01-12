<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $header = '操作';
    public $contentOptions = ['style' => 'padding: 7px;'];
    public $template = '{delete} {edit}';
    public $item;
    public $deletePjax = 'pj-list';
    public $updatePjax = 'pj-form';
    public $containerOptions = ['class' => 'btn-group btn-group-xs'];
    public $headerOptions = ['width' => '200'];

    public function init()
    {
        parent::init();
    }

    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('edit', 'pencil', [
            'class' => 'btn_mini btn_edit'
        ]);
        $this->initDefaultButton('delete', 'trash', [
            'class' => ' btn_mini btn_del item-delete',
        ]);
    }

    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = '查看';
                        break;
                    case 'update':
                        $title = '编辑';
                        break;
                    case 'edit':
                        $title = '编辑';
                        break;
                    case 'delete':
                        $title = '删除';
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                return Html::a($title, $url, $options);
            };
        }
    }

    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string) $key];

        // 分类
        if($this->item == 'type') $params['name'] = $model->name;
        
        $action = $this->item ? $this->item . '-' . $action : $action;
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;
        return Url::toRoute($params);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return Html::tag('div', parent::renderDataCellContent($model, $key, $index), $this->containerOptions);
    }
}
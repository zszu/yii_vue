<?php

namespace app\extensions\ZDateTimePicker;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;

class ZDateTimePicker extends Widget
{
	public $options;
    public $inputOptions = ['class' => 'form-control'];

	public $model;
	public $attribute;
	public $name;
	
	public $language = 'zh';
	
	public function init() 
	{
		if($this->name===null && !$this->hasModel()) {
			throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
		}
		
		if(!isset($this->options['id'])) {
			$this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
		}
		
		parent::init();
	}
	
	public function run()
	{
		$view = $this->view;
		
		ZDateTimePickerAsset::register($view);
		$js=<<<EOF
jQuery.datetimepicker.setLocale('{$this->language}');
jQuery('#{$this->options['id']}').datetimepicker({$this->getOptions()});
EOF;
		$view->registerJs($js);

		return $this->renderTextInput();
	}
	
	protected function getOptions()
	{
		return $this->options ? Json::encode($this->options) : '';
	}
	
	protected function renderTextInput()
	{
		if($this->name === null) {
			return Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
		} else {
			return Html::textInput($this->name, '', $this->inputOptions);
		}
	}

	protected function hasModel()
	{
		return $this->model instanceof \yii\base\Model && $this->attribute !== null;
	}
}
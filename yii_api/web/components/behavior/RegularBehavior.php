<?php
namespace app\components\behavior;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;


/*
* 去除文本中的script标签
*
*/
class RegularBehavior extends Behavior
{
	public $ContentAttribute = 'content';
	public $SummaryAttribute = 'summary';
	// public $length = 150;
	
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'update',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'update',
		];
	}
	
	public function update($event)
	{
		$this->owner->{$this->ContentAttribute} =  preg_replace('/<script[\s\S]*?<\/script>/i','',$this->owner->{$this->ContentAttribute}) ;
		$this->owner->{$this->SummaryAttribute} =  preg_replace('/<script[\s\S]*?<\/script>/i','',$this->owner->{$this->SummaryAttribute}) ;
	}	
	
}
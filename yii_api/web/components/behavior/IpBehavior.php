<?php
namespace app\components\behavior;

use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

class IpBehavior extends Behavior
{
	public $ipAttribute = 'created_ip';
	public $locationAttribute = 'ip_location';
	
	public function events()
	{
		return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'insertAttach',
		];
	}
	
	public function insertAttach($event)
	{
        if ($this->ipAttribute) {
            try {
                $this->owner->{$this->ipAttribute} = Yii::$app->request->userIP;
            } catch (\Exception $e) {

            }
        }
        if ($this->locationAttribute) {
            try {
                $this->owner->{$this->locationAttribute} = ZHelper::ip2City(Yii::$app->request->userIP);
            } catch (\Exception $e) {

            }
        }
	}

	
}


?>
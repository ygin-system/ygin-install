<?php echo "<?php\n"; ?>

class <?php echo $this->moduleClass; ?> extends DaWebModuleAbstract {
	protected $_urlRules = array(
	  '<?php echo $this->moduleID; ?>/<id:\d+>' => '<?php echo $this->moduleID; ?>/default/view',
	  '<?php echo $this->moduleID; ?>' => '<?php echo $this->moduleID; ?>/default/index',
	);
	
	public function init() {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			$this->id.'.models.*',
			$this->id.'.components.*',
		));
	}

	public function beforeControllerAction($controller, $action) {
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}

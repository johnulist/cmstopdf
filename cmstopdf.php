<?php

if (!defined('_PS_VERSION_'))
  exit;

include_once((defined('__DIR__')?__DIR__:dirname(__FILE__)).'/HTMLTemplateCMS.php');

class CMSToPDF extends Module{

	public function __construct()
	{
		$this->name = 'cmstopdf';
		$this->tab = 'administration';
		$this->version = '1.0';
		$this->author = 'Ethicweb';
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('CMS To PDF');
		$this->description = $this->l('Generate pdf from cms pages.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	public function install(){
		return parent::install() && $this->registerHook('actionObjectCmsAddAfter') && $this->registerHook('actionObjectCmsUpdateAfter') && $this->registerHook('actionObjectCmsDeleteAfter');
	}

	public function uninstall(){
		return parent::uninstall();
	}

	public function hookActionObjectCmsAddAfter($params){
		$this->hookActionObjectCmsUpdateAfter($params);
	}

	public function hookActionObjectCmsUpdateAfter($params){
		if(!isset($params['object']))
			return;

		$cms 		= $params['object'];	
		$keywords 	= explode(',',strtolower($cms->meta_keywords[1]));

		if(!in_array('pdf',$keywords))
			return;

		/*if (!$this->fileExists($cms->link_rewrite) && !$this->fileExists('cms'))
			return;*/

		$cms->saveAsFile = true;
		
		$pdf = new PDF($cms,'CMS', $this->context->smarty);							
		$pdf->render('F');
	}

	public function hookActionObjectCmsDeleteAfter($params){
		if(!isset($params['object']))
			return;

		$cms 				= $params['object'];
		$cms->saveAsFile	= true;
		$pdfTemplate 		= new HTMLTemplateCMS($cms,null);

		if(file_exists($pdfTemplate->getFilename())){
			unlink($pdfTemplate->getFilename());
		}

	}

	protected function fileExists($filename){
		$default_template = _PS_PDF_DIR_.'/'.$filename.'.tpl';
		$overriden_template = _PS_THEME_DIR_.'pdf/'.$filename.'.tpl';

		return file_exists($overriden_template) || file_exists($default_template);
	}

}
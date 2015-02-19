<?php 
class HTMLTemplateCMS extends HTMLTemplate
{

	public $cms;
	public $smarty;
	public $context;

	public function __construct(CMS $cms, $smarty){
		$this->cms = $cms;
		$this->smarty = $smarty;
		$this->context = Context::getContext();

		// header informations
		$this->date = Tools::displayDate($this->cms->date_add);
		$this->title = $this->cms->meta_title[1];
	}

	public function getContent()
	{
		return $this->cms->content[1];
	}

	/**
	 * @see HTMLTemplate::getBulkFilename()
	 */
	public function getBulkFilename()
	{
		return $this->cms->link_rewrite[1].'.pdf';
	}

	/**
	 * @see HTMLTemplate::getFileName()
	 */
	public function getFilename()
	{
		return ((isset($this->cms->saveAsFile) && $this->cms->saveAsFile === true) ? _PS_UPLOAD_DIR_ : '' ).'CMS'.sprintf('_%s_%s', $this->cms->link_rewrite[1], $this->cms->id).'.pdf';
	}
}
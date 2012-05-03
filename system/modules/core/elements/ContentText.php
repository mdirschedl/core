<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \ContentElement, \FilesModel, \String;


/**
 * Class ContentText
 *
 * Front end content element "text".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class ContentText extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_text';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->text = String::toXhtml($this->text);
		}
		else
		{
			$this->text = String::toHtml5($this->text);
		}

		$this->Template->text = String::encodeEmail($this->text);
		$this->Template->addImage = false;

		// Add an image
		if ($this->addImage && $this->singleSRC != '')
		{
			if (!is_numeric($this->singleSRC))
			{
				$this->Template->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
			else
			{
				$objModel = FilesModel::findByPk($this->singleSRC);

				if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
				{
					$this->singleSRC = $objModel->path;
					$this->addImageToTemplate($this->Template, $this->arrData);
				}
			}
		}
	}
}

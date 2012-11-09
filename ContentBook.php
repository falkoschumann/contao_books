<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Books Extension for Contao
 * Copyright (c) 2012, Falko Schumann <http://www.muspellheim.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials  provided with the distribution.
 *  - Neither the name of the Muspellheim.de nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written  permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP version 5
 * @copyright  Falko Schumann 2012
 * @author     Falko Schumann <http://www.muspellheim.de>
 * @package    Books
 * @license    BSD-3-clause
 * @filesource
 */


/**
 * Class ContentBook
 *
 * @copyright  Falko Schumann 2012
 * @author     Falko Schumann <http://www.muspellheim.de>
 * @package    Controller
 */
class ContentBook extends ContentElement
{


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$this->strTemplate = 'ce_book';
		return parent::generate();
	}
	
	/**
	 * Compile the current element
	 */
	protected function compile()
	{
		$bookId = $this->book;
		$objBooks = $this->Database->prepare('SELECT * FROM tl_book WHERE (id=?) AND published=1')->execute($bookId);
		$objBooks->next();
		$objBook = (object) $objBooks->row();
		$this->Template->title = $objBook->title;
		$this->Template->subtitle = $objBook->subtitle;
		$this->Template->author = $objBook->author;
		$this->Template->text = $objBook->text;
		
		$objChapters = $this->Database->prepare('SELECT title, alias FROM tl_book_chapter WHERE (pid=?) AND published=1')->execute($bookId);
		$chapters = array();
		if (TL_MODE == 'FE')
		{
			while ($objChapters->next())
			{
				$objChapter = (object) $objChapters->row();
				$arrHeadline = deserialize($objChapter->title);
				$headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
				$hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
				$url = $objChapter->alias;
				$chapters[] = array( 'title' => $headline, 'url' => $url );
			}
		}
		$this->Template->chapters = $chapters;
	}
	
};

?>
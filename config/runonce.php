<?php

/**
 * Books Extension for Contao
 * Copyright (c) 2015 Falko Schumann
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Books
 * @license MIT
 */


namespace Muspellheim\Books;


/**
 * Convert books from v1.x to v2.x table format.
 *
 * @copyright  Falko Schumann 2015
 * @author     Falko Schumann <http://www.muspellheim.de>
 * @package    Models
 * @license    BSD-2-Clause http://opensource.org/licenses/BSD-2-Clause
 */
class BooksRunonceJob extends \Controller
{

	public function __construct()
	{
		parent::__construct();
	}


	public function run()
	{
		$bookRunonce = new BookRunonce();
		$bookRunonce->run();

		$chapterRunonce = new ChapterRunonce();
		$chapterRunonce->run();

	}
}


/**
 * Convert table tl_book from v1.x to v2.x table format.
 *
 * @copyright  Falko Schumann 2015
 * @author     Falko Schumann <http://www.muspellheim.de>
 * @package    Models
 * @license    BSD-2-Clause http://opensource.org/licenses/BSD-2-Clause
 */
class BookRunonce extends \Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	public function run()
	{
		if ($this->Database->tableExists('tl_book'))
		{
			$this->renameFieldCategoryToTags();
			$this->renameFieldTextToAbstract();
		}
	}


	private function renameFieldCategoryToTags()
	{
		if ($this->Database->fieldExists('category', 'tl_book') && !$this->Database->fieldExists('tags', 'tl_book'))
		{
			$this->Database->execute("ALTER TABLE tl_book CHANGE category tags varchar(255) NOT NULL default ''");
		}
	}


	private function renameFieldTextToAbstract()
	{
		if ($this->Database->fieldExists('text', 'tl_book') && !$this->Database->fieldExists('abstract', 'tl_book'))
		{
			$this->Database->execute("ALTER TABLE tl_book CHANGE text abstract mediumtext NOT NULL");
		}
	}

}


/**
 * Convert table tl_book_chapter from v1.x to v2.x table format.
 *
 * @copyright  Falko Schumann 2015
 * @author     Falko Schumann <http://www.muspellheim.de>
 * @package    Models
 * @license    BSD-2-Clause http://opensource.org/licenses/BSD-2-Clause
 */
class ChapterRunonce extends \Controller
{

	private $chapterTreePath = array();

	private $currentChapter;


	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	public function run()
	{
		if ($this->Database->tableExists('tl_book_chapter'))
		{
			if ($this->addFieldBookId())
			{
				$this->currentChapter = $this->Database->execute("SELECT * FROM tl_book_chapter ORDER BY pid, sorting");
				while ($this->currentChapter->next())
				{
					$this->updateChapterTreePath();
					$this->updateChapter();
					$this->createContentElement();
				}
			}
		}
	}


	/**
	 * @return bool
	 */
	private function addFieldBookId()
	{
		if (!$this->Database->fieldExists('book_id', 'tl_book_chapter'))
		{
			$this->Database->execute("ALTER TABLE tl_book_chapter ADD book_id int(10) unsigned NOT NULL default '0'");
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Update the path of the current chapter in the table of content tree. Each path element is a chapter id. The first
	 * path element is a top level chapter. The last path element is the current chapter.
	 */
	private function updateChapterTreePath()
	{
		$level = static::getChapterTreeLevel($this->currentChapter);
		$pathLength = count($this->chapterTreePath);
		if ($level > $pathLength)
		{
			$this->chapterTreePath[] = $this->currentChapter->id;
		}
		else if ($level < $pathLength)
		{
			while ($level < $pathLength)
			{
				array_pop($this->chapterTreePath);
				$pathLength--;
				$this->chapterTreePath[$pathLength - 1] = $this->currentChapter->id;
			}
		}
		else if ($level == $pathLength)
		{
			$this->chapterTreePath[$pathLength - 1] = $this->currentChapter->id;
		}
		else
		{
			throw new \LogicException("unreachable code");
		}
	}


	private function updateChapter()
	{
		$this->Database->prepare("UPDATE tl_book_chapter SET title=?, book_id=?, pid=? WHERE id=?")
			->execute($this->getChapterTitle(), $this->currentChapter->pid, $this->getPid(), $this->currentChapter->id);
	}


	private function createContentElement()
	{
		$this->Database->prepare("INSERT INTO tl_content (pid, ptable, tstamp, type, headline, text) VALUES (?, ?, ?, ?, ?, ?)")
			->execute($this->currentChapter->id, 'tl_book_chapter', $this->currentChapter->tstamp, 'text', $this->currentChapter->title, $this->currentChapter->text);
	}


	/**
	 * @return string
	 */
	private function getChapterTitle()
	{
		$arrHeadline = deserialize($this->currentChapter->title);
		$headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		return $headline;
	}


	/**
	 * @return int
	 */
	private function getPid()
	{
		$pathLength = count($this->chapterTreePath);
		if ($pathLength > 1)
		{
			return $this->chapterTreePath[$pathLength - 2];
		}
		else
		{
			return 0;
		}
	}


	/**
	 * @return int
	 */
	private function getChapterTreeLevel()
	{
		$arrHeadline = deserialize($this->currentChapter->title);
		$hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
		switch ($hl)
		{
			case 'h1':
				return 1;
			case 'h2':
				return 2;
			case 'h3':
				return 3;
			case 'h4':
				return 4;
			case 'h5':
				return 5;
			case 'h6':
				return 6;
			default:
				throw new \LogicException("unreachable code");
		}
	}

}


$objBooksRunonceJob = new BooksRunonceJob();
$objBooksRunonceJob->run();
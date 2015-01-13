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


/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'books' => array
	(
		'tables' => array('tl_book', 'tl_book_chapter', 'tl_content'),
		'icon'   => 'system/modules/books/assets/book.png'
	)
));


/**
 * Content elements
 */
array_insert($GLOBALS['TL_CTE']['includes'], 0, array('book' => '\Muspellheim\Books\ContentBook'));


/**
 * Insert tags
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\Muspellheim\Books\BookInsertTags', 'replaceInsertTags');


/**
 * Model mappings
 */
$GLOBALS['TL_MODELS'][\Muspellheim\Books\BookModel::getTable()]    = '\Muspellheim\Books\BookModel';
$GLOBALS['TL_MODELS'][\Muspellheim\Books\ChapterModel::getTable()] = '\Muspellheim\Books\ChapterModel';

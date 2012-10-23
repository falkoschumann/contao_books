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
 * Table tl_book
 */
$GLOBALS['TL_DCA']['tl_book'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => 'tl_book_chapter',
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title', 'author'),
			'format'                  => '%s <span style="color:#b3b3b3;padding-left:3px">[%s]</span>'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_book']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_book']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_book']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_book']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(''),
		'default'                     => '{book_legend},title,subtitle,alias,author;{meta_legend:hide},language,category,note;{text_legend},text;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		''                            => ''
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'search'                  => true,
			'sorting'                 => true
		),
		'subtitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['subtitle'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'search'                  => true
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'rgxp'=>'alnum', 'spaceToUnderscore'=>true, 'tl_class'=>'w50'),
			'search'                  => true,
			'sorting'                 => true
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['author'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['language'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('minlength'=>2, 'maxlength'=>2, 'rgxp'=>'alpha', 'tl_class'=>'w50'),
			'filter'                  => true,
			'sorting'                 => true
		),
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['category'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true
		),
		'note' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['note'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('wrap'=>'soft'),
			'search'                  => true
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['text'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true, 'rte'=>'tinyMCE', 'doNotShow'=>true),
			'search'                  => true
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_book']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
			'filter'                  => true,
			'sorting'                 => true
		)
	)
);

?>
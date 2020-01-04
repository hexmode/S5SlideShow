<?php
/**
 * Copyright (c) 2010+ Vitaliy Filippov <vitalif@mail.ru>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * s5SlideShow SpecialPage for S5SlideShow extension
 *
 * @file
 * @ingroup Extensions
 */
namespace MediaWiki\Extension\S5SlideShow;

use SpecialPage;

class SpecialS5SlideShow extends SpecialPage {
	public function __construct() {
		parent::__construct( 's5SlideShow' );
	}

	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'special-s5SlideShow-title' ) );
		$out->addHelpLink( 'S5SlideShow' );
		$out->addWikiMsg( 'special-s5SlideShow-intro' );
	}

	protected function getGroupName() {
		return 'other';
	}
}

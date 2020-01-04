<?php

/**
 * Copyright (c) 2010+ Vitaliy Filippov <vitalif@mail.ru>
 * Copyright (c) 2020  NicheWork, LLC
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
namespace MediaWiki\Extension\S5SlideShow;

use Action as MWAction;
use Article as MWArticle;

/**
 * @author Vitaliy Filippov <vitalif@mail.ru>
 * @author Mark A. Hershberger <mah@nichework.com>
 * @package MediaWiki
 * @subpackage Extensions
 */

class Action extends MWAction {

	// This action is called 'example_action'.  This class will only
	// be invoked when the specified action is requested.
	public function getName() {
		// This should be the same name as used when registering the
		// action in $wgActions.
		return 'slide';
	}

	// This is the function that is called whenever a page is being
	// requested using this action.  You should not use globals
	// $wgOut, $wgRequest, etc.  Instead, use the methods provided by
	// the Action class (e.g. $this->getOutput()), instead.
	public function show() {
		global $wgMaxRedirects;

		$req = $this->getRequest();
		$s5skin = trim( $req->getVal( 's5skin' ) );
		if ( preg_match( '/[^\w-]/', $s5skin ) ) {
			$s5skin = '';
		}
		$print = $req->getVal( 'print' );
		if ( $print ) {
			preg_match_all( '/\d+/s', $print, $print, PREG_PATTERN_ORDER );
			$print = $print[0];
		} else {
			$print = false;
		}
		if ( $req->getVal( 's5css' ) ) {
			// Get CSS for a given S5 style (from wiki-pages)
			Render::genStyle( $s5skin, $print );
			return false;
		}
		// Check if the article is readable
		$title = $this->getTitle();
		for ( $r = 0; $r < $wgMaxRedirects && $title->isRedirect(); $r++ ) {
			if ( !$title->userCan( 'read' ) ) {
				return true;
			}
			$title = WikiPage::newFromID( $title->getArticleID() )->followRedirect();
			$article = new MWArticle( $title );
		}
		// Hack for CustIS live preview
		// TODO remove support for loading text from session object and
		//      replace it by support for save-staying-in-edit-mode extension
		$content = $req->getVal( 'wpTextbox1' );
		if ( !$content && ( $t1 = $req->getSessionData( 'wpTextbox1' ) ) ) {
			$content = $t1;
			$req->setSessionData( 'wpTextbox1', null );
		}
		// Generate presentation HTML content
		$slideShow = new Render( $title, $content );
		if ( $s5skin ) {
			$slideShow->attr['style'] = $s5skin;
		}
		$slideShow->genSlideFile( $print );
		return false;
	}
}

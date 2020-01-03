<?php

/**
 * Extension to create slide shows from wiki pages using improved S5
 * (http://meyerweb.com/eric/tools/s5/)
 *
 * Copyright (c) 2010+ Vitaliy Filippov <vitalif@mail.ru>
 * Copyright (c) 2017-2020 Wolfgang Fahl
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
namespace MediaWiki\Extensions\S5SlideShow;

use Article as MWArticle;
use Wikimedia\AtEase\AtEase;

/**
 * @author Vitaliy Filippov <vitalif@mail.ru>
 * @author Wolfgang Fahl
 * @package MediaWiki
 * @subpackage Extensions
 */

// Used to display CSS files instead of non-existing special articles
// (MediaWiki:S5/<skin>/<stylesheet>)
class Article extends MWArticle {
	var $s5skin, $s5file;
	// Create the object and remember s5skin and s5file
	public function __construct( $title, $s5skin, $s5file ) {
		$this->mPage = $this->newPage( $title );
		$this->mOldId = null;
		$this->s5skin = $s5skin;
		$this->s5file = $s5file;
	}

	// Get content from the file
	public function getContent() {
		if ( $this->getID() == 0 ) {
			$this->mContent = AtEase::quietCall( 'file_get_contents', $this->s5file );
		} else {
			$this->loadContent();
		}
		return $this->mContent;
	}

	// Show default content from the file
	public function showMissingArticle() {
		global $wgOut, $wgRequest, $wgParser;
		// Copy-paste from includes/Article.php:
		// Show delete and move logs
		LogEventsList::showLogExtract(
			$wgOut, [ 'delete', 'move' ], $this->mTitle->getPrefixedText(), '', [
				'lim' => 10,
				'conds' => [ "log_action != 'revision'" ],
				'showIfEmpty' => false,
				'msgKey' => [ 'moveddeleted-notice' ]
			]
		);
		// Show error message
		$oldid = $this->getOldID();
		if ( $oldid ) {
			$text = wfMessage(
				'missing-article', $this->mTitle->getPrefixedText(),
				wfMessage( 'missingarticle-rev', $oldid )->plain()
			)->plain();
		} else {
 			$text = $this->getContent();
		}
		if ( $wgParser->mTagHooks['source'] ) {
			$text = "<source lang='css'>\n$text\n</source>";
		}
		$text = "<div class='noarticletext'>\n$text\n</div>";
		$wgOut->addWikiText( $text );
	}
}

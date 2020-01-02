<?php
/**
 * s5SlideShow SpecialPage for S5SlideShow extension
 *
 * @file
 * @ingroup Extensions
 */
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

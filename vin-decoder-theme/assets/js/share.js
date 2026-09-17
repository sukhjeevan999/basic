/**
 * Handles the "copy link" button in the share-this-article bar
 * (single.php / vindecoder_post_share_html() in functions.php). The
 * platform share links (X, Facebook, WhatsApp, LinkedIn) are plain
 * anchors and need no JS at all.
 *
 * @package VinDecoderTheme
 */
( function ( window, document ) {
	'use strict';

	function showToast( message ) {
		var toast = document.querySelector( '.vd-toast' );
		if ( ! toast ) {
			toast = document.createElement( 'div' );
			toast.className = 'vd-toast';
			document.body.appendChild( toast );
		}
		toast.textContent = message;
		toast.classList.add( 'is-visible' );
		window.clearTimeout( toast.vdTimeout );
		toast.vdTimeout = window.setTimeout( function () {
			toast.classList.remove( 'is-visible' );
		}, 2200 );
	}

	function copyText( text ) {
		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			return navigator.clipboard.writeText( text );
		}
		// Fallback for browsers without the Clipboard API.
		return new Promise( function ( resolve, reject ) {
			var temp = document.createElement( 'textarea' );
			temp.value = text;
			temp.style.position = 'fixed';
			temp.style.opacity = '0';
			document.body.appendChild( temp );
			temp.select();
			try {
				document.execCommand( 'copy' );
				resolve();
			} catch ( e ) {
				reject( e );
			} finally {
				document.body.removeChild( temp );
			}
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '.vd-share-copy' );
		if ( ! button ) {
			return;
		}
		event.preventDefault();
		var url = button.getAttribute( 'data-copy-url' );
		if ( ! url ) {
			return;
		}
		copyText( url )
			.then( function () {
				showToast( 'Link copied.' );
			} )
			.catch( function () {
				showToast( 'Could not copy the link.' );
			} );
	} );
} )( window, document );

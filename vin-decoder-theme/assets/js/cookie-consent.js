/**
 * Cookie consent banner.
 *
 * Talks to Google Consent Mode (the gtag('consent', ...) defaults are set
 * inline in header.php, before any analytics/ads tag loads). This file only
 * handles: showing the banner once per visitor, remembering their choice,
 * and calling gtag('consent', 'update', ...) when they accept. Nothing here
 * blocks the site's own functionality either way — the VIN decoder itself
 * sets no cookies and needs no consent to work.
 *
 * @package VinDecoderTheme
 */
( function ( window, document ) {
	'use strict';

	var STORAGE_KEY = 'vd-cookie-consent';

	function getStoredChoice() {
		try {
			return window.localStorage.getItem( STORAGE_KEY );
		} catch ( e ) {
			return null;
		}
	}

	function storeChoice( value ) {
		try {
			window.localStorage.setItem( STORAGE_KEY, value );
		} catch ( e ) {
			// Private-mode/blocked storage — the choice just won't persist
			// across visits, the banner will show again next time.
		}
	}

	function updateConsent( granted ) {
		if ( typeof window.gtag !== 'function' ) {
			return;
		}
		var state = granted ? 'granted' : 'denied';
		window.gtag( 'consent', 'update', {
			ad_storage: state,
			ad_user_data: state,
			ad_personalization: state,
			analytics_storage: state
		} );
	}

	function buildBanner() {
		var banner = document.createElement( 'div' );
		banner.className = 'vd-cookie-banner';
		banner.setAttribute( 'role', 'dialog' );
		banner.setAttribute( 'aria-label', 'Cookie notice' );

		banner.innerHTML =
			'<p class="vd-cookie-text">' +
				'We use cookies to understand site traffic and improve the decoder tools. ' +
				'No cookies are needed for the VIN lookup itself. See our ' +
				'<a href="' + ( window.VinDecoderCookieConfig && window.VinDecoderCookieConfig.privacyUrl ? window.VinDecoderCookieConfig.privacyUrl : '/privacy-policy/' ) + '">Privacy Policy</a>.' +
			'</p>' +
			'<div class="vd-cookie-actions">' +
				'<button type="button" class="vd-btn vd-btn-secondary vd-cookie-decline">Only Essential</button>' +
				'<button type="button" class="vd-btn vd-btn-primary vd-cookie-accept">Accept All</button>' +
			'</div>';

		return banner;
	}

	function showBanner() {
		if ( document.querySelector( '.vd-cookie-banner' ) ) {
			return;
		}

		var banner = buildBanner();
		document.body.appendChild( banner );

		// Animate in on the next frame so the transition actually runs.
		window.requestAnimationFrame( function () {
			banner.classList.add( 'is-visible' );
		} );

		banner.querySelector( '.vd-cookie-accept' ).addEventListener( 'click', function () {
			storeChoice( 'accepted' );
			updateConsent( true );
			hideBanner( banner );
		} );

		banner.querySelector( '.vd-cookie-decline' ).addEventListener( 'click', function () {
			storeChoice( 'declined' );
			updateConsent( false );
			hideBanner( banner );
		} );
	}

	function hideBanner( banner ) {
		banner.classList.remove( 'is-visible' );
		window.setTimeout( function () {
			if ( banner.parentNode ) {
				banner.parentNode.removeChild( banner );
			}
		}, 250 );
	}

	// Exposed so the footer's "Cookie Settings" link can reopen it — lets a
	// visitor change their mind at any time, not just on their first visit.
	window.vdReopenCookieBanner = function () {
		var existing = document.querySelector( '.vd-cookie-banner' );
		if ( existing ) {
			return;
		}
		showBanner();
	};

	document.addEventListener( 'DOMContentLoaded', function () {
		if ( ! getStoredChoice() ) {
			showBanner();
		}
	} );
} )( window, document );

/**
 * VinDecoderTheme — mobile navigation toggle.
 * Vanilla JS, no dependencies.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var toggle = document.getElementById( 'vd-nav-toggle' );
		var nav = document.getElementById( 'vd-primary-nav' );

		if ( ! toggle || ! nav ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = nav.classList.toggle( 'is-open' );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		nav.addEventListener( 'click', function ( event ) {
			if ( event.target.tagName === 'A' && nav.classList.contains( 'is-open' ) ) {
				nav.classList.remove( 'is-open' );
				toggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! nav.classList.contains( 'is-open' ) ) {
				return;
			}
			if ( ! nav.contains( event.target ) && ! toggle.contains( event.target ) ) {
				nav.classList.remove( 'is-open' );
				toggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );

		// Dark/light mode toggle. The initial theme is already set by the
		// inline script in header.php (before paint) — this just handles
		// the click and remembers the choice.
		var themeToggle = document.getElementById( 'vd-theme-toggle' );
		if ( themeToggle ) {
			themeToggle.addEventListener( 'click', function () {
				var current = document.documentElement.getAttribute( 'data-vd-theme' ) === 'dark' ? 'dark' : 'light';
				var next = 'dark' === current ? 'light' : 'dark';
				document.documentElement.setAttribute( 'data-vd-theme', next );
				try {
					localStorage.setItem( 'vd-theme', next );
				} catch ( e ) {
					// Ignore — the toggle still works for the current page view.
				}
			} );
		}
	} );
} )();

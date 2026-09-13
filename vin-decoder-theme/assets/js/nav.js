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

		// Car / Bike submenu toggles. wp_nav_menu() (and our PHP fallback)
		// already mark each parent <li> with "menu-item-has-children" and
		// wrap its children in a "sub-menu" <ul> — desktop shows the
		// submenu on CSS :hover alone, but touch/keyboard users need an
		// explicit control, so we inject a chevron button per parent item
		// that toggles an "is-open" class (see style.css).
		var submenuParents = nav.querySelectorAll( 'li.menu-item-has-children' );
		submenuParents.forEach( function ( parentItem ) {
			var parentLink = parentItem.querySelector( ':scope > a' );
			if ( ! parentLink || parentItem.querySelector( ':scope > .vd-submenu-toggle' ) ) {
				return;
			}

			var toggleBtn = document.createElement( 'button' );
			toggleBtn.type = 'button';
			toggleBtn.className = 'vd-submenu-toggle';
			toggleBtn.setAttribute( 'aria-expanded', 'false' );
			toggleBtn.setAttribute( 'aria-label', 'Show submenu' );
			toggleBtn.innerHTML = '<svg width="12" height="8" viewBox="0 0 12 8" fill="none" aria-hidden="true"><path d="M1 1.5 6 6.5 11 1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			parentLink.insertAdjacentElement( 'afterend', toggleBtn );

			toggleBtn.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				event.stopPropagation();
				var isOpen = parentItem.classList.toggle( 'is-open' );
				toggleBtn.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

				// Close any other open submenu so only one is open at a time.
				submenuParents.forEach( function ( other ) {
					if ( other !== parentItem ) {
						other.classList.remove( 'is-open' );
						var otherToggle = other.querySelector( ':scope > .vd-submenu-toggle' );
						if ( otherToggle ) {
							otherToggle.setAttribute( 'aria-expanded', 'false' );
						}
					}
				} );
			} );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! nav.contains( event.target ) ) {
				submenuParents.forEach( function ( parentItem ) {
					parentItem.classList.remove( 'is-open' );
					var toggleBtn = parentItem.querySelector( ':scope > .vd-submenu-toggle' );
					if ( toggleBtn ) {
						toggleBtn.setAttribute( 'aria-expanded', 'false' );
					}
				} );
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

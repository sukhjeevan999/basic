/**
 * VinDecoderTheme — client-side VIN decode UI.
 *
 * Validates the VIN format in the browser, then calls this site's own
 * same-origin REST endpoint (registered in functions.php), which proxies
 * the request server-side to the free public NHTSA vPIC API. No API key,
 * no per-lookup cost.
 *
 * @package VinDecoderTheme
 */
( function ( window, document ) {
	'use strict';

	// VIN standard: 17 characters, letters A-Z and digits 0-9, excluding
	// I, O, and Q (to avoid confusion with 1 and 0).
	var VIN_PATTERN = /^[A-HJ-NPR-Z0-9]{17}$/;

	function sanitizeVinInput( value ) {
		return value.toUpperCase().replace( /[^A-HJ-NPR-Z0-9]/g, '' ).slice( 0, 17 );
	}

	function formatFact( value ) {
		var span = document.createElement( 'span' );
		if ( value && String( value ).trim() !== '' ) {
			span.textContent = value;
			span.className = '';
		} else {
			span.textContent = 'Not available';
			span.className = 'is-empty';
		}
		return span;
	}

	function setFact( id, value ) {
		var el = document.getElementById( id );
		if ( ! el ) {
			return;
		}
		el.innerHTML = '';
		el.appendChild( formatFact( value ) );
	}

	function buildEngineSummary( result ) {
		var parts = [];
		if ( result.engineDisp ) {
			parts.push( result.engineDisp + 'L' );
		}
		if ( result.engineCyl ) {
			parts.push( result.engineCyl + '-cyl' );
		}
		if ( result.engineHP ) {
			parts.push( result.engineHP + ' hp' );
		}
		return parts.length ? parts.join( ', ' ) : '';
	}

	function buildPlantSummary( result ) {
		var parts = [];
		if ( result.plantCity ) {
			parts.push( result.plantCity );
		}
		if ( result.plantCountry ) {
			parts.push( result.plantCountry );
		}
		return parts.length ? parts.join( ', ' ) : '';
	}

	/**
	 * Some manufacturers run their own free, official "window sticker"
	 * (Monroney label) lookup by VIN — a PDF with factory options, colors,
	 * and MSRP that goes beyond what NHTSA's public vPIC data covers.
	 * These are the manufacturer's own customer-service tools, not a
	 * public API, so we only ever link out to them (new tab, no
	 * scraping/embedding) and only for the few brands with a known,
	 * stable URL pattern. Matched against the DECODED make (not just
	 * the current page's brand) so it's correct even on a mismatch.
	 */
	function buildWindowStickerUrl( result ) {
		var make = ( result.make || '' ).toUpperCase();
		var vin = result.vin || '';
		if ( ! vin ) {
			return '';
		}
		if ( make.indexOf( 'FORD' ) !== -1 || make.indexOf( 'LINCOLN' ) !== -1 ) {
			return 'https://www.windowsticker.forddirect.com/windowsticker.pdf?vin=' + encodeURIComponent( vin );
		}
		if ( [ 'CHEVROLET', 'GMC', 'BUICK', 'CADILLAC' ].some( function ( m ) { return make.indexOf( m ) !== -1; } ) ) {
			return 'https://cws.gm.com/vs-cws/vehshop/v2/vehicle/windowsticker?vin=' + encodeURIComponent( vin );
		}
		if ( [ 'JEEP', 'DODGE', 'RAM', 'CHRYSLER' ].some( function ( m ) { return make.indexOf( m ) !== -1; } ) ) {
			return 'https://www.chrysler.com/hostd/windowsticker/getWindowStickerPdf.do?vin=' + encodeURIComponent( vin );
		}
		return '';
	}

	function renderVinStrip( vin ) {
		var strip = document.getElementById( 'vd-vin-strip' );
		if ( ! strip ) {
			return;
		}
		strip.innerHTML = '';
		for ( var i = 0; i < vin.length; i++ ) {
			var box = document.createElement( 'span' );
			box.className = 'vd-vin-char';
			box.textContent = vin.charAt( i );
			var position = i + 1;
			if ( position <= 3 ) {
				box.classList.add( 'is-wmi' ); // World Manufacturer Identifier.
			} else if ( position === 9 ) {
				box.classList.add( 'is-check' ); // Check digit.
			} else if ( position === 10 ) {
				box.classList.add( 'is-year' ); // Model year code.
			}
			strip.appendChild( box );
		}
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var form = document.getElementById( 'vd-decode-form' );
		if ( ! form ) {
			return;
		}

		var vinInput = document.getElementById( 'vd-vin-input' );
		var charCount = document.getElementById( 'vd-char-count' );
		var errorEl = document.getElementById( 'vd-form-error' );
		var resultsEl = document.getElementById( 'vd-results' );
		var toolCard = document.getElementById( 'vd-tool-card' );
		var decodeBtn = document.getElementById( 'vd-decode-btn' );
		var mismatchEl = document.getElementById( 'vd-brand-mismatch' );
		var toastEl = document.getElementById( 'vd-toast' );
		var windowStickerLink = document.getElementById( 'vd-window-sticker-link' );
		var windowStickerNote = document.getElementById( 'vd-window-sticker-note' );

		var config = window.VinDecoderConfig || {};

		function showToast( message ) {
			if ( ! toastEl ) {
				return;
			}
			toastEl.textContent = message;
			toastEl.classList.add( 'is-visible' );
			window.clearTimeout( showToast._t );
			showToast._t = window.setTimeout( function () {
				toastEl.classList.remove( 'is-visible' );
			}, 2600 );
		}

		function showError( message ) {
			errorEl.textContent = message;
			errorEl.hidden = false;
		}

		function clearError() {
			errorEl.hidden = true;
			errorEl.textContent = '';
		}

		function updateCharCount() {
			var len = vinInput.value.length;
			charCount.textContent = len + ' / 17';
			charCount.classList.toggle( 'is-complete', len === 17 );
		}

		vinInput.addEventListener( 'input', function () {
			var cursorAtEnd = vinInput.selectionStart === vinInput.value.length;
			vinInput.value = sanitizeVinInput( vinInput.value );
			if ( cursorAtEnd ) {
				vinInput.setSelectionRange( vinInput.value.length, vinInput.value.length );
			}
			updateCharCount();
		} );
		updateCharCount();

		function setBusy( isBusy ) {
			decodeBtn.disabled = isBusy;
			decodeBtn.innerHTML = isBusy
				? '<span class="vd-spinner" aria-hidden="true"></span> Decoding…'
				: 'Decode VIN';
		}

		function renderResults( result ) {
			renderVinStrip( result.vin );

			setFact( 'vd-fact-make', result.make );
			setFact( 'vd-fact-model', result.model );
			setFact( 'vd-fact-year', result.modelYear );
			setFact( 'vd-fact-body', result.bodyClass );
			setFact( 'vd-fact-engine', buildEngineSummary( result ) );
			setFact( 'vd-fact-fuel', result.fuelType );
			setFact( 'vd-fact-drive', result.driveType );
			setFact( 'vd-fact-transmission', result.transmission );
			setFact( 'vd-fact-plant', buildPlantSummary( result ) );

			var expected = ( config.expectedMake || '' ).toUpperCase();
			var actual = ( result.make || '' ).toUpperCase();
			if ( expected && actual && actual.indexOf( expected ) === -1 && expected.indexOf( actual ) === -1 ) {
				mismatchEl.innerHTML =
					'<strong>Heads up:</strong> this VIN decodes as a <strong>' + ( result.make || 'different manufacturer' ) +
					'</strong>, not ' + ( config.brandLabel || expected ) +
					'. The results above reflect exactly what the NHTSA database returned for this VIN.';
				mismatchEl.hidden = false;
			} else {
				mismatchEl.hidden = true;
			}

			if ( windowStickerLink && windowStickerNote ) {
				var stickerUrl = buildWindowStickerUrl( result );
				if ( stickerUrl ) {
					windowStickerLink.href = stickerUrl;
					windowStickerLink.hidden = false;
					windowStickerNote.hidden = false;
				} else {
					windowStickerLink.hidden = true;
					windowStickerNote.hidden = true;
				}
			}

			resultsEl.hidden = false;
			resultsEl.scrollIntoView( { behavior: 'smooth', block: 'start' } );

			window.VinDecoderLastResult = result;
		}

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			clearError();

			var vin = sanitizeVinInput( vinInput.value );

			if ( ! VIN_PATTERN.test( vin ) ) {
				showError( 'That doesn\'t look like a valid 17-character VIN. VINs never contain the letters I, O, or Q.' );
				vinInput.focus();
				return;
			}

			if ( ! config.ajaxUrl ) {
				showError( 'The decoder isn\'t configured correctly. Please try again later.' );
				return;
			}

			setBusy( true );

			// Uses admin-ajax.php rather than the WP REST API on purpose: the
			// REST API runs a global cookie/nonce check on every request when
			// the visiting browser holds a valid wp-admin login cookie, which
			// can fail with WordPress's own "Cookie check failed" error on a
			// cached page whose baked-in nonce no longer matches (e.g. right
			// after logging into wp-admin on that same device). admin-ajax.php
			// has no such check, so this tool works the same whether or not
			// the visitor happens to be logged in.
			var url = config.ajaxUrl + '?action=vindecoder_decode&vin=' + encodeURIComponent( vin );

			fetch( url, { method: 'GET' } )
				.then( function ( response ) {
					return response.json().then( function ( body ) {
						if ( ! response.ok ) {
							throw new Error( ( body && body.message ) || 'Decode failed.' );
						}
						return body;
					} );
				} )
				.then( function ( result ) {
					renderResults( result );
					showToast( 'VIN decoded successfully.' );
				} )
				.catch( function ( error ) {
					showError( error.message || 'Something went wrong decoding that VIN. Please try again.' );
				} )
				.finally( function () {
					setBusy( false );
				} );
		} );

		var decodeAnotherBtn = document.getElementById( 'vd-decode-another' );
		if ( decodeAnotherBtn ) {
			decodeAnotherBtn.addEventListener( 'click', function () {
				resultsEl.hidden = true;
				vinInput.value = '';
				updateCharCount();
				toolCard.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				vinInput.focus();
			} );
		}
	} );
} )( window, document );

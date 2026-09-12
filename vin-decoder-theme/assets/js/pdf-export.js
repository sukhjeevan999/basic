/**
 * VinDecoderTheme — client-side PDF / image export for a decode result.
 *
 * Uses html2canvas + jsPDF (loaded from CDN only on brand decoder pages,
 * see functions.php) to turn the #vd-results panel into a downloadable
 * PDF or PNG entirely in the browser.
 *
 * @package VinDecoderTheme
 */
( function ( window, document ) {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var pdfBtn = document.getElementById( 'vd-download-pdf' );
		var imageBtn = document.getElementById( 'vd-download-image' );
		var target = document.getElementById( 'vd-results' );

		if ( ! target || ( ! pdfBtn && ! imageBtn ) ) {
			return;
		}

		function buildFilename( extension ) {
			var result = window.VinDecoderLastResult || {};
			var slug = ( result.make || 'vin' ).toLowerCase().replace( /[^a-z0-9]+/g, '-' );
			var vin = result.vin || 'decode';
			return slug + '-' + vin + '.' + extension;
		}

		function withBusyState( button, isBusy ) {
			if ( ! button ) {
				return;
			}
			button.disabled = isBusy;
			if ( isBusy ) {
				button.dataset.originalText = button.textContent;
				button.innerHTML = '<span class="vd-spinner" aria-hidden="true"></span> Preparing…';
			} else if ( button.dataset.originalText ) {
				button.textContent = button.dataset.originalText;
			}
		}

		function captureResults() {
			if ( typeof window.html2canvas !== 'function' ) {
				return Promise.reject( new Error( 'html2canvas failed to load.' ) );
			}
			return window.html2canvas( target, {
				scale: Math.min( window.devicePixelRatio || 1, 2 ) + 0.5,
				backgroundColor: '#ffffff',
				useCORS: true,
				onclone: function ( clonedDoc ) {
					var actions = clonedDoc.querySelector( '.vd-results-actions' );
					if ( actions ) {
						actions.style.display = 'none';
					}
				}
			} );
		}

		function downloadBlob( blob, filename ) {
			var url = URL.createObjectURL( blob );
			var link = document.createElement( 'a' );
			link.href = url;
			link.download = filename;
			document.body.appendChild( link );
			link.click();
			document.body.removeChild( link );
			window.setTimeout( function () {
				URL.revokeObjectURL( url );
			}, 1000 );
		}

		function handlePdfExport() {
			withBusyState( pdfBtn, true );

			captureResults()
				.then( function ( canvas ) {
					if ( ! window.jspdf || ! window.jspdf.jsPDF ) {
						throw new Error( 'jsPDF failed to load.' );
					}
					var jsPDF = window.jspdf.jsPDF;
					var imgData = canvas.toDataURL( 'image/png' );
					var pdf = new jsPDF( { orientation: 'portrait', unit: 'pt', format: 'a4' } );
					var pageWidth = pdf.internal.pageSize.getWidth();
					var pageHeight = pdf.internal.pageSize.getHeight();
					var margin = 24;
					var imgWidth = pageWidth - margin * 2;
					var imgHeight = ( canvas.height * imgWidth ) / canvas.width;
					var heightLeft = imgHeight;
					var position = margin;

					pdf.addImage( imgData, 'PNG', margin, position, imgWidth, imgHeight, undefined, 'FAST' );
					heightLeft -= ( pageHeight - margin * 2 );

					while ( heightLeft > 0 ) {
						position = heightLeft - imgHeight + margin;
						pdf.addPage();
						pdf.addImage( imgData, 'PNG', margin, position, imgWidth, imgHeight, undefined, 'FAST' );
						heightLeft -= ( pageHeight - margin * 2 );
					}

					pdf.save( buildFilename( 'pdf' ) );
				} )
				.catch( function ( error ) {
					window.alert( 'Sorry, the PDF could not be generated (' + error.message + '). Please try "Download as Image" instead, or use your browser\'s Print to PDF.' );
				} )
				.finally( function () {
					withBusyState( pdfBtn, false );
				} );
		}

		function handleImageExport() {
			withBusyState( imageBtn, true );

			captureResults()
				.then( function ( canvas ) {
					return new Promise( function ( resolve, reject ) {
						canvas.toBlob( function ( blob ) {
							if ( blob ) {
								resolve( blob );
							} else {
								reject( new Error( 'Canvas export failed.' ) );
							}
						}, 'image/png' );
					} );
				} )
				.then( function ( blob ) {
					downloadBlob( blob, buildFilename( 'png' ) );
				} )
				.catch( function ( error ) {
					window.alert( 'Sorry, the image could not be generated (' + error.message + ').' );
				} )
				.finally( function () {
					withBusyState( imageBtn, false );
				} );
		}

		if ( pdfBtn ) {
			pdfBtn.addEventListener( 'click', handlePdfExport );
		}
		if ( imageBtn ) {
			imageBtn.addEventListener( 'click', handleImageExport );
		}
	} );
} )( window, document );

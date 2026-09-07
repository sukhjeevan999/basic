/**
 * TaperTheme — client-side PDF / image export for the generated schedule.
 *
 * Uses html2canvas + jsPDF (loaded from CDN only on the homepage, see
 * functions.php) to turn the #tt-results dashboard into a downloadable
 * PDF or PNG entirely in the browser. No server round-trip.
 *
 * @package TaperTheme
 */
( function ( window, document ) {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var pdfBtn = document.getElementById( 'tt-download-pdf' );
		var imageBtn = document.getElementById( 'tt-download-image' );
		var target = document.getElementById( 'tt-results' );

		if ( ! target || ( ! pdfBtn && ! imageBtn ) ) {
			return;
		}

		function buildFilename( extension ) {
			var meta = ( window.TaperTheme && window.TaperTheme.meta ) || {};
			var substance = meta.substance || 'schedule';
			var today = new Date().toISOString().slice( 0, 10 );
			return 'taper-schedule-' + substance + '-' + today + '.' + extension;
		}

		function withBusyState( button, isBusy ) {
			if ( ! button ) {
				return;
			}
			button.disabled = isBusy;
			if ( isBusy ) {
				button.dataset.originalText = button.textContent;
				button.innerHTML = '<span class="tt-spinner" aria-hidden="true"></span> Preparing…';
			} else if ( button.dataset.originalText ) {
				button.textContent = button.dataset.originalText;
			}
		}

		/**
		 * Render #tt-results to a canvas, hiding the action buttons in the
		 * cloned document so they don't appear in the export.
		 */
		function captureResults() {
			if ( typeof window.html2canvas !== 'function' ) {
				return Promise.reject( new Error( 'html2canvas failed to load.' ) );
			}

			return window.html2canvas( target, {
				scale: Math.min( window.devicePixelRatio || 1, 2 ) + 0.5,
				backgroundColor: '#ffffff',
				useCORS: true,
				onclone: function ( clonedDoc ) {
					var actions = clonedDoc.querySelector( '.tt-results-actions' );
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

					// A4 portrait, in points.
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

					// Paginate if the schedule is taller than one page.
					while ( heightLeft > 0 ) {
						position = heightLeft - imgHeight + margin;
						pdf.addPage();
						pdf.addImage( imgData, 'PNG', margin, position, imgWidth, imgHeight, undefined, 'FAST' );
						heightLeft -= ( pageHeight - margin * 2 );
					}

					pdf.save( buildFilename( 'pdf' ) );
				} )
				.catch( function ( error ) {
					window.alert( 'Sorry, the PDF could not be generated (' + error.message + '). Please try the "Download as Image" option or use your browser\'s Print to PDF feature instead.' );
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
					window.alert( 'Sorry, the image could not be generated (' + error.message + '). Please try the PDF option instead.' );
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

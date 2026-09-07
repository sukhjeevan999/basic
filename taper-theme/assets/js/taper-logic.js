/**
 * TaperTheme — client-side taper-down reduction schedule engine.
 *
 * 100% vanilla JavaScript. No server calls, no dependencies. All math and
 * persistence (via localStorage) happen entirely in the visitor's browser.
 *
 * Exposes `window.TaperTheme` with the current schedule/meta so that
 * pdf-export.js can read it without recomputing anything.
 *
 * @package TaperTheme
 */
( function ( window, document ) {
	'use strict';

	var STORAGE_KEY = 'tapertheme_schedule_v1';
	var CHECKIN_KEY = 'tapertheme_checkins_v1';

	var UNIT_LABELS = {
		cigarettes: { singular: 'cigarette', plural: 'cigarettes' },
		alcohol: { singular: 'drink', plural: 'drinks' },
		vaping: { singular: 'puff session', plural: 'puff sessions' },
		custom: { singular: 'unit', plural: 'units' }
	};

	/**
	 * Round a raw quota to sensible granularity per substance type.
	 * Cigarettes/vaping/custom -> nearest whole number.
	 * Alcohol -> nearest 0.5 (standard-drink increments).
	 */
	function roundQuota( value, substance ) {
		if ( value <= 0 ) {
			return 0;
		}
		if ( substance === 'alcohol' ) {
			return Math.round( value * 2 ) / 2;
		}
		return Math.round( value );
	}

	/**
	 * Build suggested time windows spreading `quota` uses across waking
	 * hours (08:00–22:00), evenly spaced.
	 */
	function buildTimeWindows( quota ) {
		if ( ! quota || quota <= 0 ) {
			return [];
		}
		var startHour = 8;
		var endHour = 22;
		var span = endHour - startHour;
		var count = Math.ceil( quota );
		var times = [];

		if ( count === 1 ) {
			times.push( startHour + span / 2 );
		} else {
			for ( var i = 0; i < count; i++ ) {
				times.push( startHour + ( span * i ) / ( count - 1 ) );
			}
		}

		return times.map( formatHour );
	}

	function formatHour( hourFloat ) {
		var totalMinutes = Math.round( hourFloat * 60 );
		var h = Math.floor( totalMinutes / 60 ) % 24;
		var m = totalMinutes % 60;
		var period = h >= 12 ? 'PM' : 'AM';
		var displayHour = h % 12;
		if ( displayHour === 0 ) {
			displayHour = 12;
		}
		var mm = m < 10 ? '0' + m : String( m );
		return displayHour + ':' + mm + ' ' + period;
	}

	function addDays( date, days ) {
		var result = new Date( date.getTime() );
		result.setDate( result.getDate() + days );
		return result;
	}

	function formatDate( date ) {
		return date.toLocaleDateString( undefined, { weekday: 'short', month: 'short', day: 'numeric' } );
	}

	function isoDate( date ) {
		var y = date.getFullYear();
		var m = String( date.getMonth() + 1 ).padStart( 2, '0' );
		var d = String( date.getDate() ).padStart( 2, '0' );
		return y + '-' + m + '-' + d;
	}

	/**
	 * Core algorithm: linear step-down from `baseline` to 0 across
	 * `totalDays`, quantized to sensible per-substance increments.
	 *
	 * @param {number} baseline    Current daily consumption.
	 * @param {number} totalDays   14, 21, or 30.
	 * @param {string} substance   cigarettes | alcohol | vaping | custom.
	 * @param {number} costPerUnit Cost per single unit (may be 0).
	 * @return {Object} { days: [...], totals: {...} }
	 */
	function computeSchedule( baseline, totalDays, substance, costPerUnit ) {
		var today = new Date();
		var days = [];
		var totalSaved = 0;
		var totalAvoided = 0;
		var previousQuota = baseline;

		for ( var day = 1; day <= totalDays; day++ ) {
			var rawQuota = baseline * ( totalDays - day ) / totalDays;
			var quota = roundQuota( rawQuota, substance );

			// Guarantee monotonic non-increasing schedule even after rounding.
			if ( quota > previousQuota ) {
				quota = previousQuota;
			}
			// Force the final day to zero.
			if ( day === totalDays ) {
				quota = 0;
			}
			previousQuota = quota;

			var avoided = Math.max( 0, baseline - quota );
			var saved = avoided * costPerUnit;

			totalAvoided += avoided;
			totalSaved += saved;

			var date = addDays( today, day - 1 );

			days.push( {
				day: day,
				iso: isoDate( date ),
				label: formatDate( date ),
				quota: quota,
				windows: buildTimeWindows( quota ),
				avoided: round2( avoided ),
				saved: round2( saved )
			} );
		}

		return {
			days: days,
			totals: {
				totalDays: totalDays,
				totalSaved: round2( totalSaved ),
				totalAvoided: round2( totalAvoided ),
				startQuota: days.length ? days[ 0 ].quota : 0
			}
		};
	}

	function round2( n ) {
		return Math.round( n * 100 ) / 100;
	}

	function getUnitLabel( substance, customUnit, plural ) {
		if ( substance === 'custom' && customUnit ) {
			return customUnit;
		}
		var entry = UNIT_LABELS[ substance ] || UNIT_LABELS.custom;
		return plural ? entry.plural : entry.singular;
	}

	function formatCurrency( amount ) {
		if ( ! amount ) {
			return '0.00';
		}
		return amount.toFixed( 2 );
	}

	/**
	 * ----------------------------------------------------------------
	 * Persistence
	 * ----------------------------------------------------------------
	 */
	function saveScheduleState( state ) {
		try {
			window.localStorage.setItem( STORAGE_KEY, JSON.stringify( state ) );
		} catch ( e ) {
			// Storage unavailable (private mode, quota exceeded, etc.) — fail silently.
		}
	}

	function loadScheduleState() {
		try {
			var raw = window.localStorage.getItem( STORAGE_KEY );
			return raw ? JSON.parse( raw ) : null;
		} catch ( e ) {
			return null;
		}
	}

	function saveCheckins( checkins ) {
		try {
			window.localStorage.setItem( CHECKIN_KEY, JSON.stringify( checkins ) );
		} catch ( e ) {
			// Ignore.
		}
	}

	function loadCheckins() {
		try {
			var raw = window.localStorage.getItem( CHECKIN_KEY );
			return raw ? JSON.parse( raw ) : {};
		} catch ( e ) {
			return {};
		}
	}

	/**
	 * ----------------------------------------------------------------
	 * DOM wiring
	 * ----------------------------------------------------------------
	 */
	document.addEventListener( 'DOMContentLoaded', function () {
		var form = document.getElementById( 'tt-taper-form' );
		if ( ! form ) {
			return;
		}

		var substanceEl = document.getElementById( 'tt-substance' );
		var customUnitEl = document.getElementById( 'tt-custom-unit' );
		var baselineEl = document.getElementById( 'tt-baseline' );
		var paceEl = document.getElementById( 'tt-pace' );
		var costEl = document.getElementById( 'tt-cost' );
		var errorEl = document.getElementById( 'tt-form-error' );
		var resultsEl = document.getElementById( 'tt-results' );
		var calendarGrid = document.getElementById( 'tt-calendar-grid' );
		var recalcBtn = document.getElementById( 'tt-recalculate' );
		var toastEl = document.getElementById( 'tt-toast' );

		var statDays = document.getElementById( 'tt-stat-days' );
		var statSaved = document.getElementById( 'tt-stat-saved' );
		var statStartQuota = document.getElementById( 'tt-stat-start-quota' );
		var statAvoided = document.getElementById( 'tt-stat-avoided' );

		var checkins = loadCheckins();

		// Toggle the custom-unit text field when "Custom" is selected.
		function syncCustomUnitField() {
			var isCustom = substanceEl.value === 'custom';
			customUnitEl.hidden = ! isCustom;
			customUnitEl.required = isCustom;
		}
		if ( substanceEl ) {
			substanceEl.addEventListener( 'change', syncCustomUnitField );
			syncCustomUnitField();
		}

		function showError( message ) {
			errorEl.textContent = message;
			errorEl.hidden = false;
		}

		function clearError() {
			errorEl.hidden = true;
			errorEl.textContent = '';
		}

		function showToast( message ) {
			if ( ! toastEl ) {
				return;
			}
			toastEl.textContent = message;
			toastEl.classList.add( 'is-visible' );
			window.clearTimeout( showToast._t );
			showToast._t = window.setTimeout( function () {
				toastEl.classList.remove( 'is-visible' );
			}, 2400 );
		}

		function renderDashboard( schedule, meta ) {
			var unitPlural = getUnitLabel( meta.substance, meta.customUnit, true );

			statDays.textContent = String( schedule.totals.totalDays );
			statSaved.textContent = meta.costPerUnit > 0 ? formatCurrency( schedule.totals.totalSaved ) : 'N/A';
			statStartQuota.textContent = schedule.totals.startQuota + ' ' + unitPlural;
			statAvoided.textContent = schedule.totals.totalAvoided + ' ' + unitPlural;

			calendarGrid.innerHTML = '';

			schedule.days.forEach( function ( d ) {
				var card = document.createElement( 'div' );
				card.className = 'tt-day-card';
				card.setAttribute( 'data-day', d.day );

				var checkinKey = meta.scheduleId + '-day-' + d.day;
				var isChecked = !! checkins[ checkinKey ];
				if ( isChecked ) {
					card.classList.add( 'is-complete' );
				}

				var windowsText = d.windows.length
					? d.windows.join( ' &middot; ' )
					: ( d.quota === 0 ? 'Target reached &mdash; day off!' : '' );

				card.innerHTML =
					'<div class="tt-day-number">Day ' + d.day + '</div>' +
					'<div class="tt-day-date">' + d.label + '</div>' +
					'<div class="tt-day-quota">' + d.quota + ' <small>' + unitPlural + '</small></div>' +
					'<div class="tt-day-windows">' + windowsText + '</div>' +
					'<label class="tt-day-checkbox">' +
						'<input type="checkbox" data-checkin-key="' + checkinKey + '"' + ( isChecked ? ' checked' : '' ) + '>' +
						'<span>Completed</span>' +
					'</label>';

				calendarGrid.appendChild( card );
			} );

			// Wire up checkbox persistence.
			calendarGrid.querySelectorAll( '[data-checkin-key]' ).forEach( function ( checkbox ) {
				checkbox.addEventListener( 'change', function () {
					var key = checkbox.getAttribute( 'data-checkin-key' );
					checkins[ key ] = checkbox.checked;
					saveCheckins( checkins );
					checkbox.closest( '.tt-day-card' ).classList.toggle( 'is-complete', checkbox.checked );
				} );
			} );

			resultsEl.hidden = false;
			resultsEl.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		}

		function generateScheduleId( meta ) {
			return [ meta.substance, meta.baseline, meta.totalDays, meta.costPerUnit, meta.customUnit || '' ].join( '_' );
		}

		function handleGenerate( meta ) {
			var schedule = computeSchedule( meta.baseline, meta.totalDays, meta.substance, meta.costPerUnit );
			meta.scheduleId = generateScheduleId( meta );

			window.TaperTheme = window.TaperTheme || {};
			window.TaperTheme.schedule = schedule;
			window.TaperTheme.meta = meta;

			saveScheduleState( { schedule: schedule, meta: meta, savedAt: Date.now() } );
			renderDashboard( schedule, meta );
		}

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			clearError();

			var substance = substanceEl.value;
			var customUnit = customUnitEl.value.trim();
			var baseline = parseFloat( baselineEl.value );
			var totalDays = parseInt( paceEl.value, 10 );
			var costPerUnit = parseFloat( costEl.value );

			if ( isNaN( baseline ) || baseline <= 0 ) {
				showError( 'Please enter a valid daily amount greater than zero.' );
				baselineEl.focus();
				return;
			}
			if ( baseline > 500 ) {
				showError( 'That daily amount looks unusually high — please double check it.' );
				baselineEl.focus();
				return;
			}
			if ( substance === 'custom' && ! customUnit ) {
				showError( 'Please name the unit you are tracking (e.g. "pills").' );
				customUnitEl.focus();
				return;
			}
			if ( isNaN( costPerUnit ) || costPerUnit < 0 ) {
				costPerUnit = 0;
			}

			handleGenerate( {
				substance: substance,
				customUnit: customUnit,
				baseline: baseline,
				totalDays: totalDays,
				costPerUnit: costPerUnit
			} );

			showToast( 'Schedule generated! Progress saves automatically on this device.' );
		} );

		if ( recalcBtn ) {
			recalcBtn.addEventListener( 'click', function () {
				resultsEl.hidden = true;
				document.getElementById( 'tt-taper-form-card' ).scrollIntoView( { behavior: 'smooth', block: 'start' } );
			} );
		}

		// Restore a previously generated schedule on page load, if present.
		var saved = loadScheduleState();
		if ( saved && saved.schedule && saved.meta ) {
			window.TaperTheme = window.TaperTheme || {};
			window.TaperTheme.schedule = saved.schedule;
			window.TaperTheme.meta = saved.meta;

			// Repopulate the form with the last-used values for convenience.
			if ( substanceEl ) {
				substanceEl.value = saved.meta.substance;
				syncCustomUnitField();
			}
			if ( customUnitEl && saved.meta.customUnit ) {
				customUnitEl.value = saved.meta.customUnit;
			}
			if ( baselineEl ) {
				baselineEl.value = saved.meta.baseline;
			}
			if ( paceEl ) {
				paceEl.value = saved.meta.totalDays;
			}
			if ( costEl && saved.meta.costPerUnit ) {
				costEl.value = saved.meta.costPerUnit;
			}

			renderDashboard( saved.schedule, saved.meta );
			resultsEl.hidden = false;
		}
	} );

	// Expose a small public API in case other scripts (e.g. pdf-export.js)
	// need to recompute or read values directly.
	window.TaperTheme = window.TaperTheme || {};
	window.TaperTheme.computeSchedule = computeSchedule;
	window.TaperTheme.getUnitLabel = getUnitLabel;
	window.TaperTheme.formatCurrency = formatCurrency;
} )( window, document );

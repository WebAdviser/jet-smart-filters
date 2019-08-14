( function( $ ) {

	"use strict";

	var JetSmartFiltersAdminData = window.JetSmartFiltersAdminData || false;

	var JetSmartFiltersAdmin = {

		init: function() {

			var self = JetSmartFiltersAdmin;

			$( document )
				.on( 'change.JetSmartFiltersAdmin', '#_filter_type', self.switchQueryVar )
				.on( 'change.JetSmartFiltersAdmin', '#_data_source', self.switchQueryVar )
				.on( 'change.JetSmartFiltersAdmin', '#_date_source', self.switchQueryVar )
				.on( 'change.JetSmartFiltersAdmin', '#_s_by', self.switchQueryVar )

				.on( 'change.JetSmartFiltersAdmin', '#_data_source', self.updateExcludeInclude )
				.on( 'change.JetSmartFiltersAdmin', '#_source_post_type', self.updateExcludeInclude )
				.on( 'change.JetSmartFiltersAdmin', '#_source_taxonomy', self.updateExcludeInclude )
				.on( 'ready.JetSmartFiltersAdmin', self.updateExcludeInclude )

				.on( 'ready.JetSmartFiltersAdmin', self.updateColorImageOptions )
				.on( 'change.JetSmartFiltersAdmin', '#_data_source', self.updateColorImageOptions )
				.on( 'cx-control-init', self.updateColorImageOptions )
				.on( 'change.JetSmartFiltersAdmin', '#_source_post_type', self.initColorImageOptions )
				.on( 'change.JetSmartFiltersAdmin', '#_source_taxonomy', self.initColorImageOptions )
				.on( 'change.JetSmartFiltersAdmin', '#_color_image_type', self.switchColorImageControls );

			self.switchQueryVar();

			$( '#_filter_type' ).attr( 'required', 'required' );

		},

		updateColorImageOptions: function( event, item ) {
			JetSmartFiltersAdmin.switchColorImageControls();
			JetSmartFiltersAdmin.initColorImageOptions( item );
		},

		updateExcludeInclude: function() {

			var taxonomy       = $( '#_source_taxonomy option:selected' ).val(),
				postType       = $( '#_source_post_type option:selected' ).val(),
				source         = $( '#_data_source option:selected' ).val();

			$.ajax({
				url: JetSmartFiltersAdminData.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'jet_smart_filters_admin',
					taxonomy: taxonomy,
					post_type: postType,
				},
			}).done( function( response ) {

				var excludeIncludeInput =  $('#_data_exclude_include');

				switch ( source ) {
					case 'taxonomies':
						excludeIncludeInput.html( response.terms );
						break;
					case 'posts':
						excludeIncludeInput.html( response.posts );
						break;
				}

				excludeIncludeInput.val( JetSmartFiltersAdminData.dataExcludeInclude );
				excludeIncludeInput.trigger( 'change' );

			});

		},

		switchColorImageControls: function() {

			var filter_type = $( '#_filter_type option:selected' ).val(),
				type        = $( '#_color_image_type option:selected' ).val(),
				source      = $( '#_data_source option:selected' ).val(),
				repeater    = $( '.jet-smart-filters-color-image' );

			if ( 'color-image' === filter_type ){
				repeater.attr( 'data-type', type );
				repeater.attr( 'data-source', source );
			}

		},

		initColorImageOptions: function( item ) {

			var taxonomy    = $( '#_source_taxonomy option:selected' ).val(),
				filter_type = $( '#_filter_type option:selected' ).val(),
				postType    = $( '#_source_post_type option:selected' ).val(),
				source      = $( '#_data_source option:selected' ).val();

			if ( 'color-image' !== filter_type ){
				return;
			}

			$.ajax({
				url: JetSmartFiltersAdminData.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'jet_smart_filters_admin',
					taxonomy: taxonomy,
					post_type: postType,
				},
			}).done( function( response ) {

				var is_last = false;

				var $repeaterItems = $('.cx-ui-select[name*="selected_value"]');

				if ( 'undefined' !== typeof item ){
					if ( '.cx-ui-repeater-item:last' === item.target.selector ){
						$repeaterItems = $('.cx-ui-select[name*="selected_value"]:last');
						is_last = true;
					}
				}

				switch ( source ) {
					case 'taxonomies':
						$repeaterItems.each( function() {
							$(this).html( response.terms );
						} );
						break;
					case 'posts':
						$repeaterItems.each( function() {
							$(this).html( response.posts );
						} );
						break;
				}

				if ( !is_last ){
					JetSmartFiltersAdmin.setColorImageOptions();
				}

			});

		},

		setColorImageOptions: function(  ) {
			var $options = JetSmartFiltersAdminData.dataColorImage;

			$.each( $options, function(  key, value ) {
				$('.cx-ui-select[name="_source_color_image_input['+ key +'][selected_value]"]').val( value.selected_value );
			} );
		},

		switchQueryVar: function() {

			var type         = $( '#_filter_type option:selected' ).val(),
				source       = $( '#_data_source option:selected' ).val(),
				sourceSelect = $( '#_data_source' ),
				dateSource   = $( '#_date_source option:selected' ).val(),
				sBy          = $( '#_s_by option:selected' ).val(),
				types        = ['checkboxes', 'select', 'radio', 'color-image'],
				sources      = ['taxonomies'],
				hidden       = false,
				$queryVar    = $( 'div[data-control-name="_query_var"]' );

			if ( 'color-image' === type ){
				sourceSelect.find('option[value="custom_fields"]').addClass( 'cx-control-hidden' );

				if( 'custom_fields' === source ){
					sourceSelect.val('').change();
				}
			} else {
				sourceSelect.find('option[value="custom_fields"]').removeClass( 'cx-control-hidden' );
			}

			if ( 'search' === type ) {
				if ( 'default' === sBy ) {
					hidden = true;
				} else {
					hidden = false;
				}
			} else if ( 'date-range' === type ) {
				if ( 'date_query' === dateSource ) {
					hidden = true;
				} else {
					hidden = false;
				}
			} else if ( -1 !== types.indexOf( type ) && -1 !== sources.indexOf( source ) ) {
				hidden = true;
			}

			if ( hidden && ! $queryVar.hasClass( 'cx-control-hidden' ) ) {
				$queryVar
					.addClass( 'cx-control-hidden' )
					.find( 'input[name="_query_var"]' )
					.removeAttr( 'required' );
			}

			if ( ! hidden && $queryVar.hasClass( 'cx-control-hidden' ) ) {
				$queryVar
					.removeClass( 'cx-control-hidden' )
					.find( 'input[name="_query_var"]' )
					.attr( 'required', 'required' );
			}

		}

	};

	JetSmartFiltersAdmin.init();

}( jQuery ) );

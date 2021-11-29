/* Simple masonry */
!function(a){function b(b){var c=Math.min.apply(Math,b);return a.inArray(c,b)}function c(a){for(var b=[],c=0;c<a;c++)b.push(0);return b}function d(b){var c=a(b).outerWidth(),d=a(b).offsetParent().width();return{width:100*c/d,num:Math.floor(d/c)}}Array.max=function(a){return Math.max.apply(Math,a)},a.easing.__Slide=function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},a.simplemasonry=function(e,f){var g={animate:!1,easing:"__Slide",timeout:800},h=a.extend({},g,f),i=a(e),j=this;a.extend(j,{refresh:function(){var b=a("img",e),c=b.length,d=0;b.length>0&&i.addClass("sm-images-waiting").removeClass("sm-images-loaded"),b.on("load",function(a){d++,d==c&&(j.resize(),i.removeClass("sm-images-waiting").addClass("sm-images-loaded"))}),j.resize()},resize:function(){var e=i.children(":visible"),f=d(e[0]),g=f.width,j=f.num,k=c(j),l=function(c){var d=a(this).outerHeight(),f=b(k),i=Math.round(f*g*10)/10,j={left:i+"%",top:k[f]+"px"};a(this).css({position:"absolute"}).stop(),h.animate?a(this).animate(j,h.timeout,h.easing):a(this).css(j),k[f]+=d};e.css({overflow:"hidden",zoom:"1"}).each(l),i.css({position:"relative",height:Array.max(k)+"px"})}}),a(window).resize(j.resize),i.addClass("sm-loaded"),j.refresh()},a.fn.simplemasonry=function(b){if("string"!=typeof b)return this.each(function(){if(void 0==a(this).data("simplemasonry")){var c=new a.simplemasonry(this,b);a(this).data("simplemasonry",c)}});var c=a(this).data("simplemasonry"),d=Array.prototype.slice.call(arguments,1);return c[b]?c[b].apply(c,d):void 0}}(jQuery);

/* imagesLoaded PACKAGED v4.1.3 */
!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return-1==n.indexOf(t)&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{},n=i[e]=i[e]||{};return n[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return-1!=n&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=0,o=i[n];t=t||[];for(var r=this._onceEvents&&this._onceEvents[e];o;){var s=r&&r[o];s&&(this.off(e,o),delete r[o]),o.apply(this,t),n+=s?0:1,o=i[n]}return this}},t.allOff=t.removeAllListeners=function(){delete this._events,delete this._onceEvents},e}),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return t(e,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("ev-emitter")):e.imagesLoaded=t(e,e.EvEmitter)}("undefined"!=typeof window?window:this,function(e,t){function i(e,t){for(var i in t)e[i]=t[i];return e}function n(e){var t=[];if(Array.isArray(e))t=e;else if("number"==typeof e.length)for(var i=0;i<e.length;i++)t.push(e[i]);else t.push(e);return t}function o(e,t,r){return this instanceof o?("string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=n(e),this.options=i({},this.options),"function"==typeof t?r=t:i(this.options,t),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(function(){this.check()}.bind(this))):new o(e,t,r)}function r(e){this.img=e}function s(e,t){this.url=e,this.element=t,this.img=new Image}var h=e.jQuery,a=e.console;o.prototype=Object.create(t.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&d[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var d={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},o.prototype.addImage=function(e){var t=new r(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var i=new s(e,t);this.images.push(i)},o.prototype.check=function(){function e(e,i,n){setTimeout(function(){t.progress(e,i,n)})}var t=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(t){t.once("progress",e),t.check()}):void this.complete()},o.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},r.prototype=Object.create(t.prototype),r.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&void 0!==this.img.naturalWidth},r.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},r.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(h=t,h.fn.imagesLoaded=function(e,t){var i=new o(this,e,t);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});

/* XDUtils - localStorage */
"use strict";window.XdUtils=window.XdUtils||function(){function a(a,b){var c,d=b||{};for(c in a)a.hasOwnProperty(c)&&(d[c]=a[c]);return d}return{extend:a}}(),window.xdLocalStorage=window.xdLocalStorage||function(){function a(a){k[a.id]&&(k[a.id](a),delete k[a.id])}function b(b){var c;try{c=JSON.parse(b.data)}catch(a){}c&&c.namespace===h&&("iframe-ready"===c.id?(m=!0,i.initCallback()):a(c))}function c(a,b,c,d){j++,k[j]=d;var e={namespace:h,id:j,action:a,key:b,value:c};g.contentWindow.postMessage(JSON.stringify(e),"*")}function d(a){i=XdUtils.extend(a,i);var c=document.createElement("div");window.addEventListener?window.addEventListener("message",b,!1):window.attachEvent("onmessage",b),c.innerHTML='<iframe id="'+i.iframeId+'" src='+i.iframeUrl+' style="display: none;"></iframe>',document.body.appendChild(c),g=document.getElementById(i.iframeId)}function e(){return l?!!m||(console.log("You must wait for iframe ready message before using the api."),!1):(console.log("You must call xdLocalStorage.init() before using it."),!1)}function f(){return"complete"===document.readyState}var g,h="cross-domain-local-message",i={iframeId:"cross-domain-iframe",iframeUrl:void 0,initCallback:function(){}},j=-1,k={},l=!1,m=!0;return{init:function(a){if(!a.iframeUrl)throw"You must specify iframeUrl";if(l)return void console.log("xdLocalStorage was already initialized!");l=!0,f()?d(a):document.addEventListener?document.addEventListener("readystatechange",function(){f()&&d(a)}):document.attachEvent("readystatechange",function(){f()&&d(a)})},setItem:function(a,b,d){e()&&c("set",a,b,d)},getItem:function(a,b){e()&&c("get",a,null,b)},removeItem:function(a,b){e()&&c("remove",a,null,b)},key:function(a,b){e()&&c("key",a,null,b)},getSize:function(a){e()&&c("size",null,null,a)},getLength:function(a){e()&&c("length",null,null,a)},clear:function(a){e()&&c("clear",null,null,a)},wasInit:function(){return l}}}();

/* Codevz Admin Custom JS */
jQuery( function( $ ) {
  'use strict';

	// Cross-domain copy.
	xdLocalStorage.init(
		{
			iframeUrl: 'https://xtratheme.com/api/skcp',
			initCallback: function() {
				//console.log( 'Got iframe ready' );
			}
		}
	);

	var wind = $( window ),
		inView = function(e, t) {
		  return ( t ? t : 800 ) >= e.offset().top;
		};

	// Hide plugins notifications
	if ( $( '.wp-admin' ).length ) {
	  var mute_plugins = $( '.plugins' ).find( '[data-slug="slider-revolution"], [data-slug="wpbakery-visual-composer"], [data-slug="revslider"], [data-slug="js_composer"]' );
	  if ( mute_plugins.length ) {
		mute_plugins.each(function() {
		  $( this ).next( '.plugin-update-tr' ).next( '.plugin-update-tr' ).hide();
		});
	  }
	  $( 'tr#revslider-update' ).hide();
	  $( '#message.rs-update-notice-wrap, #vc_license-activation-notice' ).hide().find( 'a' ).trigger( 'click' );
	  $( '.update-nag' ).each(function() {
		var string = $( this ).html();
		if ( string && string.toLowerCase().indexOf("visual") >= 0 ) {
		  $( this ).hide();
		}
	  });
	}

	// Post formats switch.
	if ( $( '.post-formats-select' ).length ) {

	  var pf_radio  = $( '#post-formats-select input' ),
		  pf_xtra   = $( '.post-formats-select' ),
		  pf_func = function( value ) {

			value = ( value === 'standard' ) ? 0 : value;

			pf_xtra.parent().find( '> div:first' ).trigger( 'click' );
			pf_xtra.parent().find( '[data-id="' + value + '"]' ).trigger( 'click' );

			// Switch tab.
			$( '[data-section="codevz_page_meta_post_format_settings"]' ).trigger( 'click' );

			// Scroll to meta setting.
			$( 'html, body, .block-editor-editor-skeleton__content' ).animate({ scrollTop: $( '#codevz_page_meta' ).offset().top - 50 }, 'slow' );

		  };

	  // PF changes on xtra tabs.
	  pf_xtra.on( 'change', function() {

		var value = $( this ).val();

		pf_radio.filter('[value=' + value + ']').prop( 'checked', true ).trigger( 'change' );
		
		// New editor change value.
		var option = $( '.editor-post-format option[value=' + ( value == 0 ? 'standard' : value ) + ']' );
		option.prop( 'selected', 'selected' ).addClass( 'codevz_pf_done' ).parent().trigger( 'change' );

		setTimeout( function() {
		  option.removeClass( 'codevz_pf_done' );
		}, 250 );

	  });

	  // Old editor radio click.
	  pf_radio.on( 'click', function() {
		pf_func( $( this ).val() );
	  });

	  // New editor select changes.
	  $( 'body' ).on( 'change', '.editor-post-format select', function() {

		if ( ! $( this ).find( '.codevz_pf_done' ).length ) {

		  pf_func( $( this ).val() );

		}

	  });

	}


	// Custom sidebars
	if ( $( '.sidebars-column-2' ).length ) {

	  $( '.sidebars-column-2' ).append( '<div class="widgets-holder-wrap cz-custom-sidebar"><div class="sidebar-name"><h2>Add Custom Sidebar<span class="spinner"></span></h2></div><div class="cz_sidebar_cn"><input name="cz_sidebar_name" type="text" placeholder="Name ..."><input type="submit" name="cz_add_sidebar" class="button button-primary" value="Add"></div></div>' );
	
	  $( '[name="cz_add_sidebar"]' ).on( 'click', function(e) {
			var en = $( this ), spinner = en.closest( '.widgets-holder-wrap' ).find( '.spinner' );

			if ( ! en.hasClass( 'cz_doing' ) ) {
			  en.addClass( 'cz_doing' );
			  spinner.addClass( 'is-active' );

			  $.ajax({
				type: "GET",
				url: ajaxurl,
				dataType: 'html',
				data: 'action=codevz_custom_sidebars&add_sidebar=1&sidebar_name=' + $( '[name="cz_sidebar_name"]' ).val(),
				success: function( data ) {
				  if ( data === 'done' ) {
					location.reload();
				  } else {
					confirm( 'Something went wrong, Please try again.' );
					spinner.removeClass( 'is-active' );
					en.removeClass( 'cz_doing' );
				  }
				},
				error: function( e ) {
				  console.log( e );
				  spinner.removeClass( 'is-active' );
				  en.removeClass( 'cz_doing' );
				}
			  });
			}

			e.preventDefault();
	  });

	  $( '#widgets-right [id*="cz-custom-"]' ).each( function() {
			var en = $( this );

			if ( ! en.find( '.cz-remove-sidebar' ).length ) {
			  en.find( '.sidebar-name' ).append( '<a href="#" class="cz-remove-sidebar" title="Delete this sidebar"><div class="dashicons dashicons-no-alt"></div></a>' );
			}
	  });

	  $( '.cz-remove-sidebar' ).on( 'click', function(e) {
			var en = $( this ), spinner = en.closest( '.widgets-holder-wrap' ).find( '.spinner' );

			if ( ! en.hasClass( 'cz_doing' ) && confirm( 'Are you sure?' ) ) {
			  en.addClass( 'cz_doing' );
			  spinner.addClass( 'is-active' );

			  $.ajax({
				type: "GET",
				url: ajaxurl,
				dataType: 'html',
				data: 'action=codevz_custom_sidebars&remove_sidebar=1&sidebar_name=' + en.closest( '.widgets-sortables' ).attr( 'id' ),
				success: function( data ) {
				  if ( data === 'done' ) {
					location.reload();
				  } else {
					confirm( 'Something went wrong, Please try again.' );
					spinner.removeClass( 'is-active' );
					en.removeClass( 'cz_doing' );
				  }
				},
				error: function( e ) {
				  console.log( e );
				  spinner.removeClass( 'is-active' );
				  en.removeClass( 'cz_doing' );
				}
			  });
			}

			e.preventDefault();
	  });
	}

	// VC Templates
	if ( $( '.vc_templates-template-type-default_templates' ).length ) {
	  $( '.vc_templates-button, .vc_templatera_button, #vc_templates-more-layouts, #vc_templatera-more-layouts' ).on( 'click.cz_templates', function() {

		// Filters
		var filters = $( '.cz_vc_filters' ),
			dtt     = $( '[data-tab="default_templates"]' ),
			vupc    = $( '.vc_ui-panel-content-container' ),
			last_li = $( '[data-vc-ui-element-target="[data-tab=default_templates]"]' ).html( filters.data( 'tab-title' ) ).parent('li'),
			vutl    = $( '.vc_ui-template-list' ),
			time    = null;

		last_li.prependTo( last_li.parent() );
		last_li.find( 'button' ).trigger( 'click' );

		dtt.find( '.vc_col-md-12' ).addClass( 'vc_col-md-2' ).removeClass( 'vc_col-md-12' ).html( '' ).append( filters );
		dtt.find( '.vc_column' ).addClass( 'vc_col-md-10 cz_templates_items' ).removeClass( 'vc_col-md-12' );
		
		filters.removeClass( 'hidden' ).find( 'li' ).each(function() {
		  var dis = $( this ),
			  count = $( '.cz_templates_items .' + dis.data( 'filter' ) ).length;

		  if ( count ) {
			dis.append( '<span>' + count + '</span>' );
		  } else {
			dis.remove();
		  }
		  
		  dis.on( 'click', function() {
			var cls = dis.data( 'filter' );

			dis.addClass( 'cz_active' ).siblings().removeClass( 'cz_active' );
			$( '.cz_templates_items .vc_ui-template-list .vc_ui-template' ).addClass( 'cz_deactive' );
			$( '.cz_templates_items .vc_ui-template-list .' + cls ).removeClass( 'cz_deactive' );

			vupc.trigger( 'resize' );

			setTimeout( function() {
			  vupc.trigger( 'scroll' );
			}, 250 );

		  });
		});

		// Masonry templates
		$( '.vc_templates-list-default_templates' ).simplemasonry({animate: false});

		// Lazyload templates
		vupc.off( 'scroll' ).on( 'scroll', function(e) {
		  $( '.vc_templates-template-type-default_templates:not(.cz_lazyDone,.cz_deactive)' ).slice(0,10).each(function(i) {
			var dis = $( this );

			if ( ! dis.hasClass( 'cz_lazyDone' ) && inView( dis, e.currentTarget.scrollTop + e.currentTarget.clientHeight ) ) {
			  var cls    = dis.attr( 'class' ),
				  title  = cls.match( /\d+_jpg/g );

			  $( '.vc_ui-list-bar-item-trigger', dis ).html( '<img src="https://xtratheme.com/img/templates/' + title[0].replace( /_/g, '.' ) + '" />' + cls );
			  vupc.imagesLoaded(function() {
				  vupc.trigger( 'resize' );
			  });
			  dis.addClass( 'cz_lazyDone' );
			}
		  });
		});

		$( '.vc_templates-template-type-default_templates' ).on( 'click', '.vc_ui-list-bar-item-trigger', function() {

			var dis = $( this );

			dis.addClass( 'cz_template_loader_after' ).find( 'img' ).css( 'opacity', '0.3' );

			setTimeout(function() {
				dis.removeClass( 'cz_template_loader_after' ).find( 'img' ).css( 'opacity', '1' );
			}, 4000 );

		});

		// Search tempaltes
		$( '.vc_ui-search-box-input' ).on( 'keyup', 'input', function() {

			clearTimeout( time );

			time = setTimeout( function() {
				vupc.trigger( 'resize' ).trigger( 'scroll' );
			}, 250 );

		});

		vupc.scroll();
		$( this ).off( 'click.cz_templates' );
	  });

	  // Front-end templates button
	  $('#vc_inline-frame').on( 'load', function(){
		$('#vc_inline-frame').contents().find( '#vc_templates-more-layouts' ).on( 'click', function() {
		  $( '.vc_templates-button' ).trigger( 'click' );
		});
	  });
	}

	// Eye icon for vc preview
	if ( $( '.vc_navbar-frontend' ).length ) {
	  $( '.vc_navbar-frontend .vc_navbar-nav' ).append('<li class="vc_pull-right cz_vc_preview"><a href="javascript:;" class="vc_icon-btn vc_post-settings" title="Quick Preview"><i class="vc-composer-icon fa fa-eye"></i></a></li>');
	  $( '.cz_vc_preview' ).on( 'click', 'a', function(e) {

		$( 'i', this ).toggleClass( 'fa-eye fa-eye-slash' ).attr( 'disabled', 'disabled' );
		
		var i = $( '#vc_inline-frame' ).contents().find( 'body' );

		if ( $( 'i', this ).hasClass( 'fa-eye-slash' ) ) {
		  i.append('<style id="cz_vc_preview_css">.compose-mode .vc_row:hover{z-index: auto !important}.vc_controls, .cz_edit_popup_link, .vc_welcome, .vc_empty-element, div .vc_empty:hover, .compose-mode .vc_element.vc_hold-hover>.wpb_row>.vc_element:before, .compose-mode .vc_element.vc_hover>.wpb_row>.vc_element:before, .compose-mode .vc_element:hover>.wpb_row>.vc_element:before, .view-mode .vc_element.vc_hold-hover>.wpb_row>.vc_element:before, .view-mode .vc_element.vc_hover>.wpb_row>.vc_element:before, .view-mode .vc_element:hover>.wpb_row>.vc_element:before {display: none !important} .compose-mode .vc_vc_row>.vc_parallax, .compose-mode .vc_vc_row>[data-vc-full-width=true], .compose-mode .vc_vc_row_inner, .compose-mode .vc_vc_section>.vc_parallax, .compose-mode .vc_vc_section>[data-vc-full-width=true], .compose-mode .vc_vc_video {padding-top: 0;padding-bottom: 0}@media (max-width:767px) {.compose-mode .vc_hidden-xs,.view-mode .vc_hidden-xs{display:none!important}}@media (min-width:768px) and (max-width:991px) {.compose-mode .vc_hidden-sm,.view-mode .vc_hidden-sm{display:none!important;}}@media (min-width:992px) and (max-width:1199px) {.compose-mode .vc_hidden-md,.view-mode .vc_hidden-md{display:none!important}}@media (min-width:1200px) {.compose-mode .vc_hidden-lg,.view-mode .vc_hidden-lg{display:none!important}}.compose-mode .page_content [id^=cz_]:hover,.compose-mode *{outline:none !important}.compose-mode .page_content .cz_gap{background:none}.compose-mode .vc_element .vc_element-container, .view-mode .vc_element .vc_element-container,.compose-mode .vc_empty-shortcode-element, .compose-mode .vc_templatera, .compose-mode .vc_vc_column_text, .compose-mode .vc_vc_widget_sidebar{min-height:unset}</style>');
		} else {
		  $( '#cz_vc_preview_css', i ).remove();
		}

		if ( $( '.cz_btn_inline', i ).length ) {
		  $( '.cz_btn_inline', i ).closest( '.vc_cz_button' ).toggleClass( 'cz_btn_inline' );
		}

		e.preventDefault();
		return false;
	  });
	}

	// Slider Option
	$.fn.csf_slider = function() {
	  return this.each(function() {

		$( this ).html( '<div></div>' );

		var dis    = $( this ),
			input  = dis.prev('input'),
			slider = $( '> div', dis ),
			data   = dis.data( 'options' ),
			step   = parseInt( data.step ) || 1,
			min    = parseInt( data.min ) || 0,
			max    = parseInt( data.max ) || 100,
			unit, val;

		// Connect 4 fields to each other
		var nParent = input.closest( '.csf-field-codevz_sizes' );
		if ( nParent.length ) {
		  $( '.fa-link', nParent ).off().on( 'click', function() {
			  dis.find( 'input' ).trigger( 'focus' );
			  $( this ).toggleClass( 'cz_connect' );
		  });
		}


		slider.slider({
			range: 'min',
			value: parseInt( input.val() || 0 ),
			step: step,
			min: min,
			max: max,
			slide: function( e, o ) {
			  unit = input.val().replace(/[ 0-9]/g, '').replace('-', '');
			  input.val( parseInt( o.value || 0 ) + ( unit || data.unit ) ).trigger( 'change' );
			}
		});

			// If border is custom
			val = ( input.val().indexOf(' ') >= 0 ) ? 'addClass' : 'removeClass';
			dis.parent()[val]( 'cz_border_custom_val' );

		// Focus input run mousewheel and keydown
		if ( ! input.closest( '.cz_border_custom_val' ).length ) {

			input.on( 'focus', function() {

				// Turn ON mousewheel and keydown
				input.off( 'mousewheel DOMMouseScroll keydown' ).on( "mousewheel DOMMouseScroll keydown", function( e ) {

					var unit = input.val().replace(/[ 0-9]/g, '').replace('-', ''),
						val  = parseInt( input.val() || 0 );

					// Keyboard Up
					if ( e.which == 38 ) {
						input.val( ( val + step ) + ( unit || data.unit ) ).trigger( 'change' );
						slider.slider({ value: val + step });
					} else if ( e.which == 40 ) {
						input.val( ( val - step ) + ( unit || data.unit ) ).trigger( 'change' );
						slider.slider({ value: val - step });
					}

					// Return false space on 4 fileds.
					if ( e.keyCode == 32 && ( dis.closest('.csf-field-codevz_sizes').length || dis.closest('.csf-field-codevz_box_shadow').length ) ) { 
						return false;
					}

					// Mousewheel Up/Down.
					if ( e.type === 'mousewheel' || e.type === 'DOMMouseScroll' ) {

						var deltaY = e.deltaY || e.originalEvent.wheelDelta || -e.originalEvent.detail;

						if ( typeof deltaY != 'undefined' ) {

							if ( deltaY > 0 ) {
								input.val( ( val + step ) + ( unit || data.unit ) ).trigger( 'change' );
								slider.slider({ value: val + step });
							} else {
								input.val( ( val - step ) + ( unit || data.unit ) ).trigger( 'change' );
								slider.slider({ value: val - step });
							}

							return false;
						}
					}

				}); // End mousewheel keydown
			}); // End focus

		} // End check closest( '.cz_border_custom_val' ).length

		input.on( 'keyup change', function() {

			val = input.val();

			slider.slider({ value: parseInt( val || '0' ) });

			// Connect all fields together
			if ( $( '.fa-link', nParent ).hasClass('cz_connect') ) {
			  $( 'input', nParent ).val( val );
			  $( '.csf-slider > div', nParent ).slider({ value: parseInt( val ) });
			}

			// If border is custom
			dis.parent()[( val.indexOf(' ') >= 0 ? 'addClass' : 'removeClass' )]( 'cz_border_custom_val' );

		  }).on( 'keyup', function() {

			  slider.slider({ value: parseInt( input.val() || '0' ) });

		  }).on( 'focusout', function() {

			  // Turn OFF mousewheel and keydown
			  input.off( 'mousewheel DOMMouseScroll keydown' );

			  // Fix Unit
			  val = input.val();
			  if ( $.isNumeric( val ) ) {
				input.val( val + data.unit ).trigger( 'change' );
			  } else if ( val == '-' ) {
				input.val( null ).trigger( 'change' );
			  }

		  }); // End input.on()

	  }); // End return this
	}; // End csf_slider

	// Codevz Presets tab for VC
	$.fn.csf_cz_presets = function() {
	  return this.each(function() {
		
		var dis = $( this ),
			skp = dis.attr( 'class' ) + '_class',
			tab = dis.closest( '.vc_edit-form-tab' ).attr( 'id' ).split('-').pop(),
			nk, nv, k, v, 
			setCustomFields = function( k, v, par, nk ) {
			  // Set icon
			  if ( v.indexOf( 'fa-' ) >= 0 ) {
				par.find( '.csf-icon-remove' ).removeClass( 'hidden' );
				par.find( '.csf-icon-preview' ).removeClass( 'hidden' ).html( '<i class="' + v + '"></i>' );
			  }

			  // Set checkbox
			  if ( nk.attr( 'type' ) == 'checkbox' ) {
				nk.prop( 'checked', 1 ).val( 1 ).attr( 'checked', 'checked' ).trigger( 'change' );
			  }

			  // Set content
			  if ( k == 'content' ) {
				  par.find( '[name="wpb_tinymce_content"]' ).val( v ).trigger( 'change' );
				  par.find( '#wpb_tinymce_content_ifr' ).contents().find( 'body' ).html( v );
			  }

			  // Set image field
			  if ( k.indexOf( 'image' ) >= 0 && v.indexOf( ':' ) >= 0 ) {
				var iid = ( v.indexOf("?id=") >= 0 ) ? v.split('?id=')[1] : v;
				nk.parent().find( 'ul' ).html( '<li class="added"><div class="inner" style="width:80px;height:80px;overflow:hidden;text-align:center;"><img src="' + v + '" data-image-id="' + iid + '" class="vc_ce-image"></div><a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a></li>' );
				v = iid;
			  }
			},
			setPreset = function( s, par, gr ) {
			  $.each( s, function( k, v ) {

				// Set CSS Box
				if ( v.indexOf( 'vc_custom' ) >= 0 ) {
				  v = v.substring( v.lastIndexOf("{") + 1, v.lastIndexOf("}") );
				  v = v.split(';');
				  k = par.find( '[data-vc-shortcode-param-name="' + k + '"]' );

				  $.each( v, function(kk, vv) {
					vv = vv.split(': ');
					nk = $.trim( vv[0] ).replace( /-/g, '_' );
					nv = $.trim( vv[1] ).replace( / !important/g, '' );

					// Set border color
					if ( nk.indexOf( 'border' ) >= 0 ) {
					  nk = ( nk.indexOf( 'color' ) >= 0 ) ? 'border_color' : ( ( nk.indexOf( 'style' ) >= 0 ) ? 'border_style' : nk );
					}

					// Set others
					k.find( '[name="' + nk + '"]' ).val( nv ).trigger( 'change' );

					// Set Background image
					if ( nk == 'background_image' ) {
					  nv = nv.substring( nv.lastIndexOf("(") + 1, nv.lastIndexOf(")") );
					  var prm = ( nv.indexOf("?id=") >= 0 ) ? nv.split('?id=')[1] : v;
					  k.find( 'ul.vc_image' ).html( '<li class="added"><div class="inner" style="width:80px;height:80px;overflow:hidden;text-align:center;"><img src="' + nv + '" data-image-id="' + prm + '" class="vc_ce-image"></div><a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a></li>' );
					}
				  });

				// Set Box and Text shadow fields
				} else if ( k.indexOf( 'shadow' ) >= 0 ) {

				  nk = par.find( '[name="' + k + '"]' ).closest( '.codevz-boxshadow' );
				  v = v.split('|');

				  $.each( v, function(kk, vv) {
					vv = vv.split(':');
					var nnk = $.trim( vv[0] );
					nv = $.trim( vv[1] );

					if ( nnk.indexOf( 'style' ) >= 0 ) {
					  nk.find( 'select' ).val( nv ).trigger( 'change' );
					} else if ( nnk.indexOf( 'color' ) >= 0 ) {
					  nk.find( '.czbs-colorpicker' ).val( nv ).trigger( 'change' );
					} else {
					  nk.find( '[data-id="' + nnk + '"]' ).val( parseInt( nv ) ).trigger( 'change' );
					}
				  });

				// Others
				} else {

				  nk = par.find( '[name="' + k + '"]' );

				  // Set and fix custom fields
				  setCustomFields( k, v, par, nk );

				  // Groups
				  if ( v.indexOf( '%20' ) >= 0 ) {
					var group = JSON.parse( decodeURIComponent( v ) ),
						pare = par.find( '[data-vc-shortcode-param-name="' + k + '"]' );

					// Add new group items
					if ( pare.find( 'li.vc_param' ).length < 1 ) {
					  for (var i = group.length - 2; i >= 0; i--) {
						pare.find( '.vc_param_group-add_content' ).trigger( 'click' );
					  }
					}

					// Set each group li fields
					var group_li = pare.find( 'li.vc_param' ), nnk;
					$.each( group, function( kk, vv ) {
					  $.each( vv, function( kkk, vvv ) {
						nnk = $( group_li[ kk ] ).find( '[name="' + k + '_' + kkk + '"]' );
						if ( skp.indexOf( kkk ) < 0 || ! nnk.val() ) {
						  setCustomFields( kkk, vvv, $( group_li[ kk ] ), nnk );
						  vvv && nnk.val( vvv ).trigger( 'change' );
						}
					  });
					});
				  }

				  v && nk.val( v ).trigger( 'change' );
				}

			  });
			};

		// Get presets
		$( '[data-tab-index="' + tab + '"] button' ).on( 'click', function() {
		  if ( ! $( this ).hasClass( 'cz_presets_done' ) ) {
			$.ajax({
			  type: "GET",
			  url: ajaxurl,
			  dataType: 'html',
			  data: 'action=cz_presets&type=' + dis.data( 'presets' ),
			  success: function( data ) {

				// Presets & Masonry
				dis.html( data ).simplemasonry({animate: false});
				dis.imagesLoaded(function() {
				  wind.trigger( 'resize' );
				});

				// Disable links, inputs
				dis.on( 'click', 'a', function() {
				  $( this ).closest( '[data-shortcode]' ).trigger( 'click' );
				  return false;
				});
				$( 'input', dis ).attr( 'disabled', 'disabled' );

				// Click on presets
				dis.on( 'click', '[data-shortcode]', function(e) {

				  var disa  = $( this ),
					  s     = disa.data( 'shortcode' ),
					  par   = disa.closest( '.vc_panel-tabs' ), diz, std,
					  r     = confirm("Warning: If you confirm this, ALL FIELDS * will override and you will lose old settings of this element.");
				  
				  if ( ! r ) {return;}

				  // Fix multiple clicks
				  if ( disa.attr( 'disabled' ) != 'disabled' ) {
					disa.attr( 'disabled', 'disabled' ).addClass( 'cz_presets_loader_after' );

					// Reset all fields
					var opts = par.find( '[name], .czbs-select' ).not( '[name="id"]' );
					opts.each(function(i) {
					  var diz = $( this ),
						  nam = diz.attr( 'name' ),
						  std = diz.closest( '[data-param_settings]' ).data( 'param_settings' ) || {},
						  std = std.value || std.std || '';

					  if ( skp.indexOf( nam ) < 0 || ! diz.val() ) {

						if ( nam != 'alpha' ) {
						  diz.val( std ).prop( 'selectedIndex', 0 ).trigger( 'change' );

						  // Reset icon
						  if ( diz.parent().find( '.csf-icon-remove' ).length && ! std ) {
							diz.parent().find( '.csf-icon-remove' ).trigger( 'click' );
						  }

						  // unCheck checkbox
						  if ( diz.attr( 'type' ) == 'checkbox' && ! std ) {
							diz.val( false ).prop( 'checked', false ).trigger( 'change' );
						  }
						}

						// Start setting presets
						if ( ( i + 1 ) === opts.length ) {
						  $( 'ul.vc_image' ).html( '' ); // Clear background

						  setTimeout( function() {
							$.when( setPreset( s, par ) ).done(function() {
							  setTimeout(function() {
								disa.removeAttr( 'disabled' ).removeClass( 'cz_presets_loader_after' );
							  }, 2000 );
							  $( '[data-vc-ui-element="button-save"]' ).trigger( 'click' );
							});
						  }, 500 );
						}

					  } else {
						delete s[ nam ]
					  }

					});
				  }

				  e.preventDefault();
				  return false;
				});

			  },
			  error: function( e ) {
				console.log( e );
			  }
			});
		  }
		  $( this ).addClass( 'cz_presets_done' );
		});

	  });
	};

	// Image select for vc
	$.fn.csf_cz_image_select = function() {
	  return this.each(function() {
		var dis = $( this );

		$( 'input', dis ).on( 'change', function() {
		  if ( this.checked ) {
			var val = $( this ).val();
			$( 'input[type="hidden"]', dis ).val( val ).trigger( 'change' );
		  }
		});
	  });
	};

	// Customize WPB CSS_Editor
	$.fn.cz_vc_editor = function() {
	  return this.each(function() {
		var dis = $( this ),
			brr = $( '[name="border_radius"]', dis ),
			brr_val = brr.val(),
			brr_val = ( brr_val == 'null' || ! brr_val ) ? '' : brr_val;

		// Change border radius field to input with slider
		brr.after( '<div class="csf-field csf-field-slider"><input type="text" name="border_radius" class="vc_border-radius cz_slider" value="' + brr_val + '"><div class="csf-slider" data-options="{&quot;unit&quot;:&quot;px&quot;,&quot;step&quot;:1,&quot;min&quot;:0,&quot;max&quot;:120}"></div><div class="clear"></div></div>' );
		brr.remove();

		// Add background positions
		$( '[name="background_style"]', dis ).append( '<option value="cover;background-position: top">Cover, Top</option><option value="cover;background-position: bottom">Cover, Bottom</option><option value="no-repeat !important;background-position: left top">No repeat, Top left</option><option value="no-repeat !important;background-position: center top">No repeat, Top center</option><option value="no-repeat !important;background-position: right top">No repeat, Top right</option><option value="no-repeat !important;background-position: left bottom">No repeat, Bottom left</option><option value="no-repeat !important;background-position: center bottom">No repeat, Bottom center</option><option value="no-repeat !important;background-position: right bottom">No repeat, Bottom right</option>' );
	  });
	};

	// Set shortcode unique ID
	$.fn.cz_live_shortcode_id = function() {
	  return this.each(function() {
		var id = Math.floor(Math.random() * 99999) + 11111;
		$( this ).val( 'cz_' + id ).trigger( 'change' );
	  });
	};

	// WPB copy all SK
	if ( ! $( '.xtra_copy_all_sk' ).length && window.location.href.indexOf( 'xtratheme.' ) >= 0 ) {

		$( '.vc_ui-dropdown-trigger' ).before( '<div class="xtra_copy_all_sk" style="position:absolute;left:-155px;cursor:pointer;"><i class="fa fa-file"></i></div>' );

		$( 'body' ).on( 'click', '.xtra_copy_all_sk', function() {

			var sk = {},
				$this = $( this );

			$this.css( 'color', '#ffae00' );

			setTimeout( function() {
				$this.css( 'color', '#fff' );
			}, 1000 );

			$( '.vc_edit_form_elements [name]' ).not( '.vc_param_group-list [name]' ).each( function() {

				var $this = $( this ),
					name  = $this.attr( 'name' ),
					value = $this.val();

				if ( value && ( name.indexOf( 'sk_' ) >= 0 || name === 'svg_bg' ) ) {

					if ( name.indexOf( '_hover' ) < 0 ) {

						var hover = $( '.vc_edit_form_elements input[name="' + $this.attr( 'data-hover_id' ) + '"]' ).val() || '',
							rtl   = '';

						// Get RTL mode.
						if ( value.indexOf( 'RTL' ) >= 0 ) {

							rtl = value.split( 'RTL' )[1].replace( 'RTL', '' );
							value = value.split( 'RTL' )[0];

						}

						var svg = value.split( ';' );

						if ( value.indexOf( '_class' ) >= 0 ) {

							value = '';

							$.each( svg, function( k, v ) {

								if ( v.indexOf( '_' ) != 0 ) {
									value += v + ';';
								}

							});

							value = value.replace( ';;', ';' );

						}

						if ( name === 'sk_css' && $this.closest( '[data-vc-shortcode="cz_image"]' ).length ) {
							name = 'sk_image_in';
						}

						sk[ name ] = {

							'normal': value.replace( /CDVZ/g, '' ) || '0',
							'hover': hover.replace( /CDVZ/g, '' ) || '0',
							'rtl': rtl || '0'

						};

					}

				} else if ( value ) {

					if ( $this.attr( 'type' ) === 'checkbox' ) {

						var value = $this.is( ':checked' );

						if ( value ) {

							value = 'yes';

							if ( name === 'hide_on_d' ) {

								sk[ 'hide_desktop' ] = value;

							} else if ( name === 'hide_on_t' ) {

								sk[ 'hide_tablet' ] = value;

							} else if ( name === 'hide_on_m' ) {

								sk[ 'hide_mobile' ] = value;

							} else {

								sk[ name ] = value;

							}

						}

					} else if ( $this.hasClass( 'cz_slider_field' ) && name.indexOf( 'parallax' ) < 0 && value != '0' ) {

						var newValue = value.match( /\d+.\d+|\d+|\w+|\%/g );

						if ( newValue ) {

							if ( ! newValue[1] ) {

								sk[ name ] = value;

							} else if ( newValue[0] ) {

								if ( $this.closest( '[data-vc-shortcode="cz_gap"]' ).length ) {
									if ( newValue[0] >= 20 ) {
										newValue[0] = newValue[0] - 20;
									} else {
										newValue[0] = 0;
									}
								}

								sk[ name ] = {
									'size': newValue[0],
									'unit': newValue[1] || ''
								};

							}

						}

					} else {

						if ( value.indexOf( 'url:' ) >= 0 ) {

							var url = value.replace( 'url:', '' ).split( '|' );

							if ( url[ 0 ] ) {

								url = decodeURIComponent( url[ 0 ] );

								sk[ name ] = {

									'url': url.replace( 'xtratheme.com/', 'xtratheme.com/elementor/' ),
									'is_external': '',
									'nofollow': '',
									'custom_attributes': ''

								};

							}

						} else if ( name === 'parallax_h' && value != '0' ) {

							sk[ 'parallax' ] = value;

						} else if ( name === 'parallax' && value != '0' ) {

							sk[ 'parallax_speed' ] = value;

						} else if ( name === 'mparallax' && value != '0' ) {

							sk[ 'mouse_speed' ] = value;

						} else if ( name === 'class' ) {

							sk[ '_css_classes' ] = value;

						} else if ( $this.hasClass( 'param_group_field' ) && value.indexOf( '%' ) >= 0 ) {

							var items = JSON.parse( decodeURIComponent( value ) );

							$.each( items, function( k, v ) {

								if ( items[ k ][ 'icon' ] ) {
									value = items[ k ][ 'icon' ];
								} else if ( items[ k ][ 'i' ] ) {
									value = items[ k ][ 'i' ];
								} else {
									value = false;
								}
	 
								if ( value ) {
									var library = 'fa-solid';

									if ( value.indexOf( 'czico' ) >= 0 ) {
										library = 'xtra-custom-icons';
									} else if ( value.indexOf( 'far ' ) >= 0 ) {
										library = 'fa-regular';
									} else if ( value.indexOf( 'fab ' ) >= 0 ) {
										library = 'fa-brands';
									} else if ( value.indexOf( 'twitter' ) >= 0 || value.indexOf( 'facebook' ) >= 0 || value.indexOf( 'apple' ) >= 0 || value.indexOf( 'android' ) >= 0 || value.indexOf( 'pinterest' ) >= 0 || value.indexOf( 'youtube' ) >= 0 || value.indexOf( 'skype' ) >= 0 || value.indexOf( 'dribbble' ) >= 0 || value.indexOf( '500px' ) >= 0 || value.indexOf( 'flickr' ) >= 0 || value.indexOf( 'advisor' ) >= 0 || value.indexOf( 'behance' ) >= 0 || value.indexOf( 'telegram' ) >= 0 || value.indexOf( 'whatsapp' ) >= 0 || value.indexOf( 'reddit' ) >= 0 || value.indexOf( 'instagram' ) >= 0 || value.indexOf( 'wordpress' ) >= 0 || value.indexOf( 'clubhouse' ) >= 0 || value.indexOf( 'linkedin' ) >= 0 || value.indexOf( 'facebook' ) >= 0 ) {
										library = 'fa-brands';
									}

									if ( items[ k ][ 'icon' ] ) {
										items[ k ][ 'icon' ] = { value: value, library: library };
									} else if ( items[ k ][ 'i' ] ) {
										items[ k ][ 'i' ] = { value: value, library: library };
									}
								}

							});

							sk[ name ] = items;

						} else if ( $this.hasClass( 'cz_icon_field' ) ) {

							var library = 'fa-solid';

							if ( value.indexOf( 'czico' ) >= 0 ) {
								library = 'xtra-custom-icons';
							} else if ( value.indexOf( 'far ' ) >= 0 ) {
								library = 'fa-regular';
							} else if ( value.indexOf( 'fab ' ) >= 0 ) {
								library = 'fa-brands';
							} else if ( value.indexOf( 'twitter' ) >= 0 || value.indexOf( 'facebook' ) >= 0 || value.indexOf( 'apple' ) >= 0 || value.indexOf( 'android' ) >= 0 || value.indexOf( 'pinterest' ) >= 0 || value.indexOf( 'youtube' ) >= 0 || value.indexOf( 'skype' ) >= 0 || value.indexOf( 'dribbble' ) >= 0 || value.indexOf( '500px' ) >= 0 || value.indexOf( 'flickr' ) >= 0 || value.indexOf( 'advisor' ) >= 0 || value.indexOf( 'behance' ) >= 0 || value.indexOf( 'telegram' ) >= 0 || value.indexOf( 'whatsapp' ) >= 0 || value.indexOf( 'reddit' ) >= 0 || value.indexOf( 'instagram' ) >= 0 || value.indexOf( 'wordpress' ) >= 0 || value.indexOf( 'clubhouse' ) >= 0 || value.indexOf( 'linkedin' ) >= 0 || value.indexOf( 'facebook' ) >= 0 ) {
								library = 'fa-brands';
							}

							sk[ name ] = { value: value, library: library };

						} else if ( name.indexOf( 'parallax' ) < 0 && ! $this.hasClass( 'csf-icon-value' ) && ! $this.hasClass( 'cz_slider' ) && name !== 'cursor' && name !== 'id' && name !== 'wpb_tinymce_content' && name !== 'items' && name !== 'link' && name !== 'link_type' && name !== 'images' && name !== 'image' && name !== 'size' && name !== 'anim_delay' && name !== 'css_animation' ) {

							sk[ name ] = value;

						}

					}

				}

			});

			xdLocalStorage.setItem( 'skAll', JSON.stringify( sk ), function( data ) {
				console.log( sk, data );
			});

		});

		if ( typeof elementor != 'undefined' && elementor.hooks ) {

			// Search.
			//$( '#tmpl-elementor-panel-element-search' ).append( '<a>title</a><a>button</a><a>gap</a><a>list</a><a>image</a>' );

			$( 'body' ).on( 'click', '#elementor-panel-elements-search-area a', function() {

				$( '#elementor-panel-elements-search-input' ).val( $( this ).text() ).trigger( 'input' );

			}).append( '<style>#elementor-panel-elements-search-area a{display:inline-block;cursor:pointer;padding:5px 10px;background:#f7f7f7;margin:10px 2px 0 0}</style>' );

			// Paste.
			elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {

				$( document ).off( 'xtra_elementor' ).on( 'xtra_elementor', function( event, data ) {

					if ( data.value ) {

						data = JSON.parse( data.value );

						console.log( data );

						$.each( data, function( key, value ) {

							if ( key.indexOf( 'sk_' ) >= 0 ) {

								value = value || { normal: '0', hover: '0', rtl: '0' };

							}

							model.setSetting( key, value );

						});

						// Live preivew fix.
						setTimeout( function() {

							$( '.elementor-tab-control-advanced' ).trigger( 'click' );

							setTimeout( function() {
								$( '[data-setting="top"]' ).trigger( 'input' );
							}, 250 );

							setTimeout( function() {
								$( '.elementor-tab-control-content' ).trigger( 'click' );
							}, 500 );

							//$e.routes.refreshContainer( 'panel' );

						}, 500 );

					}

				});

			});

			elementor.hooks.addFilter( 'elements/widget/contextMenuGroups', function( groups, element ) {

				groups.push(
					{
						name: 'xtra',
						actions: [
							{
								name: 'xtra_sk_paste',
								title: 'Paste from WPBakery',
								callback: function() {

									xdLocalStorage.getItem( 'skAll', function( data ) {

										$( document ).trigger( 'xtra_elementor', [ data ] );

									});

								}
							}
						]
					}
				);

				return groups;

			} );

		}

	}

	if ( typeof elementor != 'undefined' && elementor.hooks ) {

		elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {

		  // Fix white text in editor.
		  setTimeout( function() {

				$( '.mce-edit-area > iframe' ).contents().find( 'body' ).css({
					'background': 'rgba(167, 167, 167, 0.25)'
				});

		  }, 1000 );

		});

	}

	if ( $( '.wp-customizer' ).length ) {

		// Mobile header customize change iframe
		$( '#accordion-section-codevz_theme_options-mobile_header, #accordion-section-codevz_theme_options-mobile_fixed_navigation' ).on( 'click', function() {
		  $( '[data-device="mobile"]' ).trigger( 'click' );
		  $( '.customize-section-back' ).on( 'click.cz', function() {
			$( '[data-device="desktop"]' ).trigger( 'click' );
			$( this ).off( 'click.cz' );
		  });
		});

	}

	// Style kit switch with responsive device mode.
	$( document.body ).on( 'click.cz', '.devices-wrapper button, #e-responsive-bar-switcher input, #vc_screen-size-control ul a', function() {

		if ( $( '[aria-describedby="cz_modal_kit"]' ).css( 'display' ) == 'none' ) {
			return;
		}

		var $this = $( this ),
			device = $this.data( 'device-mode' ) || $this.data( 'device' ) || $this.val(),
			modal = $( '[aria-describedby="cz_modal_kit"]' );

		if ( ! device ) {

			if ( $this.hasClass( 'vc-c-icon-layout_landscape-tablets' ) || $this.hasClass( 'vc-c-icon-layout_portrait-tablets' ) ) {
				device = 'tablet';
			} else if ( $this.hasClass( 'vc-c-icon-layout_landscape-smartphones' ) || $this.hasClass( 'vc-c-icon-layout_portrait-smartphones' ) ) {
				device = 'mobile';
			}

		}

		if ( device === 'tablet' ) {
			modal.find( '.fa-tablet-alt' ).trigger( 'click' );
		} else if ( device === 'mobile' ) {
			modal.find( '.fa-mobile-alt' ).trigger( 'click' );
		} else {
			modal.find( '.fa-desktop' ).trigger( 'click' );
		}

	});

	var kit_modal = $( '#cz_modal_kit' ), rgba_reset, reopen_modal;

	// Funcs
	$.fn.codevzSerializeObject=function(){var e={};return $.each(this.serializeArray(),function(a,r){var n=r.name.match(/(.*?)\[(.*?)\]/);if(null!==n){var u=new String(n[1]),i=new String(n[2]);e[u]||(e[u]={}),e[u][i]?$.isArray(e[u][i])?e[u][i].push(r.value):(e[u][i]={},e[u][i].push(r.value)):e[u][i]=r.value}else{var l=new String(r.name);e[l]?$.isArray(e[l])?e[l].push(r.value):(e[l]={},e[l].push(r.value)):e[l]=r.value}}),e};

	function cssToObj(css) {
	  if ( !css ) {return;}

	  css = css.replace( /CDVZ/g, '' );

	  var i, ii, p, v, ob = {}, obj = {},
		rtl    = css.match( "RTL(.*)RTL" ),
		css    = css.indexOf( 'RTL' ) >= 0 ? css.substring( 0, css.indexOf( 'RTL' ) ) : css,
		s      = css.split(/:(?![^(]*\))(?![^"']*["'](?:[^"']*["'][^"']*["'])*[^"']*$)|;/g),
		bg_index = [ 'orientation', 'color', 'color2', 'color3' ],
		orientations = [ 'top', 'right', 'bottom', 'left' ];

	  // Prepare inners
	  obj['custom'] = '';
	  obj['margin'] = {};
	  obj['padding'] = {};
	  obj['background'] = {};
	  obj['box-shadow'] = {};
	  obj['text-shadow'] = {};
	  obj['border-width'] = {};
	  if ( rtl ) {
		obj['rtl'] = rtl[1];
	  }
	  
	  for ( i = 0; i < s.length; i += 2 ) {
		p = s[i], v = s[i+1], ob = {};

		if ( ! p || ! v ) {continue;}

		if ( p === 'background-color' ) {
		  obj['background']['color'] = v;
		} else if ( p === 'background-repeat' ) {
		  obj['background']['repeat'] = v;
		} else if ( p === 'background-position' ) {
		  obj['background']['position'] = v;
		} else if ( p === 'background-attachment' ) {
		  obj['background']['attachment'] = v;
		} else if ( p === 'background-size' ) {
		  obj['background']['size'] = v;
		} else if ( p === 'background-image' ) {
		  if ( v.indexOf( 'url(' ) >= 0 && v.indexOf( ')' ) >= 0 ) {
			obj['background']['image'] = v.split( 'url(' )[1].split( ')' )[0];
			if ( v.indexOf( ',linear' ) >= 0 ) {
			  obj['background']['layer'] = '1';
			}
		  }
		  if ( v.indexOf( 'gradient' ) >= 0 ) {
			var lg = v.match( /linear-gradient\((.*)\)/ );
			v = lg[1].match( /rgba\(.*?\)|#\w+|\w+\s\w+|\w+|-\w+/g );
			if ( v[1] == v[2] ) {
			  v[2] = '';
			}
			$.each( v, function( bp, bv ) {
			  obj['background'][ bg_index[bp] ] = ( ( bv == 'url' ) ? '' : bv );
			});
		  }
		} else if ( p === 'padding' || p === 'margin' || p === 'border-width' ) {
		  ob = v.match(/\S+/g);
		  if ( ob.length == 4 ) {
			obj[p]['top'] = ob[0];
			obj[p]['right'] = ob[1];
			obj[p]['bottom'] = ob[2];
			obj[p]['left'] = ob[3];
		  } else if ( ob.length == 3 ) {
			obj[p]['top'] = ob[0];
			obj[p]['right'] = ob[1];
			obj[p]['bottom'] = ob[2];
			obj[p]['left'] = ob[1];
		  } else if ( ob.length == 2 ) {
			obj[p]['top'] = ob[0];
			obj[p]['right'] = ob[1];
			obj[p]['bottom'] = ob[0];
			obj[p]['left'] = ob[1];
		  } else if ( ob.length == 1 ) {
			obj[p]['top'] = ob[0];
			obj[p]['right'] = ob[0];
			obj[p]['bottom'] = ob[0];
			obj[p]['left'] = ob[0];
		  }

		} else if ( p === 'padding-top' ) {
		  obj['padding']['top'] = v;

		} else if ( p === 'padding-right' ) {
		  obj['padding']['right'] = v;

		} else if ( p === 'padding-bottom' ) {
		  obj['padding']['bottom'] = v;

		} else if ( p === 'padding-left' ) {
		  obj['padding']['left'] = v;

		} else if ( p === 'margin-top' ) {
		  obj['margin']['top'] = v;

		} else if ( p === 'margin-right' ) {
		  obj['margin']['right'] = v;

		} else if ( p === 'margin-bottom' ) {
		  obj['margin']['bottom'] = v;

		} else if ( p === 'margin-left' ) {
		  obj['margin']['left'] = v;

		} else if ( p === 'border-top-width' ) {
		  obj['border-width']['top'] = v;
		  obj['border-top-width'] = v;

		} else if ( p === 'border-right-width' ) {
		  obj['border-width']['right'] = v;
		  obj['border-right-width'] = v;

		} else if ( p === 'border-bottom-width' ) {
		  obj['border-width']['bottom'] = v;
		  obj['border-bottom-width'] = v;

		} else if ( p === 'border-left-width' ) {
		  obj['border-width']['left'] = v;
		  obj['border-left-width'] = v;

		} else if ( p.indexOf( 'shadow' ) >= 0 ) {

		  v = v.match( /rgba\(.*?\)|#\w+|-\w+|\w+/g );
		  obj[p]['x'] = v[0];
		  obj[p]['y'] = v[1];
		  obj[p]['blur'] = v[2];

		  if ( typeof v[3] === 'string' && v[3].indexOf( 'px' ) >= 0 ) {

			obj[p]['color'] = v[4];

			if ( p.indexOf( 'box-shadow' ) >= 0 ) {
			  obj[p]['spread'] = v[3];
			  if ( typeof v[5] === 'string' ) {
				obj[p]['mode'] = v[5];
			  }
			}

		  } else {
			obj[p]['color'] = v[3];
		  }

		} else if ( p == 'transform' && v.indexOf( 'rotate' ) >= 0 ) {
		  v = v.match( /\w+/g );
		  obj[p] = v[ v.length - 1 ];

		} else if ( p == 'filter' && ( v.indexOf( 'blur' ) >= 0 || v.indexOf( 'grayscale' ) ) >= 0 ) {
		  p = v.indexOf( 'blur' ) >= 0 ? 'blur' : 'grayscale';
		  v = v.match( /(\d+)%|(\d+)px/g );
		  obj[p] = v[0];

		} else if ( p == 'content' || p == 'font-family' ) {
		  obj[p] = v.replace( /"|'/g, '' );

		} else if ( p.indexOf( 'border' ) >= 0 && p.indexOf( 'style' ) >= 0 ) {
		  obj['border-style'] = v;

		} else if ( kit_modal.find( '[name="' + p + '"]' ).length ) {
		  obj[p] = v;

		} else {
			obj['custom'] += p + ':' + v + ';';
		}
	  }

	  return obj;
	}

	function objToCSS(obj) {
	  if ( ! obj ) {return;}

	  var css = '', bg_layer;
	  $.each( obj, function( property, val ) {
		if ( val ) {
		  if ( typeof val == 'object' ) {
			if ( property == 'background' ) {
			  $.each( val, function( bp, bv ) {
				if ( bp == 'position' || bp == 'size' || bp == 'repeat' || bp == 'attachment' ) {
				  css += 'background-' + bp + ':' + bv + ';';
				} else if ( bp == 'layer' && bv ) {
				  bg_layer = bv;
				}
			  });

			  val = $.extend({image: '',color: '',color2: '',color3: '',orientation: ''}, val);

			  var image = val['image'] ? 'url(' + val['image'] + ')' : '',
				c1 = val['color'],
				c2 = val['color2'],
				c3 = val['color3'],
				is_color = ( c1 || c2 || c3 ),
				empty_cl = ( ! c1.length + ! c2.length + ! c3.length ),
				linear = val['orientation'] ? val['orientation'] : '0deg',
				linear = linear + ( c1 ? ',' + c1 + ( ( ! c2 && ! c3 ) ? ',' + c1 : '' ) : '' ),
				linear = linear + ( c2 ? ',' + c2 + ( ( ! c1 && ! c3 ) ? ',' + c2 : '' ) : '' ),
				linear = linear + ( c3 ? ',' + c3 + ( ( ! c1 && ! c2 ) ? ',' + c3 : '' ) : '' ),
				linear_gr = 'linear-gradient(' + linear + ')';

			  if ( empty_cl <= 1 ) {
				css += 'background-color:transparent;';
			  }

			  if ( is_color && image ) {
				css += 'background-image:' + ( bg_layer ? image + ',' + linear_gr : linear_gr + ',' + image ) + ';';
			  } else if ( image ) {
				css += 'background-image:' + image + ';';
			  } else if ( empty_cl == 2 ) {
				css += 'background-color:' + c1 + c2 + c3 + ';';
			  } else if ( linear && is_color ) {
				css += 'background-image:' + linear_gr + ';';
			  }

			} else if ( property == 'margin' || property == 'padding' || property == 'border-width' ) {

			  if ( Object.keys( val ).length == 4 ) {
				if ( val['top'] === val['right'] && val['bottom'] === val['left'] && val['top'] === val['left'] ) {
				  css += property + ':' + val['top'] + ';';
				} else if ( val['top'] !== val['bottom'] && val['right'] === val['left'] ) {
				  css += property + ':' + val['top'] + ' ' + val['right'] + ' ' + val['bottom'] + ';';
				} else if ( val['top'] === val['bottom'] && val['right'] === val['left'] ) {
				  css += property + ':' + val['top'] + ' ' + val['right'] + ';';
				} else {
				  css += property + ':' + val['top'] + ' ' + val['right'] + ' ' + val['bottom'] + ' ' + val['left'] + ';';
				}
			  } else {
				$.each( val, function( obp, obv ) {
				  var width = ( property == 'border-width' ) ? '-width' : '',
					  new_pro = ( property == 'border-width' ) ? 'border' : property;
				  css += new_pro + '-' + obp + width + ':' + obv + ';';
				});
			  }

			} else if ( property.indexOf( 'shadow' ) >= 0 ) {

			  if ( typeof val === 'object' && ( typeof val['x'] === 'string' || typeof val['y'] === 'string' ) ) {
				val = jQuery.extend({}, {
				  x:      '0px',
				  y:      '0px',
				  blur:   '0px',
				  spread: '0px',
				  color:  '#000',
				  mode:   'outset',
				}, val );

				css += property + ':';
				$.each( val, function( k, v ) {
				  if ( ( k === 'spread' || k === 'mode' ) && property.indexOf( 'text-shadow' ) >= 0 ) {
					v = null;
				  }

				  if ( v ) {
					if ( k !== 'mode' ) {
					  css += ( k === 'x' ) ? v : ' ' + v;
					} else {
					   css += ( v === 'inset' ) ? ' ' + v : '';
					}
				  }
				});
				css += ';';
			  }

			}

		  } else if ( property == 'rtl' ) {
			css += 'RTL' + val + 'RTL';

		  } else if ( property == 'content' ) {
			css += 'content:"' + val.replace( /"|:|;/g, '' ) + '";';

		  } else if ( property == 'font-family' ) {

		  	var font = val.split( '=' );

				css += "font-family:'" + font[ 0 ] + "';";

		  } else if ( property == 'transform' && val.endsWith( 'deg' ) ) {
			css += 'transform:rotate(' + val + ');';

		  } else if ( property == 'blur' ) {
			css += 'filter:blur(' + val + ');';

		  } else if ( property == 'grayscale' ) {
			css += 'filter:grayscale(' + val + ');';

		  } else if ( property == 'custom' ) {
			css += val;

		  }  else if ( property != 'live_id' && property != 'selector' ) {
			css += property + ':' + val + ';';

		  }
		}
	  });

	  return css;
	}
	
	// Fix font - icon select in customizer
	$( '.wp-customizer' ).off( 'click.cz' ).on( 'click.cz', '.csf-font-add, .csf-icon-add', function(e) {
	  if ( kit_modal.hasClass( 'ui-dialog-content' ) ) {
		var issk = $( this ).closest( '#cz_modal_kit' ).length;
		kit_modal.dialog( 'close' );
		setTimeout( function() {
		  $( '#csf-modal-font, #csf-modal-icon' ).off( 'click.cz2' ).on( 'click.cz2', '.csf-font-close, .csf-icon-close, a', function(e) {
			if ( e.target.className.indexOf( 'fa' ) < 0 && kit_modal.hasClass( 'ui-dialog-content' ) && issk ) {
			  kit_modal.dialog( 'open' );
			}
		  });
		}, 500 );
	  }
	});

	// Reload scripts
	$(document).on('csf-reload-script', function( event, $this, api ) {
	  $this.find('.vc_css-editor').not('.csf-no-script').cz_vc_editor();
	  $this.find('.csf-slider').not('.csf-no-script').csf_slider();
	  $this.find('.cz_presets').not('.csf-no-script').csf_cz_presets();
	  $this.find('[data-param_type="cz_image_select"]').not('.csf-no-script').csf_cz_image_select();
	  $this.find('.cz_sc_id_field').not('.csf-no-script').cz_live_shortcode_id();

	  // Block reveal dependency
	  $this.find( '.vc_param-animation-style, .animation' ).on( 'change', function() {
		var val = $( this ).val();

		if ( val.indexOf( 'cz_brfx_' ) >= 0 || val.indexOf( 'cz_grid_brfx_' ) >= 0 ) {
		  $this.find( '[data-vc-shortcode-param-name="sk_brfx"]' ).removeClass( 'vc_dependent-hidden hidden' );
		} else {
		  $this.find( '[data-vc-shortcode-param-name="sk_brfx"]' ).addClass( 'vc_dependent-hidden hidden' );
		}
	  }).trigger( 'change' );

	  // Scroll menu styling btn to menu sk
	  $( '.cz_menu_sk' ).each(function() {
		$( this ).on( 'click', function(e) {
		  if ( $( '.wp-full-overlay-sidebar-content' ).length ) {
			$( '.wp-full-overlay-sidebar-content' ).animate({ scrollTop: $( this.hash ).position().top - 50 });
		  }
		  e.preventDefault();
		});
	  });

	  // Image dropdown param
	  var iSelect = $( '.codevz_image_select' );
	  iSelect.find( '> div' ).off().on( 'click', function(e) {
		var en = $( this ), 
			id = en.attr( 'data-id' ),
			ul = en.next( 'ul' );

		iSelect.find( 'ul' ).not( ul ).hide();
		en.find( 'i' ).toggleClass( 'fa-angle-down fa-angle-up' );

		ul.find( 'li[data-id="' + id + '"]' ).addClass( 'codevz_on' ).siblings().removeClass( 'codevz_on' );

		ul.toggle().find( 'li' ).off().on( 'click', function() {
		  var li = $( this ),
			  new_id = li.attr( 'data-id' );

		  li.addClass( 'codevz_on' ).siblings().removeClass( 'codevz_on' );
		  en.parent().find( 'input' ).val( new_id ).trigger( 'change' );
		  en.attr( 'data-id', new_id ).find( '> img' ).attr( 'src', li.find( 'img' ).attr( 'src' ) );
		  en.find( 'span span' ).html( li.attr( 'data-title' ) );
		  en.next( 'ul' ).hide();
		  en.find( 'i' ).attr( 'class', 'fa fa-angle-down' );
		});
	  });
	  $( 'body' ).on( 'click', function(e) {
		  if ( ! $( e.target ).is( '.codevz_image_select *' ) ) {
			  iSelect.find( 'ul' ).hide();
			  iSelect.find( '> div i' ).attr( 'class', 'fa fa-angle-down' );
		  }
	  });

	  // Advanced Background
	  $( '.cz_advance_bg' ).off().on( 'click', function(e) {
		$( this ).hide();
		kit_modal.find( '.cz_bg_advanced' ).show();
		e.preventDefault();
	  });

	  // Close StyleKit on VC modal changes
	  $( '.vc_ui-close-button, .vc_edit-form-tab-control, .vc_ui-panel-footer span, .vc_control-btn-layout, .vc_control-btn-edit, .custom_columns, .vc-c-icon-mode_edit, .vc_ui-minimize-button' ).off().on( 'click', function() {
		if ( $( this ).data( 'vc-ui-element' ) == 'button-save' && $( '.compose-mode' ).length ) {
		  return;
		}
		if ( kit_modal.hasClass( 'ui-dialog-content' ) ) {
		  kit_modal.dialog( 'close' );
		}
	  });

	  // In the vc front-end
	  $( '#vc_inline-frame' ).contents().find( ".vc_ui-close-button, .vc_ui-panel-footer span, .vc_control-btn-layout, .vc_control-btn-edit, .vc_post-settings, .custom_columns, .vc-c-icon-mode_edit, .vc_ui-minimize-button" ).off().on( 'click', function() {
		if ( kit_modal.hasClass( 'ui-dialog-content' ) ) {
		  kit_modal.dialog( 'close' );
		}
	  });

	  // Advanced Show all _css_
	  $( '.cz_advanced_tab span' ).off().on( 'click', function() {
		if ( ! $( this ).hasClass( 'cz_active' ) ) {
		  $( this ).addClass( 'cz_active' ).siblings().removeClass( 'cz_active' ).closest( 'ul' ).toggleClass( 'cz_all_css_on' );
		}
	  });

	  // StyleKit value detection
	  var sk_value_detection = function( sk ) {

		if ( $( '.wp-customizer' ).length ) {
		  return;
		}

		var sk = sk ? sk : $( '.vc_ui-panel-window-inner .cz_sk_btn' );

		sk.each( function() {
		  var en = $( this ),
			  val = en.prev( '[name]' ).val(),
			  out = '';

		  en.parent().find( '> span' ).remove();

		  if ( val && ! en.next( 'span' ).length ) {
			if ( val.indexOf( 'background' ) >= 0 ) {
			  out += 'background';
			}
			if ( val.indexOf( 'font-size' ) >= 0 ) {
			  out += out ? ', ' : '';
			  out += 'font-size';
			}
			if ( val.indexOf( 'color' ) == 0 || val.indexOf( ';color' ) > 0 ) {
			  out += out ? ', ' : '';
			  out += 'color';
			}
			if ( val.indexOf( 'border-color' ) >= 0 ) {
			  out += out ? ', ' : '';
			  out += 'border';
			}

			if ( out && ! api ) {
			  en.parent().find( '.sk_btn_preview_image' ).after( '<span class="cz_vdt">[ ' + out + ' ]</span>' );
			}
		  }
		});
	  };
	  sk_value_detection();

	  // Preview image click
	  $( '.sk_btn_preview_image' ).off().on( 'click', function(e) {

		$( this ).parent().find( '.cz_sk_btn' ).trigger( 'click' );

		e.preventDefault();
	  });

	  // StyleKit Button and data
	  $this.find( '.cz_sk_btn' ).each( function() {

		var en = $( this ),
			input = en.parent().find( 'input' ),
			name  = input.attr( 'name' ),
			id    = name.match( /\w+/g ),
			id    = id[ id.length - 1 ],
			hover = input.attr( 'data-hover_id' ),
			hover = name.replace( id, hover ),
			val = input.val() || $( '[name="' + hover + '"]' ).val() || ( api && api.getControlValue()[ 'normal' ] ) || '',
			preview_img = en.parent().find( '.sk_btn_preview_image' ),
			prev_img = val.match( /(http|https):\/\/[^ ]+(\.gif|\.jpg|\.jpeg|\.png)/gm );

		if ( val ) {
			en.addClass( 'active_stylekit' );
		}

		if ( prev_img && prev_img[0] ) {
		  preview_img.css( 'display', 'inline-block' );
		}

		en.off().on( 'click', function( e ) {

		  e.preventDefault();

		  var en    = $( this ),
		  	  en2    = $( this ),
			  par   = en.closest( '.vc_param' ).length ? en.parent() : $( 'body' ),
			  inp   = en.prev( '[name]' ),
			  name  = inp.attr( 'name' ),
			  hvrid = inp.data( 'hover_id' ),
			  id    = name.match( /\w+/g ),
			  id    = id[ id.length - 1 ],
			  nameT = name.replace( id, id + '_tablet' ),
			  nameM = name.replace( id, id + '_mobile' ),
			  csf   = inp.data( 'fields' ) + ' padding margin',
			  csf_ar= csf.split( ' ' ),
			  form  = $( 'form', kit_modal ),
			  vcm   = en.closest( '.vc_ui-panel-content-container' ),
			  vcp   = en.closest( '.ui-draggable' ),
			  notvc = !vcm.length,
			  nameH = vcm.length ? inp.data( 'hover_id' ) : ( id === '_css_input_textarea' ? name.replace( id, id + '_focus' ) : name.replace( id, id + '_hover' ) ),
			  customizer = $( '.wp-customizer' ).length,
			  form_timeout = 0,
			  activeDevice = kit_modal.find( '.cz_sk_active_tab' ),
			  activeDevice = activeDevice.hasClass( 'fa-tablet-alt' ) ? 'tablet' : activeDevice.hasClass( 'fa-mobile-alt' ) ? 'mobile' : 'desktop',
			  cachedValue = api ? api.getControlValue() : {},
			  reset_kit = function( r ) {

				// Remove active mode.
				en.removeClass( 'active_stylekit' ).parent().find( '.sk_btn_preview_image' ).next( 'span' ).html( '' );

				// Turn off form changes.
				form.off( 'keyup change' );

				// Reset Process
				kit_modal.find( '[name]' ).not( '[name="live_id"]' ).val( '' ).trigger( 'change' ).closest( 'form' ).find( '.csf-icon-preview, .csf-font-remove, .csf-icon-remove' ).trigger( 'click' ).find( 'i:not(.fa-remove)' ).removeClass();
				kit_modal.find( '.cz_connect' ).removeClass( 'cz_connect' );
				kit_modal.find( '[name="background[orientation]"]' ).val( '90deg' ).trigger( 'change' );
				kit_modal.find( '.wp-picker-clear' ).trigger( 'click' );
				$( '.cz_advance_bg' ).show();
				kit_modal.find( '.cz_bg_advanced' ).hide();
				$( '.sk_btn_preview_image' ).hide();

				if ( r != 2 ) {
					kit_modal.find( '[name]' ).closest( '.cz_sk_row > div > .csf-field' )[ r ? 'addClass' : 'hide' ]().closest( '.cz_sk_row' )[ r ? 'addClass' : 'hide' ]();
				}

				// Reset icon.
				if ( r == 1 ) {

					var name  = kit_modal.find( '[name="live_id"]' ).val(),
						id    = name.match( /\w+/g ),
						id    = id[ id.length - 1 ],
						nameT = name.replace( id, id + '_tablet' ),
						nameM = name.replace( id, id + '_mobile' ),
						hover = par.find( '[name="' + name + '"]' ).attr( 'data-hover_id' ),
						hover = name.replace( id, hover );

					// Reset all hidden fields.
					par.find( '[name="' + name + '"], [name="' + nameT + '"], [name="' + nameM + '"], [name="' + hover + '"]' ).val( '' ).trigger( 'change' );
				}

				// Update hidden field
				clearTimeout( form_timeout );
				form_timeout = setTimeout(function() {
				  form.on( 'keyup change', '[name]', function() {

					var fieldName = $( '[name="live_id"]', form ).val(),
						new_vals = form.find('[name]').filter(function() {return !!$( this ).val();}).codevzSerializeObject();

					new_vals = objToCSS( new_vals );

					if ( new_vals ) {

					  en.addClass( 'active_stylekit' );

					  var prev_img = new_vals.match( /(http|https):\/\/[^ ]+(\.gif|\.jpg|\.jpeg|\.png)/gm );

					  if ( prev_img && prev_img[0] ) {
						preview_img.css( 'background-image', 'url(' + prev_img[0] + ')' ).css( 'display', 'inline-block' );
					  } else {
						preview_img.removeAttr( 'style' );
					  }

					} else {

					  en.removeClass( 'active_stylekit' );
					  preview_img.removeAttr( 'style' );

					}

					// Set changes.
					if ( api ) {

						var value = $.extend( {}, {
								normal: '0',
								hover: '0',
								rtl: '0'
							}, cachedValue || api.getControlValue() || {} ),
							
							rtl = new_vals.match( "RTL(.*)RTL" );

						if ( $( '.cz_sk_hover' ).hasClass( 'cz_active' ) ) {
							
							if ( rtl && rtl[ 0 ] ) {
								new_vals = new_vals.replace( rtl[ 0 ], '' );
							}

							value[ 'hover' ] = new_vals || '0';

						} else if ( rtl && rtl[ 1 ] ) {

							value[ 'normal' ] = new_vals.replace( rtl[ 0 ], '' ) || '0';
							value[ 'rtl' ] = rtl[ 1 ] || '0';

						} else {

							value[ 'normal' ] = new_vals || '0';

						}

						if ( value[ 'hover' ] == null ) {
							value[ 'hover' ] = '0';
						}

						// Live font family.
						if ( value[ 'normal' ].indexOf( 'font-family' ) >= 0 ) {

							var font = value[ 'normal' ].match( /font-family:(.*?);/ );

							if ( font && font[1] ) {
								$( '#elementor-preview-iframe' ).contents().find( 'head' ).append( "<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=" + font[1].replace( '=', ':' ).replace( /"|'/g, '' ).replace( / /g, '+' ) + "' type='text/css' media='all' />" );
							}

						}

						// Cache value.
						cachedValue = value;

						// Set value.
						api.setValue( value );

					} else {
						par.find( '[name="' + fieldName + '"]' ).val( new_vals ).trigger( 'change' );
					}

					sk_value_detection( en );
				  });

				  // Fix active mode color.
				  if ( inp.val() && ! api ) {
					en.addClass( 'active_stylekit' );
				  }

				}, 100 );

			  },
			  show_csf = function() {

				$.each( csf_ar, function(i, k) {
				  if ( k === 'border' ) {
					kit_modal.find( '[name="border-style"], [name="border-color"], [name="border-radius"]' ).closest( '.csf-field' ).show().closest( '.cz_sk_row' ).show();
					kit_modal.find( '[name="border-width[top]"]' ).parents( '.csf-field' ).last().show().closest( '.cz_sk_row' ).show();
				  } else if ( k === 'border-color' ) {
					kit_modal.find( '[name="border-style"]' ).val( 'solid' ).trigger( 'change' );
					kit_modal.find( '.csf-field-key-border-color' ).show();
				  } else if ( k === 'padding' || k === 'margin' ) {
					kit_modal.find( '[name="' + k + '[top]"]' ).parents( '.csf-field' ).last().show().closest( '.cz_sk_row' ).show();
				  } else if ( k === 'box-shadow' || k === 'text-shadow' ) {
					kit_modal.find( '[name="' + k + '[x]"]' ).parents( '.csf-field' ).last().show().closest( '.cz_sk_row' ).show();
				  } else if ( k === 'background' ) {
					kit_modal.find( '[name="' + k + '[image]"]' ).parents( '.csf-field' ).last().show().closest( '.cz_sk_row' ).show();
					if ( kit_modal.find( '[name="background[color2]"]' ).val() || kit_modal.find( '[name="background[color3]"]' ).val() ) {
					  $( '.cz_advance_bg' ).trigger( 'click' );
					}
				  } else if ( k === 'svg' ) {
					kit_modal.find( '[name="_class_svg_type"], [name="_class_svg_size"], [name="_class_svg_color"]' ).closest( '.csf-field' ).show().closest( '.cz_sk_row' ).show();
				  } else {
					kit_modal.find( '[name="' + k + '"]' ).closest( '.csf-field' ).show().closest( '.cz_sk_row' ).show();
				  }
				});

				kit_modal.find( '[name]' ).each(function() {
				  var pn = $( this );
				  if ( pn.val() && pn.attr( 'name' ) != 'live_id' && pn.attr( 'name' ) != 'background[orientation]' ) {
					pn.closest( '.csf-field' ).show().closest( '.cz_sk_row' ).show();
					pn.closest( 'fieldset' ).closest( '.csf-field' ).show();
				  }
				});

				kit_modal.find( '.cz_sk_row > .cz_hr' ).hide().each(function() {
				  var n = $( this ).next( '.col' );
				  if ( n.find('> .csf-field').css('display') == 'block' || n.next( '.col' ).find('> .csf-field').css('display') == 'block' || n.next( '.col' ).next( '.col' ).find('> .csf-field').css('display') == 'block' ) {
					$( this ).show();
				  } else {
					$( this ).hide();
				  }
				});
			  },
			  set_values = function( arr, l ) {

				$.each( arr, function( key, val ) {
				  var input_n = kit_modal.find( '[name="' + key + '"]' ), input_n2;

				  if ( typeof val == 'object' ) {
					$.each( val, function( k, v ) {
					  input_n2 = kit_modal.find( '[name="' + key + '[' + k + ']"]' );
					  input_n2.val( v ).trigger( 'change' );
					  if ( input_n2.hasClass( 'wp-color-picker' ) ) {
						input_n2.wpColorPicker( 'color', v );
						if ( v && v.indexOf( 'rgba' ) >= 0 ) {
						  var alpha = parseFloat( v.replace(/^.*,(.+)\)/, '$1') ) || 1;
						  input_n2.closest( '.wp-picker-container' ).find( '.csf-alpha-slider > span' ).css( 'left', ( alpha * 100 ) + '%' );
						  input_n2.closest( '.wp-picker-container' ).find( '.csf-alpha-text' ).html( alpha );
						}
					  }
					});
				  } else {
					input_n.val( val ).trigger( 'change' );

					if ( key === 'font-family' && val ) {
					  input_n.parent().find( '.csf-font-remove' ).removeClass( 'hidden' );
					} else if ( key === '_class_indicator' && val ) {
					  input_n.parent().find( '.csf-icon-remove, .csf-icon-preview' ).removeClass( 'hidden' ).find( 'i' ).addClass( val );
					}
				  }

				  if ( input_n.hasClass( 'wp-color-picker' ) ) {
					input_n.wpColorPicker( 'color', val );
					if ( val && val.indexOf( 'rgba' ) >= 0 ) {
					  var alpha = parseFloat( val.replace(/^.*,(.+)\)/, '$1') ) || 1;
					  input_n.closest( '.wp-picker-container' ).find( '.csf-alpha-slider > span' ).css( 'left', ( alpha * 100 ) + '%' );
					  input_n.closest( '.wp-picker-container' ).find( '.csf-alpha-text' ).html( alpha );
					}
				  }
				});
				kit_modal.find( '[name="live_id"]' ).val( l ).trigger( 'change' );

				if ( api ) {

					var rtlValue = api.getControlValue()[ 'rtl' ] || '';
					rtlValue && kit_modal.find( '[name="rtl"]' ).val( rtlValue ).trigger( 'change' );

				}

				show_csf();
			  },
			  resizes = function() {
				var wpb_content_height = parseInt( vcm.css( 'height' ) ) - kit_modal.prev( '.ui-dialog-titlebar' ).height();

				kit_modal.css({
				  'width': ( parseInt( vcm.css( 'width' ) ) - 55 ) + 'px',
				  'height': ( wpb_content_height - 30 ) + 'px',
				  'max-height': parseInt( vcm.css( 'height' ) ) + 'px',
				}).closest( '.ui-dialog' ).css({
				  'top': parseInt( vcp.css( 'top' ) ) + vcp.find( '.vc_ui-post-settings-header-container' ).outerHeight() + 18 + 'px',
				  'left': parseInt( vcp.css( 'left' ) ) + 25 + 'px',
				  'width': ( parseInt( vcm.css( 'width' ) ) - 35 ) + 'px',
				  'height': ( parseInt( vcm.css( 'height' ) ) - 20 ) + 'px',
				});
			  };

		  // Fix advanced mode on first open.
		  if ( kit_modal.parent().find( '.cz_advanced_mode_active' ).length ) {
			kit_modal.parent().find( '.cz_advanced_mode_active' ).trigger( 'click' );
		  }

		  // Fix hover ID for menus
		  if ( nameH ) {
			if ( nameH.indexOf( 'menu-item' ) >= 0 ) {
			  nameH = nameH.replace( '_hover', '' ).replace( '[', '_hover[' );
			}
			if ( nameH.indexOf( '_css_menu_a_' ) >= 0 ) {
			 nameH = nameH.replace( '_hover]', ']' ).replace( '_css_menu_a_', '_css_menu_a_hover_' );
			}
			if ( nameH.indexOf( '_css_menu_ul_a_' ) >= 0 ) {
			 nameH = nameH.replace( '_hover]', ']' ).replace( '_css_menu_ul_a_', '_css_menu_ul_a_hover_' );
			}
		  }

		var value = api ? ( api.getControlValue()['normal'] || '' ) || '' : inp.val();

		  // RTL set values on first load.
		  if ( $( 'body' ).hasClass( 'rtl' ) ) {

				if ( api && api.getControlValue()['rtl'] ) {

					value = value + api.getControlValue()['rtl'];

					api.setValue( {

						'normal': value || '0',
						'hover': api.getControlValue()['hover'] || '0',
						'rtl': '0'

					} );

				} else {

					inp.val( value.replace( /RTL/g, '' ) );

				}

		  }

		  // First load
		  reset_kit();
		  sk_value_detection( en );

			var new_css = cssToObj( value );

		  if ( new_css ) {
			en.addClass( 'active_stylekit' );
		  }

		  set_values( new_css, name );

		  // Auto RTL. 
		  kit_modal.find( '.xtra-auto-rtl' ).off().on( 'click', function( e ) {

			var auto_rtl = '',
					new_css = cssToObj( api ? ( api.getControlValue()['normal'] || '' ) || '' : inp.val() );

			if ( new_css['text-align'] == 'left' ) {
			  auto_rtl += 'text-align:right;';
			} else if ( new_css['text-align'] == 'right' ) {
			  auto_rtl += 'text-align:left;';
			}

			if ( new_css['float'] == 'left' ) {
			  auto_rtl += 'float:right;';
			} else if ( new_css['float'] == 'right' ) {
			  auto_rtl += 'float:left;';
			}

			if ( new_css['left'] && new_css['right'] ) {
			  auto_rtl += 'left:' + new_css['right'] + ';';
			  auto_rtl += 'right:' + new_css['left'] + ';';
			} else if ( new_css['left'] ) {
			  auto_rtl += 'left:auto;right:' + new_css['left'] + ';';
			} else if ( new_css['right'] ) {
			  auto_rtl += 'right:auto;left:' + new_css['right'] + ';';
			}

			// Padding and margin.
			$.each( { a: 'margin', b: 'padding' }, function( e, v ) {

			  if ( new_css[ v ]['left'] != new_css[ v ]['right'] ) {

				if ( new_css[ v ]['left'] && new_css[ v ]['right'] ) {
				  auto_rtl += v + '-left:' + new_css[ v ]['right'] + ';';
				  auto_rtl += v + '-right:' + new_css[ v ]['left'] + ';';
				} else if ( new_css[ v ]['left'] ) {
				  auto_rtl += v + '-left:0px;' + v + '-right:' + new_css[ v ]['left'] + ';';
				} else if ( new_css[ v ]['right'] ) {
				  auto_rtl += v + '-right:0px;' + v + '-left:' + new_css[ v ]['right'] + ';';
				}

			  }

			});

			// Border width.
			if ( new_css['border-width']['left'] != new_css['border-width']['right'] ) {

			  if ( new_css['border-width']['left'] && new_css['border-width']['right'] ) {
				auto_rtl += 'border-left-width:' + new_css['border-width']['right'] + ';';
				auto_rtl += 'border-right-width:' + new_css['border-width']['left'] + ';';
			  } else if ( new_css['border-width']['left'] ) {
				auto_rtl += 'border-left-width:0px;border-right-width:' + new_css['border-width']['left'] + ';';
			  } else if ( new_css['border-width']['right'] ) {
				auto_rtl += 'border-right-width:0px;border-left-width:' + new_css['border-width']['right'] + ';';
			  }

			}

			$( this ).parent().find( 'textarea' ).val( auto_rtl ).trigger( 'change' );

			e.preventDefault();

		  });

		  // Modal kit dialog
		  if ( ! kit_modal.hasClass( 'ui-dialog-content' ) ) {
			kit_modal.csf_reload_script().dialog({
			  autoOpen: false,
			  title: en.text(),
			  closeText: '',
			  //modal: false,
			  height: 'auto',
			  width: ( notvc ? 500 : vcm.width() ),
			  maxHeight: ( notvc ? wind.height() * 0.75 : vcm.height() ),
			  draggable: ( notvc ? true : false ),
			  resizable: ( notvc ? true : false ),
			  position: {of: ( notvc ? wind : vcm ),my:'center',at:'center'},
			  open: function() {
				var titlebar = kit_modal.prev( '.ui-dialog-titlebar' );

				titlebar.find( '.fa-desktop' ).addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );
				
				vcm.css( 'filter', 'blur(2px)' );

				titlebar.find( 'button' ).attr( 'data-title', sk_aiL10n.close );
			 
				titlebar.find( '.ui-dialog-title' ).attr( 'data-title', sk_aiL10n.normal + ' ' + sk_aiL10n.styles );
			  },
			  close: function() {vcm.css( 'filter', 'blur(0px)' );}
			}).closest( '.ui-dialog' ).css({position:"fixed"});

			setTimeout(function() {
			  kit_modal.dialog( "open" );
			  resizes();
			}, 50 );

			// Reload sizes
			if ( ! notvc ) {
			  vcp.off( 'drag.cz_sk resize.cz_sk' ).on( 'drag.cz_sk resize.cz_sk', resizes );
			  wind.off( 'resize.cz_sk' ).on( 'resize.cz_sk', resizes );
			}
		  } else {
			kit_modal.dialog( "open" ).prev( '.ui-dialog-titlebar' ).find( '.ui-dialog-title' ).html( en.text() );
			resizes();
		  }

			var mTitle = kit_modal.prev( '.ui-dialog-titlebar' );

		  // Hover
		  mTitle.find( '.ui-dialog-title' ).removeClass( 'cz_deactive' ).parent().find( '.cz_sk_hover' ).removeClass( 'cz_active' );
		  
		  if ( ( hvrid && nameH ) || api ) {

			mTitle.find( '.cz_sk_hover' ).remove();

			if ( hvrid === '_css_input_textarea_focus' ) {
				mTitle.append( '<span class="cz_sk_hover" data-title="' + sk_aiL10n.focus + ' ' + sk_aiL10n.styles + '">' + sk_aiL10n.focus + '</span>' );
			} else {
				mTitle.append( '<span class="cz_sk_hover" data-title="' + sk_aiL10n.hover + ' ' + sk_aiL10n.styles + '">' + sk_aiL10n.hover + '</span>' );
			}

			mTitle.find( '.cz_sk_hover' ).off( 'click' ).on( 'click', function() {
			  reset_kit( 2 );
			  var val = api ? cachedValue[ 'hover' ] || ( api.getControlValue()['hover'] || '0' ) : $( '[name="' + nameH + '"]' ).val();
			  set_values( cssToObj( val ), nameH );
			  if ( val ) {
			  	en.addClass( 'active_stylekit' );
			  }
			  mTitle.find( '.fa-desktop' ).addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );
			  $( this ).addClass( 'cz_active' ).parent().find( '.ui-dialog-title' ).addClass( 'cz_deactive' );
			});

			mTitle.find( '.ui-dialog-title' ).off( 'click' ).on( 'click', function() {
			  reset_kit( 2 );
			  var val = api ? cachedValue[ 'normal' ] || value : $( '[name="' + name + '"]' ).val();
			  set_values( cssToObj( val ), name );
			  if ( val ) {
			  	en.addClass( 'active_stylekit' );
			  }
			  $( this ).removeClass( 'cz_deactive' ).parent().find( '.cz_sk_hover' ).removeClass( 'cz_active' );
			});

		  } else {
			mTitle.find( '.cz_sk_hover' ).off( 'click' ).remove();
			mTitle.find( '.ui-dialog-title' ).off( 'click' ).removeClass( 'cz_deactive' );
		  }

		  // Reset button
			if ( ! mTitle.find( '.fa-refresh' ).length ) {
				mTitle.append( '<i class="fa fa-refresh" data-title="' + sk_aiL10n.reset + '"></i>' );
			}

			mTitle.find( '.fa-refresh' ).off().on( 'click', function() {

				if ( confirm( sk_aiL10n.reset_confirm ) ) {

					reset_kit( 1 );

					setTimeout( function() {

						var parent = par.find( '[name="' + $( '[name="live_id"]', form ).val() + '"]' ).parent();

						en.removeClass( 'active_stylekit' );
						parent.find( '.sk_btn_preview_image' ).next( 'span' ).html( '' );
						parent.find( '.cz_vdt' ).html( '' );

						if ( api ) {

							var eName = en.attr( 'data-name' ).replace( /_tablet|_mobile/g, '' );

							cachedValue = {};
							window[ 'xtraElementor' ][ eName ].setValue( { normal: '0', hover: '0', rtl: '0' } );
							window[ 'xtraElementor' ][ eName + '_tablet' ].setValue( { normal: '0', hover: '0', rtl: '0' } );
							window[ 'xtraElementor' ][ eName + '_mobile' ].setValue( { normal: '0', hover: '0', rtl: '0' } );

						}

					}, 100 );

				}

			});

		  // Responsive buttons
		  !mTitle.find( '.fa-mobile-alt' ).length && mTitle.append( '<i class="fas fa-mobile-alt" data-title="' + sk_aiL10n.mobile + '"></i>' );
		  !mTitle.find( '.fa-tablet-alt' ).length && mTitle.append( '<i class="fas fa-tablet-alt" data-title="' + sk_aiL10n.tablet + '"></i>' );
		  !mTitle.find( '.fa-desktop' ).length && mTitle.append( '<i class="fas fa-desktop cz_sk_active_tab" data-title="' + sk_aiL10n.desktop + '"></i>' );
		  
		  mTitle.find( '.fa-tablet-alt, .fa-mobile-alt' ).removeClass( 'cz_deactive_tm cz_sk_active_tab' );
		  mTitle.find( '.fa-desktop' ).addClass( 'cz_sk_active_tab' );
		  if ( par.hasClass( 'cz_sk' ) || en.closest( '[id*="header_1"],[id*="header_2"],[id*="header_3"],[id*="header_5"],[id*="fixed_side_1"]' ).length || en.closest( '.csf-field-group' ).length || en.closest( '[id*="mobile_header"]' ).length ) {
			mTitle.find( '.fa-tablet-alt, .fa-mobile-alt' ).addClass( 'cz_deactive_tm' );
		  }
		  if ( ! par.find( '[name="' + nameM + '"]' ).length && ! api ) {
			mTitle.find( '.fa-mobile-alt' ).addClass( 'cz_deactive_tm' );
		  }
		  if ( ! par.find( '[name="' + nameT + '"]' ).length && ! api ) {
			mTitle.find( '.fa-tablet-alt' ).addClass( 'cz_deactive_tm' );
		  }

		  // Responsive on click.
		  mTitle.find( '.fa-desktop, .fa-tablet-alt, .fa-mobile-alt' ).off().on( 'click', function() {

			var en = $( this ),
				nn = en.hasClass( 'fa-desktop' ) ? name : en.hasClass( 'fa-tablet-alt' ) ? nameT : nameM;

			if ( en.hasClass( 'cz_sk_active_tab' ) || en.hasClass( 'cz_deactive_tm' ) ) {return;}
			mTitle.find( '.ui-dialog-title' ).removeClass( 'cz_deactive' ).parent().find( '.cz_sk_hover' ).removeClass( 'cz_active' );

			reset_kit( 2 );
			set_values( cssToObj( api ? api.getControlValue()['normal'] : par.find( '[name="' + nn + '"]' ).val() ), nn );
			en.addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );

			// Customizer trigger devices icon.
			if ( customizer ) {

				var devices = $( '.devices-wrapper' ),
					wrapper = $( '.wp-full-overlay' );

				devices.find( 'button' ).removeClass( 'active' );
				wrapper.removeClass( 'preview-desktop preview-tablet preview-mobile' );

				if ( en.hasClass( 'fa-tablet-alt' ) ) {
					devices.find( '.preview-tablet' ).addClass( 'active' );
					wrapper.addClass( 'preview-tablet' );
				} else if ( en.hasClass( 'fa-mobile-alt' ) ) {
					devices.find( '.preview-mobile' ).addClass( 'active' );
					wrapper.addClass( 'preview-mobile' );
				} else {
					devices.find( '.preview-desktop' ).addClass( 'active' );
					wrapper.addClass( 'preview-desktop' );
				}

			} else if ( api ) {

				setTimeout( function() {
					$( 'a[data-sk="elementor-control-sk-c' + en.attr( 'data-cid' ) + '"]' ).trigger( 'click' );
				}, 100 );

				if ( en.hasClass( 'fa-tablet-alt' ) ) {
					$( '#e-responsive-bar-switch-tablet' ).trigger( 'click' );

				} else if ( en.hasClass( 'fa-mobile-alt' ) ) {
					$( '#e-responsive-bar-switch-mobile' ).trigger( 'click' );

				} else {
					$( '#e-responsive-bar-switch-desktop' ).trigger( 'click' );
				}

			} else {

				var wpb_current_device  = $( '#vc_screen-size-current' ),
					wpb_get_current 	= $( '#vc_screen-size-control' ),
					wpb_vc_inline_frame = $( '#vc_inline-frame' ),
					wpb_list_of_devices = $( '.vc_dropdown-list' );

				wpb_list_of_devices.find( 'a' ).removeClass( 'active' );

				if ( en.hasClass( 'fa-tablet-alt' ) ) {

					if ( ! wpb_current_device.hasClass( 'vc-c-icon-layout_landscape-tablets' ) && ! wpb_current_device.hasClass( 'vc-c-icon-layout_portrait-tablets' ) ) {

						wpb_current_device.removeClass( function( i, c ) {
							return ( c.match( /(^|\s)vc-c-icon-layout_\S+/g ) || [] ).join( ' ' );
						}).addClass( 'vc-c-icon-layout_portrait-tablets' );

						wpb_list_of_devices.find( '.vc-c-icon-layout_portrait-tablets' ).addClass( 'active' );

						wpb_vc_inline_frame.css( 'width', 768 );

					}

				} else if ( en.hasClass( 'fa-mobile-alt' ) ) {

					if ( ! wpb_current_device.hasClass( 'vc-c-icon-layout_landscape-smartphones' ) && ! wpb_current_device.hasClass( 'vc-c-icon-layout_portrait-smartphones' ) ) {

						wpb_current_device.removeClass( function( i, c ) {
							return ( c.match( /(^|\s)vc-c-icon-layout_\S+/g ) || [] ).join( ' ' );
						}).addClass( 'vc-c-icon-layout_portrait-smartphones' );

						wpb_list_of_devices.find( '.vc-c-icon-layout_portrait-smartphones' ).addClass( 'active' );

						wpb_vc_inline_frame.css( 'width', 480 );

					}

				} else if ( ! wpb_current_device.hasClass( 'vc-c-icon-layout_default' ) ) {

					wpb_current_device.removeClass( function( i, c ) {
						return ( c.match( /(^|\s)vc-c-icon-layout_\S+/g ) || [] ).join( ' ' );
					}).addClass( 'vc-c-icon-layout_default' );

					wpb_vc_inline_frame.css( 'width', '100%' );

					wpb_list_of_devices.find( '.vc-c-icon-layout_default' ).addClass( 'active' );

				}

			}

		  });

		// Customizer trigger devices icon on open.
		var device = $( '.devices-wrapper .active' ).data( 'device' ) || $( '#e-responsive-bar-switcher input:checked' ).val();

		if ( ! device ) {

			var wpb_current_device = $( '#vc_screen-size-current' );

			if ( wpb_current_device.hasClass( 'vc-c-icon-layout_landscape-tablets' ) || wpb_current_device.hasClass( 'vc-c-icon-layout_portrait-tablets' ) ) {
				device = 'tablet';
			} else if ( wpb_current_device.hasClass( 'vc-c-icon-layout_landscape-smartphones' ) || wpb_current_device.hasClass( 'vc-c-icon-layout_portrait-smartphones' ) ) {
				device = 'mobile';
			}

		}

		if ( api ) {

			var cid = parseFloat( en2.attr( 'data-sk' ).replace( 'elementor-control-sk-c', '' ) );

			if ( device === 'tablet' && ! mTitle.find( '.fa-tablet-alt' ).hasClass( 'cz_sk_active_tab' ) ) {
				
				mTitle.find( '.fa-tablet-alt' ).addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );
				mTitle.find( '.fa-desktop' ).attr( 'data-cid', cid - 1 );
				mTitle.find( '.fa-mobile-alt' ).attr( 'data-cid', cid + 1 );

			} else if ( device === 'mobile' && ! mTitle.find( '.fa-mobile-alt' ).hasClass( 'cz_sk_active_tab' ) ) {
				
				mTitle.find( '.fa-mobile-alt' ).addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );
				mTitle.find( '.fa-desktop' ).attr( 'data-cid', cid - 2 );
				mTitle.find( '.fa-tablet-alt' ).attr( 'data-cid', cid - 1 );

			} else {

				mTitle.find( '.fa-desktop' ).addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );
				mTitle.find( '.fa-desktop' ).attr( 'data-cid', cid );
				mTitle.find( '.fa-tablet-alt' ).attr( 'data-cid', cid + 1 );
				mTitle.find( '.fa-mobile-alt' ).attr( 'data-cid', cid + 2 );
						
			}

		} else {

			if ( device === 'tablet' && ! mTitle.find( '.fa-tablet-alt' ).hasClass( 'cz_sk_active_tab' ) ) {
				
				mTitle.find( '.fa-tablet-alt' ).trigger( 'click' );

			} else if ( device === 'mobile' && ! mTitle.find( '.fa-mobile-alt' ).hasClass( 'cz_sk_active_tab' ) ) {
				
				mTitle.find( '.fa-mobile-alt' ).trigger( 'click' );

			}

		}

		  // Copy/Paste icons.
		  if ( ! mTitle.find( '.fa-file-o' ).length && ! mTitle.find( '.fa-files-o' ).length ) {
			mTitle.append( '<i class="fa fa-file-o hide" data-title="' + sk_aiL10n.copy + '"></i><i class="fa fa-files-o hide" data-title="' + sk_aiL10n.paste + '"></i>' );
		  }

		  // Copy.
		  mTitle.find( '.fa-file-o' ).addClass( 'hide' ).off().on( 'click', function() {

			var $this = $( this ),
				eName = api ? en.attr( 'data-name' ).replace( /_tablet|_mobile/g, '' ) : '';

		  	var sk = {

		  		'normal' 	: api ? window[ 'xtraElementor' ][ eName ].getControlValue() || '0' : par.find( '[name="' + name + '"]' ).val(),
		  		'tablet' 	: api ? window[ 'xtraElementor' ][ eName + '_tablet' ].getControlValue() || '0' : par.find( '[name="' + nameT + '"]' ).val(),
		  		'mobile' 	: api ? window[ 'xtraElementor' ][ eName + '_mobile' ].getControlValue() || '0' : par.find( '[name="' + nameM + '"]' ).val(),
		  		'hover' 	: par.find( '[name="' + nameH + '"]' ).val() || '0',
		  		'rtl' 		: '0'

		  	};

			xdLocalStorage.setItem( 'sk', JSON.stringify( sk ), function( data ) {
				console.log( sk );
			});

			$this.addClass( 'cz_copied' ).attr( 'data-title', sk_aiL10n.copied );

			setTimeout( function() {
				$this.addClass( 'cz_copied' ).attr( 'data-title', sk_aiL10n.copy );
			}, 1000 );

			$( '.fa-files-o' ).addClass( 'cz_paste' ).attr( 'data-title', sk_aiL10n.paste );

		  });

		  // Paste.
		  mTitle.find( '.fa-files-o' ).addClass( 'hide' ).off().on( 'click', function( e ) {

			if ( confirm( sk_aiL10n.paste_confirm ) ) {

				mTitle.find( '.fa-desktop' ).addClass( 'cz_sk_active_tab' ).siblings().removeClass( 'cz_sk_active_tab' );

				xdLocalStorage.getItem( 'sk', function( data ) {

					console.log( data );

					if ( data.value ) {

						data = JSON.parse( data.value );

						var normal = data.normal,
								tablet = data.tablet,
								mobile = data.mobile,
								hover = data.hover;

						if ( api ) {

							var eName = en.attr( 'data-name' ).replace( /_tablet|_mobile/g, '' );

							window[ 'xtraElementor' ][ eName ].setValue( typeof normal == 'object' ? normal : {

								'normal': normal || '0',
								'hover': hover || '0',
								'rtl': data.rtl ? data.rtl : '0'

							} );

							window[ 'xtraElementor' ][ eName + '_tablet' ].setValue( typeof tablet == 'object' ? tablet : {

								'normal': tablet || '0',
								'hover': '0',
								'rtl': '0'

							} );

							window[ 'xtraElementor' ][ eName + '_mobile' ].setValue( typeof mobile == 'object' ? mobile : {

								'normal': mobile || '0',
								'hover': '0',
								'rtl': '0'

							} );

							$( '.ui-dialog-titlebar-close' ).trigger( 'click' );

							setTimeout( function() {
								en.trigger( 'click' );
							}, 10 );

						} else {

							normal && par.find( '[name="' + name + '"]' ).val( normal ).trigger( 'change' ).next( 'a' ).trigger( 'click' );
							tablet && par.find( '[name="' + nameT + '"]' ).val( tablet ).trigger( 'change' );
							mobile && par.find( '[name="' + nameM + '"]' ).val( mobile ).trigger( 'change' );
							hover && par.find( '[name="' + nameH + '"]' ).val( hover ).trigger( 'change' );

						}

					}

				});

			}

		  });

		  // Show all SK options
		  if ( ! mTitle.find( '.fa-toggle-off' ).length ) {
			mTitle.append( '<i class="cz_advanced_mode" data-title="' + sk_aiL10n.advanced + '"><i class="fa fa-toggle-off"></i></i>' );
		  }
		  mTitle.find( '.cz_advanced_mode' ).off().on( 'click', function() {
			$( this ).toggleClass( 'cz_advanced_mode_active' ).find( 'i' ).toggleClass( 'fa-toggle-on' );
			kit_modal.toggleClass( 'cz_show_all_sk' );
			mTitle.find( '.fa-file-o,.fa-files-o' ).toggleClass( 'hide' );
		  });

		  // Customizer responsive switcher.
		  var devices = $( '.devices-wrapper' );
		  if ( devices.length ) {

			devices.find( 'button' ).off( 'click.cz' ).on( 'click.cz', function() {
			  mTitle.find( '.fa-' + $( this ).data( 'device' ) ).trigger( 'click' );
			} );

			// Auto switch to responsive mode.
			var activeDevice = devices.find( '.active' );
			if ( activeDevice.length ) {
			  mTitle.find( '.fa-' + activeDevice.data( 'device' ) ).trigger( 'click' );
			}

		  } // customizer

		}); // style kit btn
	  }); // each btn

	}); // csf reload

	// Fix options for Appearance > Menus
	if ( $( '#menu-management' ).length ) {
	  $( '#menu-management .item-edit' ).on('click', function(e) {
		$( this ).closest('li').csf_reload_script();
	  });

	  // Fix for Pending new menu item
	  $('ul.menu').on('change', 'li', function() {
		if ( $( this ).hasClass('pending') ) {
		  $( this ).csf_reload_script();
		}
	  });
	}

});
var fancy = (function(){
	
	// vars
	var public = {},
		$ = jQuery,
		loaded = false,
		isvisible = true,
		animating = false,
		fading = false,
		gallery = false,
		galOpen = false;
		mobile = ($(window).width() > 991) ? false : true,
		adminbar = $('body').hasClass('admin-bar'),
		headerHeight = adminbar ? 102 : 70, 
		pageCache = {},
		instance = false;
	
	// init
	public.init = function(loading){
		$('.cardWrapper').css('perspective',800);
		public.resize();
		$('.focuspoint').focusPoint();
		if('pushState' in history){
			public.enableFastMode();
		}
		loader = loading; 
		public.setupLightbox();
		public.setupGallery();
	}
	
	public.when_content_loaded = function(_contentContainer, callback) {
    var _content = _contentContainer.find('img, iframe, frame, script'),
      content_length = _content.length,
      content_load_cntr = 0;
    
    if (content_length) { //if the _contentContainer contains new onload-enabled content.
      _content.on('load', function() { //then we avoid the callback until onload-enabled content is loaded
        content_load_cntr++;
        if (content_load_cntr == content_length) {
          callback();
        }
      });
    }
    else { //otherwise just do the main callback action if there's no onload-enabled content in _contentContainer.
      callback();
    }
  }
	
	public.resize = function(){
		var wHeight = $(window).height(),
			wWidth = $(window).width(),
			cHeight = $('#mainContent .card').height(),
			sbHeight = $('#sidebar .card').height(),
			minHeight = wHeight - headerHeight;
			winHeight = $(window).innerHeight() - headerHeight;
			if(minHeight < cHeight) {
				minHeight = cHeight;
			}
			
			tablet = $('#tablet-indicator').is(':visible');
			phone = $('#mobile-indicator').is(':visible');
			
			if(!tablet && !phone) {
				mobile = false;
				$('#gallery,#gallery .card, .focuspoint').css({'min-height': winHeight});
				$('.cardWrapper').css('perspective',800);
				$('#mainContent .card, #sidebar .card').css({'min-height': minHeight});
			} else if(tablet && !phone) {
				mobile = true;
				$('.cardWrapper').css('perspective',800);
				$('#mainContent .card, #sidebar .card').removeAttr('style');
				$('#gallery,#gallery .card, .focuspoint').removeAttr('style');
				mHeight = $('#mainContent .card').height(),
				sHeight = $('#sidebar .card').height();
				minHeight = mHeight;
				if(sHeight > mHeight){
					minHeight = sHeight;
				}
				
				$('#mainContent .card, #sidebar .card').css({'min-height': minHeight});
			}
			else if(phone){	
				mobile = true;
				$('#gallery,#gallery .card, .focuspoint').removeAttr('style');
				$('#mainContent .card, #sidebar .card').removeAttr('style');
			}
	}
	
	public.setupGallery = function(){
		var gLength = $('.galImage').length;
		if(gLength > 1) {
			$('<div>').addClass('dotNavi').appendTo('#gallery .cardFace');
			
			for(var i = 0; i < gLength; i++){
				$('<a>').attr('href','#').addClass('dot').appendTo('.dotNavi');
			}
			
			$('.dotNavi a').first().addClass('active');
		}
	}
	
	public.setupLightbox = function(){
		if(instance){
			instance.destroy();
		}
		instance = $('.content a[href$=".gif"], .content a[href$=".jpg"], .content a[href$=".png"], .content a[href$=".bmp"]').simpleLightbox({
			navText : ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
		});
		
	}
	
	// super fastMode
	public.enableFastMode = function(){
		$('body').on('click','a',function(e){
			
			var url = $(this).prop('href'),
				target = $(this).prop('target'),
				noAjax = $(this).hasClass('no-ajax');
			if(noAjax) return;
			if(target == '_blank') return;
			if(url.indexOf("#") > -1) return;
			if(url.indexOf('wp-admin/') > -1) return;
			if(url.indexOf('wp-login.php') > -1) return;
			if(url.match(/\.(jpeg|jpg|gif|png)$/)!= null) return;
			
			e.preventDefault();
			url = url.match(/\.de(\/.*)/)[1];
			if(mobile) {
				$('.topmenu, .navbar-toggle').removeClass('active');
				$('.topmenu li').removeClass('open');
			}
			public.niceLoad( url );
		});
		setTimeout(function(){
			$(window).bind('popstate',public.handleHistoryState);
		},500);
	}
	
	public.handleHistoryState = function(e){
		if(!animating) {
			public.niceLoad(window.location.pathname + window.location.search);
		}
	}
	
	public.niceLoad = function(url){
		if(!animating) {
			if(!mobile){
				public.goOut(url);
			} else {
				public.mobileGoOut(url);
			}
		}
	}
	
	public.docReady = function(){
		public.resize();
		
		//resize on ajax relead
		public.when_content_loaded($('#mainContent'), public.resize);
		
		public.setupLightbox();
		
		$('.focuspoint').focusPoint();
		$('.cardWrapper').css('perspective',800);
		$('header#header, #gallery').css({'-webkit-transform': 'translateZ(0)'});
		public.setupGallery();
		
		// audio,video player
		$('video,audio').mediaelementplayer(/* Options */);
		
		$(".galImage").swipe( {
	      //Generic swipe handler for all directions
	        swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
	          dir = 'next';
	          if(direction == 'right') {
	            dir = 'prev';
	          }
	          public.slideShow(dir);
	      }
	    });
	}
	
	public.calcGal = function(){
		var $el = $('#gallery .galImage.active');
		var imageW = $el.data('imageW');
	    var imageH = $el.data('imageH');
	    var imageSrc = $el.data('imageSrc');
	    
	    var containerW = $(window).width();
	    var containerH = $el.height();
	    var focusX = parseFloat($el.data('focusX'));
	    var focusY = parseFloat($el.data('focusY'));
	    var $image = $el.find('img').first();
	    
	    var wR = imageW / containerW;
	    var hR = imageH / containerH;
	    
	    //Amount position will be shifted
	    var hShift = 0,
	        vShift = 0,
	        mHeight = 'none',
	        mWidth = 'none';
	        
	    if (imageW > containerW && imageH > containerH) {
	      if(wR > hR){
	        mHeight = '100%';
	      } else {
	        mWidth = '100%';
	      }
	    }
	    
	    if (wR > hR) {
	      hShift = public.calcShift(hR, containerW, imageW, focusX);
	    } else if (wR < hR) {
	      vShift = public.calcShift(wR, containerH, imageH, focusY, true);
	    }
    
		return [hShift,vShift,mHeight,mWidth];
	}
	
	public.calcShift = function(conToImageRatio, containerSize, imageSize, focusSize, toMinus) {
    var containerCenter = Math.floor(containerSize / 2); //Container center in px
    var focusFactor = (focusSize + 1) / 2; //Focus point of resize image in px
    var scaledImage = Math.floor(imageSize / conToImageRatio); //Can't use width() as images may be display:none
    var focus =  Math.floor(focusFactor * scaledImage);
    if (toMinus) focus = scaledImage - focus;
    var focusOffset = focus - containerCenter; //Calculate difference between focus point and center
    var remainder = scaledImage - focus; //Reduce offset if necessary so image remains filled
    var containerRemainder = containerSize - containerCenter;
    if (remainder < containerRemainder) focusOffset -= containerRemainder - remainder;
    if (focusOffset < 0) focusOffset = 0;

    return (focusOffset * -100 / containerSize);
  };
	
	// In and Out Function
	//////////////////////////////////////////////////
	public.goOut = function(url) {
		animating = true;
		$(elements()).each(function (i, item) {
		var item = $(item),
		    delay = 200 * i;
		    
		    $(this).find(".card").velocity(
		    {
		    	translateZ: "-600px",
		    	rotateY: "-50deg",
		    	opacity: 0
		    }, 
		    {
		    	duration: 800,
		    	delay: delay,
		    	easing: 'easeInOutQuad',
		    	complete: function(){
		    		if(i == 2) {
		    			animating = false;
		    			isvisible = false;
		    			$(window).scrollTop(0);
		    			
		    			// reset gal if open
		    			public.resetGal();
		    			
		    			
		    			if( pageCache[url] ){
							public.processContent(pageCache[url],url);
							return;
						}
						
						$.ajax({
							type : 'html',
							url : url,
							success : function(raw){
								pageCache[this.url] = raw;
								public.processContent(pageCache[this.url], this.url);
							}
						});
		    		}
		    	}
		    }
		    );
		});
	}
	
	public.mobileGoOut = function(url) {
		animating = true;
		
		$('#wholecontent').fadeTo("fast", 0.33, function(){
			animating = false;
		    isvisible = false;
		    
		    if( pageCache[url] ){
				public.processContent(pageCache[url],url);
				return;
			}
			
			$.ajax({
				type : 'html',
				url : url,
				success : function(raw){
					pageCache[this.url] = raw;
					public.processContent(pageCache[this.url], this.url);
				}
			});
		});
	}
	
	public.comeBack = function () {
		isvisible = true;
		animating = true;
		$(elements().reverse()).each(function (i, item) {
		var item = $(item),
		    delay = 200 * i;
		    
		    $(this).find(".card").velocity(
		    {
		    	rotateY: 0,
		    	translateZ: 0,
		    	opacity: 1
		    }, 
		    {
		    	duration: 800,
		    	delay: delay,
		    	easing: 'easeInOutQuad',
		    	complete: function(){
		    		if(i == 2) {
		    			animating = false;
	            	}
		    	}
		    });
		});
	}
	
	public.mobileComeBack = function(){
		isvisible = true;
		animating = true;
		$('#wholecontent').fadeTo("fast", 1, function(){
			animating = false;
		});
	}
	
	// Helper functions for the 3 Content Boxes
	//////////////////////////////////////////////////
	function elements() {
	    var e = [$('#gallery'), $('#mainContent'), $('#sidebar')];
	    return e
	}
	
	// Process Ajax Content
	//////////////////////////////////////////////////
	public.processContent = function(raw, url){
		var test = $($.parseHTML(raw));
		var content = test.find('#mainContent .cardFace'),
		gallery = test.find('#gallery .cardFace'),
		sidebar = test.find('#sidebar .cardFace')
		raw = raw.replace(/[\r\n]/g,'');
		
		var title = raw.match(/<title>(.*)<\/title>/)[1],
			bodyclass = raw.match(/<body class="([\w_\s-]+)">/),
			slideShow = raw.match(/<!-- slideshow -->(.*)<!-- \/slideshow -->/);
		
		if(window.location.search != '') {
			history.pushState({},title,url);
		}
		
		if( url != window.location.pathname ){
			history.pushState({},title,url);
		}
		
		var urlSegments = window.location.pathname.split('/');
		if(urlSegments[0] == '') urlSegments.shift();
		urlSegment = urlSegments[0];
		if(urlSegments[0] == 'category') {
			urlSegment = urlSegments[1];
		}
		
		$('.topmenu .current-menu-item').removeClass('current-menu-item');
		$('.topmenu .current-menu-parent').removeClass('current-menu-parent');
		if( urlSegments ){
			if(urlSegment == '') {
				$('.topmenu a[href="'+window.location.origin+urlSegment+'/"]').parent().addClass('current-menu-item');
			} else {
				var li = $('.topmenu a[href*="'+urlSegment+'"]').parent();
				li.addClass('current-menu-item');
				if(li.parents('li').length > 0) {
					li.parents('li').addClass('current-menu-parent');
				}
			}
		}
		if(title != '') {
			$('title').html(title);  
		} else {
			$('title').html('Andre Knieriem Webdesign/-development');  
		}
		$('body').prop('class',bodyclass[1]);
		
		if(!animating) {
			//$('#gallery, .card, .focuspoint').css({'min-height': '0'});
			$('#mainContent .card').html(content);
			$('#gallery .card').html(gallery);
			$('#sidebar .card').html(sidebar);
		}
		$('#mainContent .card, #sidebar .card').css({'min-height': '0'});
		public.docReady();
		
		loaded = true;
		if(!isvisible) {
			if(!mobile){
				public.comeBack();
				
			} else {
				public.mobileComeBack();
			}
			
		}
	}
	
	$(window).resize(function(){
		public.resize();
	});
	
	public.slideShow = function(dir) {
		fading = true;
		var $active = $('.galImage.active');
		if ( $active.length == 0 ) {
			(dir == 'next') ? $active = $('.galImage:last') : $active = $('.galImage:first');
		}
		
		if(dir == 'next') {
			var $next =  $active.next('.galImage').length ? $active.next('.galImage'): $('.galImage:first');
		} else {
			var $next =  $active.prev('.galImage').length ? $active.prev('.galImage'): $('.galImage:last');
		}
		
		$active.addClass('last-active').fadeOut(500);
		$next.addClass('active').fadeIn(500, function(){fading = false;});
		$active.removeClass('active last-active');
		
		// Dot navigation
		index = $('.galImage').index($('.galImage.active'));
		$('.dotNavi a').removeClass('active');
		$('.dotNavi a').eq(index).addClass('active');
	}
	
	public.dotNavigation = function(index) {
		fading = true;
		var $active = $('.galImage.active');
		$next = $('.galImage').eq(index);
		$active.addClass('last-active').fadeOut(500);
		$next.addClass('active').fadeIn(500, function(){fading = false;});
		$active.removeClass('active last-active');
	}
	
	$('.menu-hauptmenu-container .menu-item-has-children').each(function(i,item){		$('<span>').addClass('mobileSub').html('<i class="fa fa-angle-down"></i>').appendTo($(item));
	});
	
	$(document).on('click', 'span.mobileSub', function(e){
		e.preventDefault();
		$(this).parent().find('.sub-menu').show();
	});
	
	$(".galImage").swipe( {
    //Generic swipe handler for all directions
      swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
        dir = 'next';
        if(direction == 'right') {
          dir = 'prev';
        }
        public.slideShow(dir);
    }
  });
  
	$(document).on('click', 'button.openGallery', function(e){
  		e.preventDefault();
  		if(!mobile){
  		loader.show();
  		
  		
  		setTimeout( function() {
  			$('.openGallery').hide();
  			$('#gallery').css({
  				'width' : '100%'
	  		});
	  		$('.galImage').adjustFocus();
	  		$('.navigation button, .dotNavi').show();
  		}, 100);
  		
  		setTimeout( function() {
  			loader.hide();
  		},350);
  		} else {
  			$('.openGallery').hide();
  			$('#gallery').css({
  				'width' : '100%'
	  		});
	  		$('.galImage').adjustFocus();
	  		$('.navigation button, .dotNavi').fadeIn(200);
  		}
  	});
	
	public.resetGal = function(){
		galOpen = false;
		$('.navigation button, .dotNavi').hide();
		$('#gallery').addClass('closed');
		$('.openGallery').show();
		$('#gallery').width('45%');
		$('.galImage img').css({'width': 'auto'});
		$('.focuspoint').adjustFocus();
	}
	$(document).on('click', '.closeGallery', function(e){
		e.preventDefault();
		galOpen = false;
		if(!mobile){
			loader.show();
		}
		setTimeout( function() {
  			$('#gallery').addClass('closed');
  			
  			if(mobile){
				$('.openGallery').fadeIn('fast');
			} else {
				$('#gallery').css({
					'width' : '45%'
				});
				$('.galImage img').css({'width': 'auto'});
				$('.focuspoint').adjustFocus();
				$('.openGallery').fadeIn('fast');
			}
	  		$('.navigation button, .dotNavi').hide();
  		}, 100);
  		
  		setTimeout( function() {
  			loader.hide();
  		},350);
	});
	
	$(document).on('click', '.search-toggle', function(e){
		e.preventDefault();
		$('.mobileSearch').toggleClass('open');
		$('.mobileSearch').slideToggle('50', function(){
			if($('.mobileSearch').hasClass('open')){
				$('.mobileSearch input#s').focus();
			}
		});
	});
	
	$(document).on('click', '.dotNavi a', function(e){
		e.preventDefault();
		if(!fading){
			var index = $('.dotNavi a').index($(this));
			$('.dotNavi a').removeClass('active');
			$(this).addClass('active');
			public.dotNavigation(index);
		}
	});
	
	// Gallery Interactions
	$(document).on('click', '.nextButton, .prevButton', function(e){
		e.preventDefault();
		dir = 'next';
		if($(this).hasClass('prevButton')) dir = 'prev';
		public.slideShow(dir);
	});
	
	$(document).on('click', '.navbar-toggle', function(e){
		e.preventDefault();
		$(window).scrollTop(0);
		$(this).toggleClass('active');
		$('.topmenu').toggleClass('active');
	});
	
	$(document).on('click', '.menu-header-search a', function(e){
		e.preventDefault();
		var elem = $(this).parents('li'),
		    field = $('.menu-header-search .formfield');
	   if(elem.hasClass('opened')){
	     field.fadeOut('fast');
	   } else {
	     field.fadeIn('fast', function(){
	       field.find('input').focus();
	     });
	   }
	   
	   elem.toggleClass('opened');
	});
	
	$(document).on('click', '.mobileSub', function(e){
		e.preventDefault();
		var elem = $(this);
		elem.parents('li').toggleClass('open');
	});
	
	$('#searchform,.searchform').on('submit', function(e){
	  e.preventDefault();
	  var input = $(this).find('input').val(),
	      url = '/?s='+input;
	  if(input != ''){
      $('.menu-header-search').removeClass('opened');
      $('.menu-header-search .formfield').fadeOut('fast');
      $('.searchform #s').val('');
      public.niceLoad( url );
	  }
	});
	
	// Fix if content size is gettings bigger after images are loaded
	$(window).load(function(){
		public.resize();
	});
	
	// Click anywhere to hide the search popdown
	$(document).on('click', function(e){
	  var target = $(e.target);
	  if(target.closest('.menu-header-search').length == 0){
	    $('.menu-header-search').removeClass('opened');
	    $('.menu-header-search .formfield').fadeOut('fast');
	  }
	});
	
	// Reply Link clickable
	$(document).on('click','.reply', function(e){
		e.preventDefault();
		$(this).find('a').click();
	});
	
	// ripple effect
	var ink, d, x, y;
	$(document).on('click','.ripplelink',function(e){
	    if($(this).find('.ink').length === 0){
	        $(this).prepend("<span class='ink'></span>");
	    }
	    
	    ink = $(this).find('.ink');
	    ink.removeClass('ripple');
	     
	    if(!ink.height() && !ink.width()){
	        d = Math.max($(this).outerWidth(), $(this).outerHeight());
	        ink.css({height: d, width: d});
	    }
	     
	    x = e.pageX - $(this).offset().left - ink.width()/2;
	    y = e.pageY - $(this).offset().top - ink.height()/2;
	     
	    ink.css({top: y+'px', left: x+'px'}).addClass('ripple');
	});
	
	return public;
})();

// Init on domready
jQuery(function($){
	var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 300, easingIn : mina.easeinout } );
	fancy.init(loader);
	
  	//var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 300, easingIn : mina.easeinout } );
});
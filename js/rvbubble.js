/*!
 * Redvolver Bubble JS
 * Author: Redvolver
 * Site: http://redvolver.it
 */

;( function( $, window, document, undefined ) {
    'use strict';

    var pluginName = "rvBubble";

    var particles = [];
    var elements = [];
    var baseelement = [];
    var zind = -1;
    var selectors = '';
    var distribution = 1;
    var defaults = {
        count: 20,
        fixedSize: false,
        minSize: 50,
        maxSize: 100,
        randomSpeed: false,
        speed: 2,
        randomColors: true,
        colors: [],
        randomOpacity: false,
        opacity: 0.3,
        mode: '', // null auto, font generate li with i inside
    };
    var containers = {};
    var maxY = '';
    var colors = '';
    var isScrolling = false;
    var lastScroll = 0;
    var screenY = 0;
    var posY = 0;

    var $window = $(window);
    var $document = $(document);

    function Plugin ( element, options ) {
        this.element = element;
        
        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    function getScrollDirection(currentScroll){
        var direction = currentScroll > lastScroll ? 'down' : 'up';

        lastScroll = currentScroll;

        return direction;
    }

    var updatePosition = function(percentage, speed) {
      var value = (speed * (100 * (1 - percentage)));
      return Math.round(value);
    };


    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {
        init: function() {

            screenY = window.innerHeight;

            this.updateDimensions();
            this.generateBubble();
            this.bindEvents();
        },

        updateDimensions: function() {
            containers.width = document.documentElement.offsetWidth;
            containers.height = document.documentElement.offsetHeight;
            maxY = containers.height;
            maxY = maxY - 250;
        },
        generateBubble: function() {
            particles.length = 0;
            
            var $el, zoom, size, x, y, zIndex,copacity;

            // Particles
            for (var i = 0; i < this.settings.count; i++) {
                $el = $('<li/>');
                zoom = Math.pow(Math.random(), distribution) * 0.99 + 0.01;

                if (this.settings.fixedSize === true) {

                    size = this.settings.maxSize;
                }else{
                    size = Math.max(this.settings.minSize, this.settings.maxSize * zoom);
                    size = Math.max(this.settings.minSize, this.settings.maxSize * zoom);
                }

                x = Math.random() * (containers.width - size);
                y = Math.random() * (maxY - size) - zoom;
                zIndex = Math.round(zoom * 100);

                if (this.settings.randomColors === true) {
                    var color = '#'+Math.random().toString(16).substr(-6);
                }else{
                    if (this.settings.colors.length > 0) {
                        var color = this.settings.colors[Math.floor(Math.random()*this.settings.colors.length)];
                    }else{
                        var color = '#'+Math.random().toString(16).substr(-6);
                    }
                }

                if (this.settings.randomOpacity === true) {
                    copacity = Math.random() * (0 - 1) + 1;
                }else{
                    copacity = this.settings.opacity;
                }

                var topx = Math.round(y);

                // Adjust dot size
                $el.css({
                    width: Math.round(size),
                    height: Math.round(size),
                    zIndex: zind,
                    opacity: copacity,
                    left: Math.round(x),
                    top: topx,
                    background: color
                });

                var block = { top: topx };

                baseelement.push(block);

                
                if (this.settings.randomSpeed === true) {
                    $el.attr('data-rvspeed',Math.floor(Math.random() * 10));
                }else{
                    $el.attr('data-rvspeed',this.settings.speed);
                }
                

                //$el.attr('data-rvspeed',0.1);
                // Add to particles
                elements.push($el[0]);
            }

            $(this.element).empty().append(elements);
            

        },
        
        bindEvents: function() {
            var tis = this;
            $window.on('scroll'+'.'+tis._name, function() {
                tis.scrollHandler.call(tis);
            });
        },
        scrollHandler: function() {

            if (window.pageYOffset !== undefined) {
                posY = window.pageYOffset;
            } else {
                posY = (document.documentElement || document.body.parentNode || document.body).scrollTop;
            }


            var currentScroll = $window.scrollTop();
            var scrollDirection = getScrollDirection(currentScroll);

            for (var i = 0; i < elements.length; i++){

                var that = $( elements[i] );
                var pos = that.position();
                var postop = pos.top;

                var copacity = Math.random();

                postop=baseelement[i].top-(currentScroll*( that.data('rvspeed') / 10) );
                that.css('top',postop+'px');

            }

        },
        
    } );

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function( options ) {
        return this.each( function() {
        
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" +
                    pluginName, new Plugin( this, options ) );
            }
            
        } );

    };

} )( jQuery, window, document );
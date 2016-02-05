/**
 * Resizable GridView columns.
 * @author Vasilij Belosludcev <https://github.com/bupy7>
 * @since 1.1.3
 */
'use strict';

(function($) {   
    // constants
    var EVENT_MOUSE_DOWN = 'mousedown', 
        EVENT_MOUSE_UP = 'mouseup',
        EVENT_MOUSE_MOVE = 'mousemove',
        EVENT_AFTER_DRAGGING = 'afterDragging.grid.rc';
    // protected properties
    var startPos = null,
        $document = $(document),
        $body = $('body');
    // public methods
    var methods = {
        init: function() {
            return this.each(function () {  
                var $th = $(this);

                $th
                    .prepend($('<div class="resizer"></div>').on(EVENT_MOUSE_DOWN, $th, startDragging))
                    .addClass('resizable-columns');
            });
        }
    };
    // protected methods
    var startDragging = function(event) {
            $body.addClass('resizing-columns');

            startPos = getMousePos(event);
            startPos.width = parseInt(event.data.width(), 10);
            startPos.height = parseInt(event.data.height(), 10);
            
            $document.on(EVENT_MOUSE_MOVE, event.data, doDrag);
            $document.on(EVENT_MOUSE_UP, event.data, stopDragging);
        },
        doDrag = function(event) {   
            var pos = getMousePos(event),
                newWidth = startPos.width + pos.x - startPos.x; 
            
            event.data.css({
                width: newWidth,
                minWidth: newWidth
            });
        },
        stopDragging = function(event) {    
            noop(event);
            
            $document.off(EVENT_MOUSE_MOVE);
            $document.off(EVENT_MOUSE_UP);
            
            $body.removeClass('resizing-columns');
            
            event.data.trigger(EVENT_AFTER_DRAGGING);
        },
        getMousePos = function(event) {
            var pos = {
                x: 0,
                y: 0,
                width: 0,
                height: 0
            };        
            
            if (typeof event.clientX === 'number') {
                pos.x = event.clientX;
                pos.y = event.clientY;
            } else if (event.originalEvent.touches) {
                pos.x = event.originalEvent.touches[0].clientX;
                pos.y = event.originalEvent.touches[0].clientY;
            } else {
                return null;
            }
            
            return pos;
        },
        noop = function(event) {
            event.stopPropagation();
            event.preventDefault();
        };
   
    $.fn.gridResizableColumns = function(options) {       
        if (methods[options]) {
            return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof options === 'object' || ! options) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method "' +  options + '" not exists.');
        }
    };
})(jQuery);
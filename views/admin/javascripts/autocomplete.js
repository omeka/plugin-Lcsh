Event.observe(window, 'load', function(){
    Event.observe('element-id', 'change', function(event){
        new Ajax.Request(<?php echo js_escape(uri(array('module' => 'simple-vocab', 
                                                        'controller' => 'index', 
                                                        'action' => 'element-terms'))); ?>, {
            method: 'get',
            parameters: {'element_id': $('element-id').getValue()},
            onComplete: function(transport) {
                $('terms').value = transport.responseText;
            }
        })
    });
});

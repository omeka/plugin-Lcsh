<?php
add_plugin_hook('admin_theme_header', 'LcshPlugin::adminThemeHeader');
add_filter(array('Form', 'Item', 'Dublin Core', 'Subject'), 'LcshPlugin::filterDcSubject');

class LcshPlugin
{
    public static function adminThemeHeader()
    {
?>
<script type="text/javascript" charset="utf-8">
document.observe('omeka:elementformload', function(e) {
    $$('#element-49 input[type="text"]').each(function(input) {
        Event.observe(input, 'keyup', function(event){
            if (3 > input.getValue().length) return;
            new Ajax.Request('<?php echo ADMIN_BASE_URL . '/lcsh/index/lcsh-proxy/'; ?>', {
                method: 'get',
                parameters: {'q': input.getValue()},
                onSuccess: function(transport, json) {
                    if (!$(input.id + '-suggest')) {
                        var suggestDiv = new Element('div', {id: input.id + '-suggest'});
                        input.insert({after: suggestDiv});
                    }
                    var html = '<ul>';
                    for(var i = 0; i < json[1].length; i++) {
                        html += '<li onclick="$(\'' + input.id + '\').setValue(\'' + json[1][i] + '\')">' + json[1][i] + '</li>';
                    }
                    html += '</ul>';
                    $(input.id + '-suggest').update(html);
                },
                onFailure: function() {
                    alert('failure');
                }
            })
        })
    })
})
</script>
<?php
    }
    
    public static function filterDcSubject($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        return __v()->formText($inputNameStem . '[text]', $value, array('size' => '50'));
    }
}
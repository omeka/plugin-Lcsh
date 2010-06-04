<?php
add_plugin_hook('admin_theme_header', 'LcshPlugin::adminThemeHeader');
add_filter(array('Form', 'Item', 'Dublin Core', 'Subject'), 'LcshPlugin::filterDcSubject');

class LcshPlugin
{
    public static function adminThemeHeader()
    {
        $db = get_db();
        $dcSubject = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Subject');
?>
<script type="text/javascript" charset="utf-8">
// Observe any keyup events from all Dublin Core:Subject form inputs.
document.observe('omeka:elementformload', function(e) {
    $$('#element-<?php echo $dcSubject->id; ?> input[type="text"]').each(function(input) {
        Event.observe(input, 'keyup', function(event){
            // Do not make a request if there are less than 3 characters in the query.
            if (3 > input.getValue().length) {
                return;
            }
            // Request the LCSH proxy for suggestions.
            new Ajax.Request('<?php echo ADMIN_BASE_URL . '/lcsh/index/lcsh-proxy/'; ?>', {
                method: 'get',
                parameters: {'q': input.getValue()},
                onSuccess: function(transport, json) {
                    // Remove the suggest div if there are no suggestions.
                    if (0 == json[1].length) {
                        $(input.id + '-suggest').remove(html);
                        return;
                    }
                    // Insert the suggest div if it doesn't already exist.
                    if (!$(input.id + '-suggest')) {
                        var suggestDiv = new Element('div', {id: input.id + '-suggest', style: 'overflow:auto;height:200px;width:420px;border:1px solid #D0D0D0;'});
                        input.insert({after: suggestDiv});
                    }
                    // Update the suggest div with the suggestion HTML.
                    var html = '<ul>';
                    for(var i = 0; i < json[1].length; i++) {
                        html += '<li onclick="$(\'' + input.id + '\').setValue(\'' + json[1][i] + '\');$(\'' + input.id + '-suggest\').remove()">' + json[1][i] + '</li>';
                    }
                    html += '</ul>';
                    $(input.id + '-suggest').update(html);
                },
                onFailure: function() {
                    //alert('failure');
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
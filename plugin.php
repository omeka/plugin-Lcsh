<?php
add_filter(array('Form', 'Item', 'Dublin Core', 'Subject'), 'LcshPlugin::filterDcSubject');

class LcshPlugin
{
    public static function filterDcSubject($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        $id = trim($inputNameStem, ']');
        $id = str_replace('][', '-', $id);
        $id = str_replace('[', '-', $id);
        $id = $id . '-text';
        
        $lcshProxyUrl = ADMIN_BASE_URL . '/lcsh/index/lcsh-proxy/';
        
        // Any HTML other than a form element will be removed when you invoke 
        // "Add Input." This means the below <script> and <div> tags will be 
        // removed
        $js = "
<script type=\"text/javascript\" charset=\"utf-8\">
Event.observe('$id', 'keyup', function(event){
    if (3 > $('$id').getValue().length) return;
    new Ajax.Request('$lcshProxyUrl', {
        method: 'get',
        parameters: {'q': $('$id').getValue()},
        onSuccess: function(transport, json) {
            //alert(json ? Object.inspect(json) : 'no JSON object');
            var html = '<ul>'; 
            for(var i = 0; i < json[1].length; i++) {
                html += '<li onclick=\"$(\'$id\').setValue(\'' + json[1][i] + '\')\">' + json[1][i] + '</li>';
            }
            html += '</ul>';
            $('$id-suggest').update(html)
        },
        onFailure: function() {
            alert('failure');
        }
    })
});
</script>";
//exit($js);
        return __v()->formText($inputNameStem . '[text]', $value, array('size' => '50')) 
               . $js 
               . '<div id="' . $id . '-suggest"></div>' ;
    }
}
(function() {
    tinymce.PluginManager.add('ytube_tc_button', function( editor, url ) {
        editor.addButton( 'ytube_tc_button', {
            text: 'YouTube',
            icon: false,
            onclick: function() {
                    editor.insertContent("[ytube_shortcode url='|||||' ]");
            }
        });
    });
})();
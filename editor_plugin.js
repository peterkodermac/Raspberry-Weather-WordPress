function raspberryweather() {
    return "[line_chart title=\"My weather station temperatures\" day=\"Today\" display=\"Temperatures\" scale=\"Celsius\" v_title=\"Temperatures\" width=\"600px\" height=\"400px\" ] ";
}

(function() {

    tinymce.create('tinymce.plugins.raspberryweather', {

        init : function(ed, url){
            ed.addButton('raspberryweather', {
                title : 'Add the Raspberry Weather shortcode',
                onclick : function() {
                    ed.execCommand(
                        'mceInsertContent',
                        false,
                        raspberryweather()
                        );
                },
                image: url + "/wand.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Raspberry Weather plugin button',
                author : 'Peter Kodermac',
                authorurl : 'http://www.kodermac.com',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('raspberryweather', tinymce.plugins.raspberryweather);
    
})();

require.config({
  baseUrl: '/js',
  paths: {
    json2: 'lib/json2',    
    jQuery: 'lib/jquery/jquery',
    jQueryUI: 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui',
    underscore: 'lib/underscore',
    backbone: 'lib/backbone',
    handlebars: 'lib/handlebars',
    moment: 'lib/moment',
    countdown: 'lib/countdown',
    bootstrap: 'lib/bootstrap',
    SWFUpload: 'lib/swfupload',
    remaining: 'http://www.labs.mimmin.com/countdown/remaining',
    raty: 'lib/jquery.raty',
    fileuploader: 'lib/fileuploader'
    // fosrouter: '../bundles/fosjsrouting/js/router',
  },

  shim: {
    "bootstrap": {
        deps: ["jQuery"],
        exports: "$"
    },

    "app" : {
        deps: ["backbone"]
    },

    "raty": {
        deps: ["jQuery"]
    },

    "remaining": {
      exports: "remaining"
    },

    "jQueryUI": {
        deps: ["jQuery"]
    },

    "fileuploader": {
        exports: "qq"
    },
    
    // "fosrouter": {
    //     exports: "Routing"
    // },

    "handlebars": {
        exports: "Handlebars"
    },

    "underscore": {
        exports: "_"
    },

    "backbone": {
        deps: ["json2","handlebars", "underscore", "jQuery"],
        exports: "Backbone"
    }
  }
});

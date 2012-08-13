/*global module:false*/
module.exports = function(grunt) {

  fs = require( "fs" ),
  path = require( "path" ),
  request = require( "request" ),

  libFiles = grunt.file.expandFiles( "assets/js/lib/**/*.js" ),
  elleolFiles = grunt.file.expandFiles("assets/js/elleol/**/*.js"),

  lessFiles = [
    "bootstrap",
    "responsive"
  ].map(function( component ) {
    return "assets/less/" + component + ".less";
  }),

  cssFiles = [
    "styles"
  ].map(function( component ) {
    return "assets/css/" + component + ".css";
  }),

  minify = {};

  function mapMinFile( file ) {
    return file.replace( /\.js$/, ".min.js" ).replace(/^assets\//, "web/");
  }

  libFiles.forEach(function( file ) {
    minify[ mapMinFile( file ) ] = [ "<banner>", file ];
  });

  elleolFiles.forEach(function( file ) {
    minify[ mapMinFile( file ) ] = [ "<banner>", file ];
  });


  // grunt plugins
  grunt.loadNpmTasks( "grunt-css" );
  grunt.loadNpmTasks( "grunt-html" );
  grunt.loadNpmTasks('grunt-less');
  grunt.loadNpmTasks('grunt-shell');
  grunt.loadTasks( "build/tasks" );

  grunt.registerHelper( "strip_all_banners", function( filepath ) {
    return grunt.file.read( filepath ).replace( /^\s*\/\*[\s\S]*?\*\/\s*/g, "" );
  });

  function stripBanner( files ) {
    return files.map(function( file ) {
      return "<strip_all_banners:" + file + ">";
    });
  }

  function stripDirectory( file ) {
    // TODO: we're receiving the directive, so we need to strip the trailing >
    // we should be receving a clean path without the directive
    return file.replace( /.+\/(.+?)>?$/, "$1" );
  }
  // allow access from banner template
  global.stripDirectory = stripDirectory;

  function createBanner( files ) {
    // strip folders
    var fileNames = files && files.map( stripDirectory );
    return "/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - " +
      "<%= grunt.template.today('isoDate') %>\n" +
      "<%= pkg.homepage ? '* ' + pkg.homepage + '\n' : '' %>" +
      "* Includes: " + (files ? fileNames.join(", ") : "<%= stripDirectory(grunt.task.current.file.src[1]) %>") + "\n" +
      "* Copyright (c) <%= grunt.template.today('yyyy') %> <%= pkg.author.name %>; */";
  }

  
  // Project configuration.
  grunt.initConfig({
    pkg: "<json:package.json>",

    meta: {
      banner: createBanner(),
      bannerAll: createBanner( elleolFiles ),
      bannerCSS: createBanner( cssFiles )      
    },
    shell: {
      load_fixtures: {
        command: "php app/console doctrine:mongodb:fixtures:load"
      },
      update_db: {
        command: "php app/console doctrine:mongodb:schema:update"
      },
      generate_site_docs: {
        command: "php app/console doctrine:mongodb:generate:documents ElleOLSiteBundle"
      },
      generate_admin_docs: {
        command: "php app/console doctrine:mongodb:generate:documents ElleOLAdminBundle"
      }      
    },
    copy: {
      assets: {
        src: ["assets/js/**/*", "assets/css/**/*.css"],
        strip: /^assets/,
        dest: "web/"
      }
    },
    less: {
      files: {
        src: [ lessFiles ],
        dest: "assets/css/styles.css"        
      } 
    },    
    lint: {
      files: ['assets/js/elleol/**/*.js'] 
    },
    concat: {
      css: {
        src: [ "<banner:meta.bannerCSS>", stripBanner( cssFiles ) ],
        dest: "assets/css/styles.css"
      }
    },
    min: minify,
    watch: {
      jslib: {
        files: 'assets/js/**/*',
        tasks: 'lint copy'
      },     
      css: {
        files: 'assets/less/**/*.less',
        tasks: 'less concat:css cssmin copy'
      }
      
    },
    cssmin: {
      css: {
        src: '<config:concat.css.dest>',
        dest: 'web/css/styles.min.css'
      }
    },
    jshint: {
      options: {
        curly: true,
        eqeqeq: true,
        immed: true,
        latedef: true,
        newcap: true,
        noarg: true,
        sub: true,
        undef: true,
        boss: true,
        eqnull: true,
        browser: true,
        smarttabs: true,
        devel: true
      },
      globals: {
        $: false,
        jQuery: true,
        Handlebars: false,
        App: false,
        moment: false,
        remaining: false,
        SWFUpload: false,
        Backbone: false,
        _: false,
        Routing: false,
        require: false,
        define: false,
        qq: false
      }
    },
    uglify: {}
  });

  // Default task.
  grunt.registerTask('default', 'lint less concat min copy');
  grunt.registerTask('css', 'less concat:css cssmin copy');
  grunt.registerTask('initdb', 'shell:generate_site_docs shell:generate_admin_docs shell:update_db shell:load_fixtures');
};

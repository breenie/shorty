module.exports = function (grunt) {
  'use strict';

  require('load-grunt-tasks')(grunt);

  // Configurable paths for the application
  var appConfig = {
    app: require('./bower.json').appPath || 'app',
    dist: 'web'
  };

  grunt.initConfig({
    shorty: appConfig,
    pkg: grunt.file.readJSON('package.json'),
    concurrent: {
      foreman: {
        tasks: ['foreman', 'watch'],
        options: {
          logConcurrentOutput: true
        }
      }
    },
    copy: {
      html: {
        src: '<%= shorty.app %>/index.html', dest: '<%= shorty.dist %>/index.html'
      },
      dist: {
        files: [{
          expand: true,
          dot: true,
          cwd: '<%= shorty.app %>',
          dest: '<%= shorty.dist %>',
          src: [
            '*.{ico,png,txt}',
            '.htaccess',
            '{,*/}*.html' //,
            //'images/{,*/}*.{webp}',
            //'styles/fonts/{,*/}*.*'
          ]
        }, {
          expand: true,
          cwd: '.tmp/images',
          dest: '<%= shorty.dist %>/images',
          src: ['generated/*']
        }/*, {
          expand: true,
          cwd: 'bower_components/bootstrap/dist',
          src: 'fonts/*',
          dest: '<%= shorty.dist %>'
        }*/]
      },
      styles: {
        expand: true,
        cwd: '<%= shorty.app %>/styles',
        dest: '.tmp/styles/',
        src: '{,*/}*.css'
      }
    },
    concat: {
      options: {
        separator: ';'
      },
      js: {
        src: [
          'bower_components/bootstrap-without-jquery/bootstrap3/bootstrap-without-jquery.min.js',
          'bower_components/angular/angular.js',
          'bower_components/angular-route/angular-route.js',
          'bower_components/angular-resource/angular-resource.js',
          'bower_components/angular-flash/dist/angular-flash.js',
          'bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.js',
          '<%= shorty.app %>/scripts/{,*/}*.js'
        ],
        dest: '<%= shorty.dist %>/js/<%= pkg.name %>.js'
      },
      css: {
        src: [
          'bower_components/bootstrap/dist/css/bootstrap.min.css',
          '<%= shorty.app %>/styles/shorty.css'
        ],
        dest: '<%= shorty.dist %>/css/<%= pkg.name %>.css'
      }
    },

    jasmine: {
      customTemplate: {
        src: [
        ],
        options: {
          //helpers: 'spec/*Helper.js',
          //template: require('exports-process.js')
          vendor: [
            'bower_components/bootstrap-without-jquery/bootstrap3/bootstrap-without-jquery.min.js',
            'bower_components/angular/angular.js',
            'bower_components/angular-route/angular-route.js',
            'bower_components/angular-mocks/angular-mocks.js',
            'bower_components/angular-resource/angular-resource.js',
            'bower_components/angular-flash/dist/angular-flash.js'
          ]
        }
      }
    },

    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n',
        screwIE8: true
      }
    },

    // Make sure code styles are up to par and there are no obvious mistakes
    jshint: {
      options: {
        jshintrc: '.jshintrc' //,
        //reporter: require('jshint-stylish')
      },
      dist: {
        src: [
          'Gruntfile.js',
          '<%= shorty.app %>/scripts/{,*/}*.js'
        ]
      },
      test: {
        options: {
          jshintrc: 'tests/.jshintrc'
        },
        src: ['tests/spec/{,*/}*.js']
      }
    },

    watch: {
      options: {
        livereload: 35729
      },
      bower: {
        files: ['bower.json'] //,
        //tasks: ['wiredep']
      },
      js: {
        files: ['<%= shorty.app %>/scripts/{,*/}*.js'],
        tasks: ['newer:jshint:dist', 'concat'],
        options: {
          livereload: '<%= watch.options.livereload %>'
        }
      },
      jsTest: {
        files: ['tests/spec/{,*/}*.js'],
        tasks: ['newer:jshint:test', 'karma']
      },
      styles: {
        files: ['<%= shorty.app %>/styles/{,*/}*.css'],
        tasks: ['newer:copy:styles']
      },
      gruntfile: {
        files: ['Gruntfile.js']
      },
      livereload: {
        options: {
          livereload: '<%= watch.options.livereload %>'
        },
        files: [
          '<%= shorty.app %>/{,*/}*.html',
          '.tmp/styles/{,*/}*.css',
          '<%= shorty.app %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
        ],
        tasks: [
          'newer:copy:styles',
          'newer:copy:dist',
          'processhtml:dev'
        ]
      }
    },

    processhtml: {
      options: {
        process: true
      },
      dev: {
        files: {
          '<%= shorty.dist %>/index.html': ['<%= shorty.app %>/index.html']
        }
      },
      dist: {
        files: {
          '<%= shorty.dist %>/index.html': ['<%= shorty.app %>/index.html']
        }
      }
    },

    clean: {
      dist: {
        files: [{
          dot: true,
          src: ['<%= shorty.dist %>/*', '!<%= shorty.dist %>/index.php']
        }]
      },
      build: [
        '<%= shorty.dist %>/js/{,*/}*.js',
        '<%= shorty.dist %>/{,*/}*.css',
        '!<%= shorty.dist %>/{,*/}*.min.*.js',
        '!<%= shorty.dist %>/{,*/}*.min.*.css'
      ]
    },

    filerev: {
      dist: {
        src: [
          '<%= shorty.dist %>/js/{,*/}*.min.js',
          '<%= shorty.dist %>/css/{,*/}*.min.css'
        ]
      }
    },

    useminPrepare: {
      html: '<%= shorty.app %>/index.html',
      options: {
        staging: 'build/.tmp',
        dest: '<%= shorty.dist %>',
        flow: {
          html: {
            steps: {
              js: ['concat', 'uglifyjs'],
              css: ['cssmin']
            },
            post: {}
          }
        }
      }
    },

    usemin: {
      html: ['<%= shorty.dist %>/index.html'],
      css: ['<%= shorty.dist %>/css/{,*/}*.css'],
      js: ['<%= shorty.dist %>/js/{,*/}*.js'],
      options: {
        assetsDirs: [
          '<%= shorty.dist %>/',
          '<%= shorty.dist %>/js',
          '<%= shorty.dist %>/css'
        ]
      }
    },

    karma: {
      unit: {
        configFile: 'tests/karma.conf.js',
        singleRun: true
      }
    }
  });

  grunt.registerTask('serve', 'Compile then start a connect web server', function () {
    grunt.task.run([
      'clean:dist',
      'newer:jshint:dist',
      'newer:copy:styles',
      'newer:copy:dist',
      'concat',
      'concurrent:foreman'
    ]);
  });

  grunt.registerTask('build', [
    'clean:dist',
    'copy:dist',
    'concat:js',
    'concat:css',
    'useminPrepare',
    'concat:generated',
    'cssmin:generated',
    'uglify:generated',
    'filerev',
    'usemin',
    'htmlmin',
    'clean:build'
  ]);

  grunt.registerTask('test', ['jshint', 'karma']);
  grunt.registerTask('default', ['test', 'build']);
};
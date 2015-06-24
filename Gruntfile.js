module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        copy: {
            all: {
                // This copies all the html and css into the dist/ folder
                expand: true,
                cwd: 'src/Shorty/Resources/public',
                src: ['partials/*.html'],
                dest: 'web/'
            }
        },
        concat: {
            options: {
                separator: ';'
            },
            dist: {
                src: [
                    'bower_components/bootstrap-without-jquery/bootstrap3/bootstrap-without-jquery.min.js',
                    'bower_components/angular/angular.js',
                    'bower_components/angular-route/angular-route.js',
                    'bower_components/angular-resource/angular-resource.js',
                    'bower_components/angular-flash/dist/angular-flash.js',
                    'src/Shorty/Resources/public/js/app.js',
                    'src/Shorty/Resources/public/js/services.js',
                    'src/Shorty/Resources/public/js/controllers.js',
                    'src/Shorty/Resources/public/js/filters.js'
                ],
                dest: 'web/js/<%= pkg.name %>.js'
            },
            css: {
                src: [
                    'bower_components/bootstrap/dist/css/bootstrap.min.css',
                    'src/Shorty/Resources/public/css/shorty.css'
                ],
                dest: 'web/css/<%= pkg.name %>.css'
            }
        },
        jasmine: {
            customTemplate: {
                src: [
                    'src/Shorty/Resources/public/js/app.js',
                    'src/Shorty/Resources/public/js/services.js',
                    'src/Shorty/Resources/public/js/controllers.js',
                    'src/Shorty/Resources/public/js/filters.js'
                ],
                options: {
                    specs: 'src/Shorty/Resources/public/test/unit/*Spec.js',
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
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
            },
            dist: {
                files: {
                    'dist/<%= pkg.name %>.min.js': ['<%= concat.dist.dest %>']
                }
            }
        },
        jshint: {
            files: ['Gruntfile.js', 'src/**/*.js', 'test/**/*.js'],
            options: {
                // options here to override JSHint defaults
                globals: {
                    jQuery: false,
                    console: true,
                    module: true,
                    document: true
                }
            }
        },
        watch: {
            files: ['<%= jshint.files %>', '<%= copy.all.cwd %>/<%= copy.all.src %>'],
            tasks: [
                'copy',
                'concat',
                'jshint' //,
                // 'qunit'
            ]
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-jasmine');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('test', ['jshint', 'jasmine']);

    grunt.registerTask('default', ['jshint', /*'jasmine',*/ 'concat' /*, 'uglify'*/]);
};
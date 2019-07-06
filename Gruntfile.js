module.exports = function(grunt) {
	// Project configuration
    var autoprefixer = require('autoprefixer');
    var flexibility = require('postcss-flexibility');

	grunt.initConfig({

		pkg: grunt.file.readJSON( 'package.json' ),

		copy: {
            main: {
                options: {
                    mode: true
                },
                src: [
                    '**',
                    '*.zip',
                    '!node_modules/**',
                    '!build/**',
                    '!css/sourcemap/**',
                    '!.git/**',
                    '!bin/**',
                    '!.gitlab-ci.yml',
                    '!bin/**',
                    '!tests/**',
                    '!phpunit.xml.dist',
                    '!*.sh',
                    '!*.map',
                    '!Gruntfile.js',
                    '!package.json',
                    '!.gitignore',
                    '!phpunit.xml',
                    '!README.md',
                    '!sass/**',
                    '!codesniffer.ruleset.xml',
                    '!vendor/**',
                    '!composer.json',
                    '!composer.lock',
                    '!package-lock.json',
                    '!phpcs.xml.dist',
                ],
                dest: 'social-elementor-lite/'
            }
        },
		uglify: {
			js: {
				files: [
					{ // all .js to min.js
                        expand: true,
                        src: [ '**.js' ],
                        dest: 'assets/min-js/',
                        cwd: 'assets/js/',
                        ext: '.min.js'
                    }
				]
			}
		},
		cssmin: {
			css: {
				files: [
					{ //.css to min.css
						expand: true,
						src: [ '**.css' ],
						dest: 'assets/min-css/',
						cwd: 'assets/css/',
						ext: '.min.css'
					},
					{ // .css to social.min.css
	                    src: [
	                        'assets/css/modules/*.css'
	                    ],
	                    dest: 'assets/min-css/social-frontend.min.css'
	                },
				]
			}
		},
		rtlcss: {
			options: {
				config: {
					swapLeftRightInUrl: false,
					swapLtrRtlInUrl: false,
					autoRename: false,
					preserveDirectives: true,
					preserveComments: true
				}
			},
			main: {
				expand: true,
				ext: '-rtl.min.css',
				src: [
					'**.css',
					'!**-rtl.min.css',
				],
				dest: 'assets/min-css/',
				cwd: 'assets/min-css/',
			}
		},
		postcss: {
	        options: {
	            map: false,
	            processors: [
	                flexibility,
	                autoprefixer({
	                    browsers: [
	                        'Android >= 2.1',
	                        'Chrome >= 21',
	                        'Edge >= 12',
	                        'Explorer >= 7',
	                        'Firefox >= 17',
	                        'Opera >= 12.1',
	                        'Safari >= 6.0'
	                    ],
	                    cascade: false
	                })
	            ]
	        },
	        style: {
	            expand: true,
	            src: [
	            	'assets/css/modules/**.css',
	            	'assets/css/**.css',
					'!assets/css/**-rtl.css'
	            ]
	        }
	    },
		compress: {
			main: {
				options: {
					archive: 'social-elementor-lite-<%= pkg.version %>.zip',

					mode: 'zip'
				},
				files: [
				{
					src: [
					'./social-elementor-lite/**'
					]

				}
				]
			},
			version: {
				options: {
					archive: 'social-elementor-lite.zip',

					mode: 'zip'
				},
				files: [
				{
					src: [
					'./social-elementor-lite/**'
					]

				}
				]
			}
		},
		clean: {
			main: ["social-elementor-lite"],
			zip: ["*.zip"],
		},
		makepot: {
            target: {
                options: {
                    domainPath: '/',
                    mainFile: 'social-elementor-lite.php',
                    potFilename: 'languages/social-elementor-lite.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },
        addtextdomain: {
            options: {
                textdomain: 'social-elementor',
                updateDomains: true
            },
            target: {
                files: {
                    src: ['*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**']
                }
            }
		},
		wp_readme_to_markdown: {
			your_target: {
				files: {
					"README.md": "readme.txt"
				}
			},
		},
        bumpup: {
			options: {
				updateProps: {
					pkg: 'package.json'
				}
			},
			file: 'package.json'
		},
		replace: {
			plugin_main: {
				src: [ 'social-elementor-lite.php' ],
				overwrite: true,
				replacements: [
					{
						from: /Version: \d{1,1}\.\d{1,2}\.\d{1,2}/g,
						to: 'Version: <%= pkg.version %>'
					}
				]
			},

			plugin_const: {
				src: [ 'classes/class-social-elementor-loader.php' ],
				overwrite: true,
				replacements: [
					{
						from: /SOCIAL_ELEMENTOR_VER', '.*?'/g,
						to: 'SOCIAL_ELEMENTOR_VER\', \'<%= pkg.version %>\''
					}
				]
			},

			plugin_function_comment: {
                src: [
                    '*.php',
                    '**/*.php',
                    '!node_modules/**',
                    '!php-tests/**',
                    '!bin/**'
                ],
                overwrite: true,
                replacements: [
                    {
                        from: 'x.x.x',
                        to: '<%=pkg.version %>'
                    }
                ]
            }
		}
	});

	/* Load Tasks */
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );

	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.loadNpmTasks( 'grunt-rtlcss' );
	grunt.loadNpmTasks( 'grunt-postcss' );

	/* Read File Generation task */
    grunt.loadNpmTasks("grunt-wp-readme-to-markdown");
    
    // Generate Read me file
	grunt.registerTask( "readme", ["wp_readme_to_markdown"] );

	/* Version Bump Task */
	grunt.loadNpmTasks( 'grunt-bumpup' );
	grunt.loadNpmTasks( 'grunt-text-replace' );


	/* Register task started */
	grunt.registerTask('release', ['clean:zip', 'copy:main','compress:main','clean:main']);
	grunt.registerTask('download', ['clean:zip', 'copy:main','compress:version','clean:main']);
	grunt.registerTask('i18n', ['addtextdomain', 'makepot']);

	// Default
	grunt.registerTask('default', ['style', 'cssmin:css', 'uglify:js', 'rtl']);

	// rtlcss
    grunt.registerTask('rtl', ['rtlcss:main']);

    //['postcss:style', 'rtl']
    // Style
    grunt.registerTask('style', ['postcss:style']);

    // Min all
    grunt.registerTask('minify', ['style', 'cssmin:css', 'uglify:js', 'rtl']);

    // Minified CSS
    grunt.registerTask('mincss', ['style', 'cssmin:css', 'rtl']);

    // Minified JS
    grunt.registerTask('minjs', ['uglify:js']);

    // Version Bump `grunt bump-version --ver=<version-number>`
    grunt.registerTask( 'bump-version', function() {

		var newVersion = grunt.option('ver');

		if ( newVersion ) {
			grunt.task.run( 'bumpup:' + newVersion );
			grunt.task.run( 'replace' );
		}
	} );
};

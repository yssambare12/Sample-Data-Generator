module.exports = function (grunt) {
  // Project configuration
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),

    // CSS Minification
    cssmin: {
      options: {
        mergeIntoShorthands: false,
        roundingPrecision: -1,
      },
      target: {
        files: {
          "admin/css/admin.min.css": ["admin/css/admin.css"],
        },
      },
    },

    // JavaScript Minification
    uglify: {
      options: {
        banner:
          '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        compress: {
          drop_console: false,
        },
      },
      target: {
        files: {
          "admin/js/admin.min.js": ["admin/js/admin.js"],
        },
      },
    },

    // Clean files before creating release
    clean: {
      release: ["release"],
    },

    // Copy files for release
    copy: {
      release: {
        files: [
          {
            expand: true,
            src: [
              "**",
              "!node_modules/**",
              "!release/**",
              "!.git/**",
              "!.gitignore",
              "!package.json",
              "!package-lock.json",
              "!Gruntfile.js",
              "!DEVELOPMENT.md",
              "!admin/css/admin.css",
              "!admin/js/admin.js",
              "!**/.DS_Store",
            ],
            dest: "release/temp/",
          },
        ],
      },
    },

    // Create release ZIP
    compress: {
      release: {
        options: {
          archive: "sample-data-generator-<%= pkg.version %>.zip",
          mode: "zip",
        },
        files: [
          {
            expand: true,
            cwd: "release/temp/",
            src: ["**"],
            dest: "/",
          },
        ],
      },
    },

    // Clean temp directory after ZIP creation
    clean_temp: {
      temp: ["release/temp"],
    },
  });

  // Load the plugins
  grunt.loadNpmTasks("grunt-contrib-cssmin");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-contrib-compress");
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-contrib-copy");

  // Register tasks
  grunt.registerTask("minify", ["cssmin", "uglify"]);

  grunt.registerTask("release", "Create release package", function () {
    // First, minify the files
    grunt.task.run("minify");

    // Clean old release directory
    grunt.task.run("clean:release");

    // Copy files to temp directory
    grunt.task.run("copy:release");

    // Create ZIP file in root
    grunt.task.run("compress:release");

    // Clean up temp files
    grunt.task.run("clean_temp:temp");

    grunt.log.writeln("Release package created successfully!");
  });

  // Default task
  grunt.registerTask("default", ["minify"]);
};

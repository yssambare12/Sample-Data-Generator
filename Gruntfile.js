module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),

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

    clean: {
      release: ["release"],
      temp: ["release/temp"],
    },

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
              "!*.md",
              "!admin/css/admin.css",
              "!admin/js/admin.js",
              "!**/.DS_Store",
              "README.md",
            ],
            dest: "release/temp/",
          },
        ],
      },
    },

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
  });

  grunt.loadNpmTasks("grunt-contrib-cssmin");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-contrib-compress");
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-contrib-copy");

  grunt.registerTask("minify", ["cssmin", "uglify"]);

  grunt.registerTask("release", "Create release package", function () {
    grunt.task.run("minify");
    grunt.task.run("clean:release");
    grunt.task.run("copy:release");
    grunt.task.run("compress:release");
    grunt.task.run("clean:temp");
    grunt.log.writeln("Release package created successfully!");
  });

  grunt.registerTask("default", ["minify"]);
};

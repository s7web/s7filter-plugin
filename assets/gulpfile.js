'use strict';

var gulp       = require('gulp'),
	sass         = require('gulp-sass'),
	maps         = require('gulp-sourcemaps'),
	autoprefixer = require('gulp-autoprefixer'),
	livereload   = require('gulp-livereload'),
	postcss      = require('gulp-postcss'),
	notify       = require('gulp-notify'),
	lr           = require('tiny-lr'),
	server       = lr();

gulp.task('compileSass', function(){
	return gulp.src("scss/admin_interface.scss")
		.pipe(maps.init())
		.pipe(sass())
		.pipe(autoprefixer({
			browsers: '> 5%'
		}))
		.pipe(livereload(server))
		.pipe(maps.write('./'))
		.pipe(gulp.dest('css'))
		.pipe(notify({ message: 'Styles task complete' }));
});

gulp.task('watch', function(){
	server.listen(35729, function (err) {
		if (err) {
			return console.log(err)
		};
	});
	gulp.watch(['scss/*.scss'], ['compileSass']);
});

gulp.task('build', ['compileSass']);
gulp.task('default', ['watch']);

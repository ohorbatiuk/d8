var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function () {
  return gulp.src('sass/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compressed'}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('css'))
});

gulp.task('default', gulp.series('sass', function (done) {
  gulp.watch('sass/style.scss', gulp.series('sass'));
  done();
}));

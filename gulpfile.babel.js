import gulp from 'gulp';
import less from 'gulp-less';
import plumber from 'gulp-plumber';
import watch from 'gulp-watch';

const dirs = {
    src: 'resources',
    dest: 'www'
};

const lessPaths = {
    src: `${dirs.src}/less/**`,
    dest: `${dirs.dest}/styles/`
};

gulp.task('styles', () => {
    return gulp.src(lessPaths.src)
        .pipe(watch(lessPaths.src))
        .pipe(plumber())
        .pipe(less())
        .pipe(gulp.dest(lessPaths.dest));
});

gulp.task('default', ['styles']);

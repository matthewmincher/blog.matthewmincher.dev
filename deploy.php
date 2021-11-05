<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'blog.matthewmincher.dev');

// Project repository
set('repository', 'https://github.com/matthewmincher/blog.matthewmincher.dev.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('blog.matthewmincher.dev')
    ->set('deploy_path', '/var/www/{{application}}')
    ->multiplexing(false);

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('deploy:mix', function () {
    run('cd {{release_path}} && npm run prod');
});


after('deploy:vendors', 'npm:install');
after('deploy:update_code', 'deploy:mix');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');


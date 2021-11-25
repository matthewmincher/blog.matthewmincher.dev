<?php
namespace Deployer;

require 'recipe/laravel.php';

set('bin/npm', function () {
    return locateBinaryPath('npm');
});

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

desc('Install npm packages');
task('npm:install', function () {
    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/node_modules ]')) {
            run('cp -R {{previous_release}}/node_modules {{release_path}}');
        }
    }
    run("cd {{release_path}} && {{bin/npm}} install");
});

task('build', function () {
    run('cd {{release_path}} && build');
});

task('deploy:mix', function () {
    run('cd {{release_path}} && npm run prod');
});


after('deploy:vendors', 'npm:install');
after('npm:install', 'deploy:mix');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
after('deploy:symlink', 'artisan:queue:restart');


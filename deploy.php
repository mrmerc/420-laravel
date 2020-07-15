<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', '420-laravel');

// Project repository
set('repository', 'git@github.com:mrmerc/420-laravel.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('420api.bymerc.xyz')
    ->user('deployer')
    ->identityFile('~/.ssh/420deployer')
    ->set('deploy_path', '/var/www/420api.bymerc.xyz/420-laravel    ');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

// before('deploy:symlink', 'artisan:migrate');


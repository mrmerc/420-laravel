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

set('writable_mode', 'chmod');

// Hosts

host('188.120.255.154')
    ->user('deployer')
    ->port(4423)
    ->identityFile('~/.ssh/420deployer')
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('deploy_path', '/var/www/420api.bymerc.xyz/420-laravel');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

// before('deploy:symlink', 'artisan:migrate');


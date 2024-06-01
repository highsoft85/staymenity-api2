@servers([ 'stage' => ['ubuntu@13.56.160.20'], 'local' => '127.0.0.1' ])

@setup
function logMessage(string $message): string {
    return "echo '\033[32m" . $message . "\033[0m';\n";
}
@endsetup

@task('optimize', ['on' => 'stage'])
    {{ logMessage('Optimize') }}
    cd /var/www/api.staymenity.com/laradock && \
    docker-compose exec --user=laradock workspace make optimize
@endtask

@story('deploy')
    optimize
@endstory

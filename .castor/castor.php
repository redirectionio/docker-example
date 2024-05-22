<?php

use Castor\Attribute\AsArgument;
use Castor\Attribute\AsTask;
use function Castor\context;
use function Castor\finder;
use function Castor\fs;
use function Castor\request;
use function Castor\io;
use function Castor\run;

#[AsTask(name: 'run', description: 'Build, start and ensure examples are working as expected')]
function _run(
    #[AsArgument(description: 'The name of the specific example to run', autocomplete: 'get_examples')]
    ?string $example = null,
): void
{
    if (!fs()->exists('.env')) {
        io()->error('No .env file found. Please create one by copying the .env.dist file and filling in the required values.');
        return;
    }

    stop();

    if ($example) {
        io()->title("Building and running example: $example");

        example($example);

        return;
    }

    io()->title('Building and running all examples');

    foreach (get_examples() as $example) {
        example($example);
    }
}

#[AsTask(description: 'Build the given example')]
function build(
    #[AsArgument(description: 'The name of the specific example to run', autocomplete: 'get_examples')]
    ?string $example = null,
): void
{
    if (!$example) {
        foreach (get_examples() as $example) {
            build($example);
        }
        return;
    }

    io()->title("Building example: $example");

    run('docker compose build', workingDirectory: $example);
}

#[AsTask(description: 'Start the given example')]
function start(
    #[AsArgument(description: 'The name of the specific example to run', autocomplete: 'get_examples')]
    string $example,
): void
{
    io()->title("Starting example: $example");

    run('docker compose up -d', workingDirectory: $example);

    sleep(2);
}

#[AsTask(description: 'Test the given example')]
function test(
    #[AsArgument(description: 'The name of the specific example to run', autocomplete: 'get_examples')]
    string $example,
): void
{
    io()->title("Testing example: $example");

    $fails = 0;
    !assertResponse('/', 200) && $fails++;
    !assertResponse('/example/301-rio.html', 301) && $fails++;
    !assertResponse('/example/302-rio.html', 302) && $fails++;
    !assertResponse('/example/404-nginx.html', 404) && $fails++;
    !assertResponse('/example/404-rio.html', 404) && $fails++;
    !assertResponse('/example/410-rio.html', 410) && $fails++;

    if ($fails > 0) {
        throw new \RuntimeException("Some tests were not successful for example \"$example\"");
    }

    io()->writeln('');
}

#[AsTask(description: 'Stop the given example')]
function stop(
    #[AsArgument(description: 'The name of the specific example to run', autocomplete: 'get_examples')]
    ?string $example = null,
): void
{
    if (!$example) {
        foreach (get_examples() as $example) {
            stop($example);
        }
        return;
    }

    io()->title("Stopping example: $example");

    run('docker compose stop', workingDirectory: $example, allowFailure: true);
}

function get_examples(): iterable
{
    /** @var Symfony\Component\Finder\Finder $examples */
    $examples = finder()
        ->in(context()->workingDirectory)
        ->notName('app')
        ->directories()
        ->depth(0)
        ->sortByName()
    ;

    foreach ($examples as $example) {
        yield $example->getBasename();
    }
}

function example(string $example): void
{
    if (!fs()->exists("$example/docker-compose.yml")) {
        throw new \RuntimeException("The example directory \"$example\" does not exist or does not contain a docker-compose.yml file");
    }

    build($example);
    start($example);
    test($example);
    stop($example);
}

function assertResponse(string $url, int $expectedStatusCode): bool
{
    $response = request('GET', 'http://127.0.0.1:8080' . $url, [
        'max_redirects' => 0,
    ]);

    $result = $response->getStatusCode() === $expectedStatusCode ? '✔️' : '❌';

    io()->text("Asserting response for $url is status $expectedStatusCode: $result");

    return $response->getStatusCode() === $expectedStatusCode;
}

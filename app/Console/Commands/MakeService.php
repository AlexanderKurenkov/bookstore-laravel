<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

// $ php artisan make:service {ServiceName}
class MakeService extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:service {name : The name of the service class}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new service class and bind it in the service provider';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$name = $this->argument('name');
		$servicePath = app_path("Services/{$name}.php");
		$providerPath = app_path('Providers/AppServiceProvider.php');

		// Step 1: Generate the Service Class
		if (File::exists($servicePath)) {
			$this->error("Service {$name} already exists!");
			return Command::FAILURE;
		}

		$serviceStub = <<<EOL
<?php

namespace App\Services;

class {$name}
{
    // Implement service logic here
}
EOL;

		File::ensureDirectoryExists(app_path('Services'));
		File::put($servicePath, $serviceStub);
		$this->info("Service {$name} created successfully.");

		// Step 2: Bind the Service in AppServiceProvider
		if (File::exists($providerPath)) {
			$this->bindServiceInProvider($name, $providerPath);
		} else {
			$this->warn("AppServiceProvider.php not found. Service binding skipped.");
		}

		return Command::SUCCESS;
	}

	/**
	 * Bind the service in AppServiceProvider to ensure it can be resolved from the service container
	 * allowing for dependency injection and centralized lifecycle management.
	 *
	 * @param string $name
	 * @param string $providerPath
	 */
	protected function bindServiceInProvider(string $name, string $providerPath)
	{
		$providerContent = File::get($providerPath);

		$binding = <<<EOL
		// Auto-generated binding for the {$name} by make:service command.
        \$this->app->singleton(\\App\\Services\\{$name}::class, function (\$app) {
            return new \\App\\Services\\{$name}();
        });
EOL;

		if (strpos($providerContent, $binding) !== false) {
			$this->info("Service {$name} is already bound in AppServiceProvider.");
			return;
		}

		// Insert binding into the register() method
		$updatedContent = preg_replace(
			'/public function register\(\)\s*:\s*void\n?\s*{/',
			"public function register() : void\n\t{\n{$binding}\n",
			$providerContent
		);

		File::put($providerPath, $updatedContent);
		$this->info("Service {$name} bound in AppServiceProvider.");
	}
}

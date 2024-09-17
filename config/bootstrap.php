use Symfony\Component\Dotenv\Dotenv;

if (class_exists(Dotenv::class) && 'dev' === ($_ENV['APP_ENV'] ?? 'dev')) {
// Load environment variables from .env only if in development environment
(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');
}


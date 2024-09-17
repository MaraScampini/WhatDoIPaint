use Symfony\Component\Dotenv\Dotenv;

if (class_exists(Dotenv::class) && ($_ENV['APP_ENV'] ?? 'dev') === 'dev') {
// Load environment variables from .env only in development environment
(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');
}

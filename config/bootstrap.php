// config/bootstrap.php
use Symfony\Component\Dotenv\Dotenv;

if (!class_exists(Dotenv::class)) {
throw new RuntimeException('Symfony Dotenv component is not installed.');
}

(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

<?php

require_once 'vendor/autoload.php';

// Load environment variables from .env.production
$envFile = __DIR__ . '/.env.production';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, '"\'');
        }
    }
}

echo "🔧 Testing PostgreSQL Connection to Digital Ocean...\n\n";

// Database configuration
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '5432';
$database = $_ENV['DB_DATABASE'] ?? 'referral_app';
$username = $_ENV['DB_USERNAME'] ?? 'postgres';
$password = $_ENV['DB_PASSWORD'] ?? '';
$sslmode = $_ENV['DB_SSLMODE'] ?? 'prefer';

echo "Configuration:\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $database\n";
echo "Username: $username\n";
echo "Password: " . str_repeat('*', strlen($password)) . "\n";
echo "SSL Mode: $sslmode\n\n";

try {
    // First, try to connect to the default database to create our database
    echo "🔍 Step 1: Connecting to defaultdb to create referral_app database...\n";
    
    $defaultDsn = "pgsql:host=$host;port=$port;dbname=defaultdb;sslmode=$sslmode";
    $defaultPdo = new PDO($defaultDsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10
    ]);
    
    echo "✅ Connected to defaultdb successfully!\n";
    
    // Check if referral_app database exists
    $stmt = $defaultPdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
    $stmt->execute([$database]);
    
    if ($stmt->fetchColumn()) {
        echo "✅ Database '$database' already exists!\n";
    } else {
        echo "🔧 Creating database '$database'...\n";
        $defaultPdo->exec("CREATE DATABASE $database");
        echo "✅ Database '$database' created successfully!\n";
    }
    
    $defaultPdo = null;
    
    // Now connect to our specific database
    echo "\n🔍 Step 2: Connecting to referral_app database...\n";
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;sslmode=$sslmode";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10
    ]);
    
    echo "✅ Connected to $database successfully!\n";
    
    // Test basic query
    echo "\n🔍 Step 3: Testing basic query...\n";
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "✅ PostgreSQL Version: $version\n";
    
    // Test if we can create tables (simulate migration)
    echo "\n🔍 Step 4: Testing table creation permissions...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_connection (id SERIAL PRIMARY KEY, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    echo "✅ Table creation successful!\n";
    
    // Test insert
    echo "\n🔍 Step 5: Testing data insertion...\n";
    $pdo->exec("INSERT INTO test_connection DEFAULT VALUES");
    echo "✅ Data insertion successful!\n";
    
    // Test select
    echo "\n🔍 Step 6: Testing data retrieval...\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM test_connection");
    $count = $stmt->fetchColumn();
    echo "✅ Data retrieval successful! Records: $count\n";
    
    // Clean up test table
    $pdo->exec("DROP TABLE test_connection");
    echo "✅ Test table cleaned up!\n";
    
    echo "\n🎉 All tests passed! PostgreSQL connection is working perfectly!\n";
    echo "✅ Ready for deployment!\n\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n\n";
    
    echo "🔧 Troubleshooting tips:\n";
    echo "1. Verify your Digital Ocean database credentials\n";
    echo "2. Check if your IP is whitelisted in Digital Ocean database settings\n";
    echo "3. Ensure the database cluster is running\n";
    echo "4. Verify SSL requirements (sslmode=require)\n";
    echo "5. Check firewall settings on port $port\n\n";
    
    exit(1);
} catch (Exception $e) {
    echo "❌ Unexpected error: " . $e->getMessage() . "\n";
    exit(1);
}

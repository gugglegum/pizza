<?php

class App
{
    private $_migrationsDir;

    public function __construct()
    {
        $this->_migrationsDir = __DIR__ . "/migrations";
    }

    /**
     * @throws Exception
     */
    public function main()
    {
        echo "Update database from migrations\n\n";
        if (! $this->_isMigrationsTableExists()) {
            echo "Migration table does not exists.\n";
            if ($this->_getTablesAmount() > 0) {
                throw new Exception("Database already has tables");
            }
            if (! $this->_promptYesNoQuestion("Would you like to create migrations table?")) {
                echo "Exiting...\n";
                return 1;
            }
            echo "Creating migration table ... ";
            $this->_createMigrationTable();
            echo "OK\n";
        }

        $newMigrations = $this->_getNewMigrations();
        if (empty($newMigrations)) {
            echo "There is all up to date\n";
            return 0;
        }

        echo "There is ", count($newMigrations), " new migration(s):\n\n";
        $i = 1;
        foreach ($newMigrations as $file) {
            echo "\t{$i}. {$file}\n";
            $i++;
        }
        if (! $this->_promptYesNoQuestion("Would you like to apply these migrations?")) {
            echo "Exiting...\n";
            return 1;
        }

        echo "\n";
        foreach ($newMigrations as $file) {
            echo "Applying {$file} ... ";
            $this->_applyMigration($file);
            echo "OK\n";
        }
        return 0;
    }

    private function _applyMigration($migrationFile)
    {
        if (! $sql = file_get_contents($this->_migrationsDir . "/" . $migrationFile) ) {
            throw new Exception("Failed to read " . $this->_migrationsDir . "/" . $migrationFile);
        }
        $this->_query("START TRANSACTION");
        $dbLink = $this->_getDbLink();
        if (! $dbLink->multi_query($sql)) {
            throw new Exception($dbLink->error, $dbLink->errno);
        }

        do {
            if ($result = $dbLink->use_result()) {
                $result->free();
            }
        } while ($dbLink->more_results() && $dbLink->next_result());

        if ($dbLink->errno) {
            throw new Exception($dbLink->error, $dbLink->errno);
        }

        $m = $this->_matchMigrationFile($migrationFile);
        $this->_query("INSERT INTO `migrations` (`created_utc`, `name`, `applied_ts`) VALUES (
            " . $this->_quote($m["created_utc"]) . ",
            " . $this->_quote($m["name"]) . ",
            NOW()
        )");

        $this->_query("COMMIT");
//        $this->_query("ROLLBACK");
    }

    private function _getNewMigrations()
    {
        if (! $dh = opendir($this->_migrationsDir) ) {
            throw new Exception("Can't open dir " . $this->_migrationsDir);
        }
        $newMigrations = array();
        while (($entry = readdir($dh)) !== false) {
            if (! is_file($this->_migrationsDir . "/{$entry}")) {
                continue;
            }
            if ($m = $this->_matchMigrationFile($entry)) {
                if (! $this->_isMigrationApplied($m["created_utc"], $m["name"])) {
                    $newMigrations[] = $entry;
                }
            }
        }
        closedir($dh);
        sort($newMigrations);
        return $newMigrations;
    }

    private function _matchMigrationFile($file)
    {
        if (preg_match("/^m(\\d{4})(\\d{2})(\\d{2})_(\\d{2})(\\d{2})(\\d{2})_(.+)\\.sql$/", $file, $m)) {
            return array(
                "created_utc" => "{$m[1]}-{$m[2]}-{$m[3]} {$m[4]}:{$m[5]}:{$m[6]}",
                "name" => $m[7],
            );
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    private function _getDbConfig()
    {
        return require(__DIR__ . "/dbConfig.php");
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    private function _getDbLink()
    {
        static $dbLink = null;
        if (! $dbLink) {
            $cfg = $this->_getDbConfig();
            $dbLink = new mysqli($cfg["host"], $cfg["user"], $cfg["pass"], $cfg["dbName"], $cfg["port"]);
            if ($dbLink->connect_error) {
                throw new Exception($dbLink->connect_error, $dbLink->connect_errno);
            }
        }
        return $dbLink;
    }

    private function _createMigrationTable()
    {
        $this->_query("CREATE TABLE `migrations` (
              `created_utc` datetime NOT NULL,
              `name` varchar(128) NOT NULL,
              `applied_ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
              UNIQUE KEY `created_name` (`created_utc`,`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    /**
     * @return bool
     */
    private function _isMigrationsTableExists()
    {
        $result = $this->_query("SHOW TABLES LIKE 'migrations'");
        $isExists = $result->num_rows != 0;
        $result->free();
        return $isExists;
    }

    /**
     * @return int
     */
    private function _getTablesAmount()
    {
        $result = $this->_query("SHOW TABLES");
        $amount = $result->num_rows;
        $result->free();
        return $amount;
    }

    private function _isMigrationApplied($createdUtc, $name)
    {
        static $migrations = null;
        if ($migrations === null) {
            $result = $this->_query("SELECT `created_utc`, `name` FROM `migrations`");
            $migrations = array();
            while (($row = $result->fetch_assoc()) !== null) {
                $migrations[] = $row["created_utc"] . "_" . $row["name"];
            }
            $result->free();
        }
        return in_array("{$createdUtc}_{$name}", $migrations);
    }

    private function _query($sql)
    {
        $dbLink = $this->_getDbLink();
        $result = $dbLink->query($sql);
        if ($result === false) {
            throw new Exception($dbLink->error, $dbLink->errno);
        }
        return $result;
    }

    private function _quote($value)
    {
        return "'" . $this->_getDbLink()->escape_string($value) . "'";
    }

    private function _promptYesNoQuestion($question)
    {
        $result = null;
        do {
            echo "\n{$question} (yes/no): ";
            $input = rtrim(fgets(STDIN));
            if (preg_match("/^y(es)?$/i", $input)) {
                $result = true;
            }
            if (preg_match("/^no?$/i", $input)) {
                $result = false;
            }
        } while ($result === null);
        return $result;
    }
}

$app = new App();
try {
    $result = (int) $app->main();
    exit($result);
} catch (Exception $e) {
    echo "Error: ", $e->getCode() ? "(" . $e->getCode() . ") " : "", $e->getMessage(), "\n";
    exit(255);
}

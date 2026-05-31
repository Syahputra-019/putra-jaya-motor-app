<?php
$files = glob(__DIR__.'/database/migrations/*.php');
$tables = [];

foreach($files as $file) {
    $content = file_get_contents($file);
    
    // Find Schema::create('table_name', ...) or Schema::table('table_name', ...)
    preg_match_all("/Schema::(?:create|table)\s*\(\s*['\"]([^'\"]+)['\"]\s*,/is", $content, $matches);
    
    if (!empty($matches[1])) {
        // We might have multiple blocks in one file
        // A better approach is to split the content by Schema::create or Schema::table
        $blocks = preg_split("/Schema::(?:create|table)\s*\(\s*['\"]([^'\"]+)['\"]\s*,/is", $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        for ($i = 1; $i < count($blocks); $i+=2) {
            $tableName = $blocks[$i];
            $blockContent = $blocks[$i+1];
            
            if (!isset($tables[$tableName])) {
                $tables[$tableName] = [];
            }
            
            preg_match_all("/\\\$table->([a-zA-Z0-9_]+)\s*\(\s*['\"]([^'\"]+)['\"]/is", $blockContent, $colMatches);
            
            for ($j = 0; $j < count($colMatches[1]); $j++) {
                $type = $colMatches[1][$j];
                $name = $colMatches[2][$j];
                $tables[$tableName][] = ["type" => $type, "name" => $name];
            }
            
            // Handle timestamps() and id()
            if (preg_match("/\\\$table->timestamps\(\)/", $blockContent)) {
                $tables[$tableName][] = ["type" => "timestamp", "name" => "created_at"];
                $tables[$tableName][] = ["type" => "timestamp", "name" => "updated_at"];
            }
            if (preg_match("/\\\$table->id\(\)/", $blockContent)) {
                $tables[$tableName][] = ["type" => "id", "name" => "id"];
            }
            // Handle dropColumn
            if (preg_match_all("/\\\$table->dropColumn\(\s*\[([^\]]+)\]\s*\)/is", $blockContent, $dropMatches)) {
                // Ignore down methods since they are usually after up
                // We'll just assume they are for down() and ignore
            }
        }
    }
}

foreach($tables as $table => $cols) {
    echo "TABLE: $table\n";
    $uniqueCols = [];
    foreach($cols as $col) {
        $uniqueCols[$col['name']] = $col['type'];
    }
    foreach($uniqueCols as $name => $type) {
        echo "  - $name ($type)\n";
    }
}

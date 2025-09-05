<?php

/**
 * BIR Receipt Plugin Installation Script
 * This script helps integrate the BIR Receipt Plugin with UltimatePOS
 */

// Check if we're in the right environment
if (!defined('LARAVEL_START')) {
    die('This script must be run from within Laravel environment');
}

echo "Installing BIR Receipt Plugin...\n";

// 1. Register the module in modules_statuses.json
$modulesFile = base_path('modules_statuses.json');
$modules = [];

if (file_exists($modulesFile)) {
    $modules = json_decode(file_get_contents($modulesFile), true) ?: [];
}

$modules['BIRReceiptPlugin'] = true;

file_put_contents($modulesFile, json_encode($modules, JSON_PRETTY_PRINT));
echo "✓ Module registered in modules_statuses.json\n";

// 2. Add routes to web.php
$webRoutesFile = base_path('routes/web.php');
$birRoutesInclude = "// BIR Receipt Plugin Routes\nrequire_once base_path('Modules/BIRReceiptPlugin/Routes/web.php');\n";

if (file_exists($webRoutesFile)) {
    $webRoutesContent = file_get_contents($webRoutesFile);
    
    if (strpos($webRoutesContent, 'BIRReceiptPlugin/Routes/web.php') === false) {
        file_put_contents($webRoutesFile, $webRoutesContent . "\n" . $birRoutesInclude);
        echo "✓ Routes added to web.php\n";
    } else {
        echo "✓ Routes already exist in web.php\n";
    }
}

// 3. Add service provider to config/app.php
$appConfigFile = config_path('app.php');
if (file_exists($appConfigFile)) {
    $appConfigContent = file_get_contents($appConfigFile);
    
    if (strpos($appConfigContent, 'BIRReceiptPluginServiceProvider') === false) {
        // Find the providers array and add our service provider
        $pattern = '/(\'providers\' => \[)(.*?)(\],)/s';
        $replacement = '$1$2        Modules\\BIRReceiptPlugin\\Providers\\BIRReceiptPluginServiceProvider::class,$3';
        
        $newContent = preg_replace($pattern, $replacement, $appConfigContent);
        
        if ($newContent !== $appConfigContent) {
            file_put_contents($appConfigFile, $newContent);
            echo "✓ Service provider added to config/app.php\n";
        }
    } else {
        echo "✓ Service provider already exists in config/app.php\n";
    }
}

// 4. Create symlink for public assets (if needed)
$publicModulesDir = public_path('Modules');
$pluginPublicDir = base_path('Modules/BIRReceiptPlugin/Resources/public');

if (!is_dir($publicModulesDir)) {
    mkdir($publicModulesDir, 0755, true);
}

$symlinkTarget = $publicModulesDir . '/BIRReceiptPlugin';
if (!is_link($symlinkTarget) && !is_dir($symlinkTarget)) {
    if (is_dir($pluginPublicDir)) {
        symlink($pluginPublicDir, $symlinkTarget);
        echo "✓ Public assets symlink created\n";
    }
} else {
    echo "✓ Public assets symlink already exists\n";
}

// 5. Add integration script to main layout
$layoutFile = resource_path('views/layouts/app.blade.php');
if (file_exists($layoutFile)) {
    $layoutContent = file_get_contents($layoutFile);
    
    if (strpos($layoutContent, 'BIRReceiptPlugin/Resources/js/integration.js') === false) {
        // Add the script before closing body tag
        $scriptTag = '<script src="{{ asset("Modules/BIRReceiptPlugin/Resources/js/integration.js") }}"></script>';
        
        if (strpos($layoutContent, '</body>') !== false) {
            $layoutContent = str_replace('</body>', $scriptTag . "\n</body>", $layoutContent);
            file_put_contents($layoutFile, $layoutContent);
            echo "✓ Integration script added to main layout\n";
        }
    } else {
        echo "✓ Integration script already exists in main layout\n";
    }
}

// 6. Add menu item to UltimatePOS navigation
$menuConfigFile = config_path('menus.php');
if (file_exists($menuConfigFile)) {
    $menuConfig = include $menuConfigFile;
    
    // Add BIR Receipt menu item
    $birMenuItem = [
        'name' => 'BIR Receipt Plugin',
        'icon' => 'fas fa-receipt',
        'url' => '/bir-receipt',
        'permission' => 'bir_receipt.access',
        'order' => 100
    ];
    
    if (!isset($menuConfig['bir_receipt'])) {
        $menuConfig['bir_receipt'] = $birMenuItem;
        
        $menuContent = "<?php\n\nreturn " . var_export($menuConfig, true) . ";\n";
        file_put_contents($menuConfigFile, $menuContent);
        echo "✓ Menu item added to menus.php\n";
    } else {
        echo "✓ Menu item already exists in menus.php\n";
    }
}

echo "\nBIR Receipt Plugin installation completed!\n";
echo "\nNext steps:\n";
echo "1. Run: php artisan migrate\n";
echo "2. Run: php artisan db:seed --class=\"Modules\\\\BIRReceiptPlugin\\\\Database\\\\Seeders\\\\BIRReceiptTemplateSeeder\"\n";
echo "3. Run: php artisan db:seed --class=\"Modules\\\\BIRReceiptPlugin\\\\Database\\\\Seeders\\\\BIRReceiptSettingsSeeder\"\n";
echo "4. Visit: /bir-receipt/settings to configure your business information\n";
echo "5. Test the plugin by visiting: /bir-receipt\n";
echo "\nFor integration with other POS systems, refer to the README.md file.\n";

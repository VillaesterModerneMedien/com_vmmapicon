#!/usr/bin/env php
<?php
/**
 * Joomla Component Package Builder for com_vmmapicon
 */

// Configuration
$config = [
    'package_name' => 'com_vmmapicon',
    'package_version' => '1.0.2',
    'build_dir' => __DIR__ . '/build',
    'packages_dir' => __DIR__ . '/build/packages',
    'output_dir' => __DIR__ . '/dist'
];

// Extensions to include in the package
$extensions = [
    'components' => [
        [
            'name' => 'com_vmmapicon',
            'type' => 'component',
            'admin_folder' => 'administrator/components/com_vmmapicon',
            'site_folder' => 'components/com_vmmapicon',
            'media_folder' => 'media/com_vmmapicon',
            'manifest' => 'vmmapicon.xml',
            'zip_name' => 'com_vmmapicon.zip'
        ]
    ],
    'modules' => [],
    'plugins' => [
        [
            'name' => 'plg_system_ytvmmapicon',
            'type' => 'plugin',
            'group' => 'system',
            'folder' => 'plugins/system/ytvmmapicon',
            'manifest' => 'plugins/system/ytvmmapicon/ytvmmapicon.xml',
            'zip_name' => 'plg_system_ytvmmapicon.zip'
        ]
    ],
    'templates' => []
];

/**
 * Create directory if it doesn't exist
 */
function createDir($path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo "Created directory: $path\n";
    }
}

/**
 * Clean directory
 */
function cleanDir($path) {
    if (is_dir($path)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        rmdir($path);
        echo "Cleaned directory: $path\n";
    }
}

/**
 * Copy directory recursively
 */
function copyDir($src, $dst) {
    if (!is_dir($src)) {
        return false;
    }

    // Directories to exclude from copying
    $excludeDirs = ['.github', '.git', '.idea', 'node_modules', '.DS_Store'];

    createDir($dst);

    $files = scandir($src);
    foreach ($files as $file) {
        if ($file != "." && $file != ".." && !in_array($file, $excludeDirs)) {
            if (is_dir($src . "/" . $file)) {
                copyDir($src . "/" . $file, $dst . "/" . $file);
            } else {
                copy($src . "/" . $file, $dst . "/" . $file);
            }
        }
    }
    return true;
}

/**
 * Create ZIP archive
 */
function createZip($source, $destination) {
    if (!extension_loaded('zip')) {
        echo "ERROR: PHP ZIP extension is not installed!\n";
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        echo "ERROR: Failed to create ZIP file: $destination\n";
        return false;
    }

    $fileCount = 0;
    if (is_dir($source)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($source) + 1);

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
                $fileCount++;
            }
        }
    } else {
        $zip->addFile($source, basename($source));
        $fileCount = 1;
    }

    $zip->close();

    // Verify ZIP was created and contains files
    if (!file_exists($destination)) {
        echo "ERROR: ZIP file was not created: $destination\n";
        return false;
    }

    $filesize = filesize($destination);
    if ($filesize < 100) {
        echo "ERROR: ZIP file is too small ({$filesize} bytes), likely corrupted\n";
        return false;
    }

    // Verify ZIP can be opened
    $verifyZip = new ZipArchive();
    if ($verifyZip->open($destination) !== TRUE) {
        echo "ERROR: Created ZIP file cannot be opened: $destination\n";
        return false;
    }
    $verifyZip->close();

    echo "  ✓ ZIP verified: {$fileCount} files, " . number_format($filesize / 1024, 1) . " KB\n";
    return true;
}

/**
 * Generic build function for all extension types
 */
function buildExtension($extension, $buildDir, $packagesDir, $type) {
    // Determine display name
    $displayName = $extension['name'];
    if ($type === 'module' && isset($extension['client'])) {
        $displayName .= " ({$extension['client']})";
    } elseif ($type === 'plugin' && isset($extension['group'])) {
        $displayName .= " ({$extension['group']})";
    }

    echo "\nBuilding $type: $displayName\n";
    echo str_repeat('-', 40) . "\n";

    // Validate required paths based on type
    $validationErrors = [];

    switch ($type) {
        case 'component':
            if (isset($extension['admin_folder']) && !is_dir($extension['admin_folder'])) {
                $validationErrors[] = "Admin folder not found: {$extension['admin_folder']}";
            }
            if (isset($extension['site_folder']) && !is_dir($extension['site_folder'])) {
                $validationErrors[] = "Site folder not found: {$extension['site_folder']}";
            }
            break;
        case 'module':
        case 'plugin':
        case 'template':
            if (!isset($extension['folder']) || !is_dir($extension['folder'])) {
                $validationErrors[] = ucfirst($type) . " folder not found: " . ($extension['folder'] ?? 'not set');
            }
            break;
    }

    // Check manifest file
    if (isset($extension['manifest']) && !file_exists($extension['manifest'])) {
        $validationErrors[] = "Manifest file not found: {$extension['manifest']}";
    }

    // Return false if validation failed
    if (!empty($validationErrors)) {
        foreach ($validationErrors as $error) {
            echo "ERROR: $error\n";
        }
        return false;
    }

    // Create temp directory
    $tempDirName = $extension['name'];
    if ($type === 'module' && isset($extension['client'])) {
        $tempDirName .= '_' . $extension['client'];
    }
    $tempDir = $buildDir . '/' . $tempDirName;
    cleanDir($tempDir);
    createDir($tempDir);

    // Type-specific file copying
    switch ($type) {
        case 'component':
            if (!copyComponentFiles($extension, $tempDir)) {
                return false;
            }
            break;
        case 'module':
        case 'plugin':
        case 'template':
            if (!copySimpleExtensionFiles($extension, $tempDir, $type)) {
                return false;
            }
            break;
    }

    // Create ZIP
    $zipPath = $packagesDir . '/' . $extension['zip_name'];
    if (createZip($tempDir, $zipPath)) {
        echo "Created ZIP: {$extension['zip_name']}\n";
        return true;
    }

    return false;
}

/**
 * Copy component files with CORRECT Joomla component ZIP structure
 */
function copyComponentFiles($component, $tempDir) {
    // CORRECT JOOMLA COMPONENT ZIP STRUCTURE:
    // /vmmapicon.xml (manifest in root)
    // /administrator/ (admin files - FLAT structure, not nested)
    // /site/ (frontend files - FLAT structure, not nested)
    // /media/ (media files - FLAT structure, not nested)

    // Copy administrator files to /administrator/ folder (FLAT structure in ZIP)
    if (isset($component['admin_folder']) && is_dir($component['admin_folder'])) {
        $adminTargetDir = $tempDir . '/administrator';
        createDir($adminTargetDir);

        // Copy all admin files
        $excludeFiles = ['.', '..', 'script.php', '.github', '.git', '.DS_Store'];
        $adminFiles = scandir($component['admin_folder']);
        foreach ($adminFiles as $file) {
            if (!in_array($file, $excludeFiles)) {
                $src = $component['admin_folder'] . '/' . $file;
                $dst = $adminTargetDir . '/' . $file;
                if (is_dir($src)) {
                    copyDir($src, $dst);
                } else {
                    copy($src, $dst);
                }
            }
        }
        echo "Copied administrator files to /administrator/\n";
    }

    // Copy site files to /site/ folder (FLAT structure in ZIP)
    if (isset($component['site_folder']) && is_dir($component['site_folder'])) {
        $siteTargetDir = $tempDir . '/site';
        createDir($siteTargetDir);

        // Copy all site files
        $excludeFiles = ['.', '..', '.github', '.git', '.DS_Store'];
        $siteFiles = scandir($component['site_folder']);
        foreach ($siteFiles as $file) {
            if (!in_array($file, $excludeFiles)) {
                $src = $component['site_folder'] . '/' . $file;
                $dst = $siteTargetDir . '/' . $file;
                if (is_dir($src)) {
                    copyDir($src, $dst);
                } else {
                    copy($src, $dst);
                }
            }
        }
        echo "Copied site files to /site/\n";
    }

    // Copy media files to /media/ folder (FLAT structure in ZIP)
    if (isset($component['media_folder']) && is_dir($component['media_folder'])) {
        $mediaTargetDir = $tempDir . '/media';
        createDir($mediaTargetDir);

        // Copy media folder contents directly (expected by <media folder="media"> in manifest)
        $excludeFiles = ['.', '..', '.github', '.git', '.DS_Store'];
        $mediaFiles = scandir($component['media_folder']);
        foreach ($mediaFiles as $file) {
            if (!in_array($file, $excludeFiles)) {
                $src = $component['media_folder'] . '/' . $file;
                $dst = $mediaTargetDir . '/' . $file;
                if (is_dir($src)) {
                    copyDir($src, $dst);
                } else {
                    copy($src, $dst);
                }
            }
        }
        echo "Copied media files to /media/\n";
    }

    // Copy manifest to ROOT of ZIP
    if (isset($component['manifest']) && file_exists($component['manifest'])) {
        copy($component['manifest'], $tempDir . '/' . basename($component['manifest']));
        echo "Copied manifest to root\n";
    } else {
        echo "ERROR: Manifest file not found!\n";
        return false;
    }

    return true;
}

/**
 * Copy files for simple extensions (modules, plugins, templates)
 */
function copySimpleExtensionFiles($extension, $tempDir, $type) {
    // Copy main files
    if (isset($extension['folder']) && is_dir($extension['folder'])) {
        copyDir($extension['folder'], $tempDir);
        echo "Copied $type files\n";
    }

    // Copy media files if exists
    if (isset($extension['media_folder']) && is_dir($extension['media_folder'])) {
        copyDir($extension['media_folder'], $tempDir . '/media');
        echo "Copied media files\n";
    }

    return true;
}

/**
 * Main build process
 */
function build($config, $extensions) {
    echo "\n";
    echo "========================================\n";
    echo "  com_vmmapicon Component Builder\n";
    echo "  Version: {$config['package_version']}\n";
    echo "========================================\n";

    // Track build results
    $buildResults = [
        'success' => [],
        'failed' => [],
        'skipped' => []
    ];

    // Clean and create directories to ensure fresh build
    echo "Preparing clean build environment...\n";
    cleanDir($config['build_dir']);
    cleanDir($config['packages_dir']);

    // Delete ALL existing ZIP files to ensure fresh builds
    echo "Deleting ALL existing ZIP files...\n";
    if (is_dir($config['packages_dir'])) {
        $oldZips = glob($config['packages_dir'] . '/*.zip');
        foreach ($oldZips as $zip) {
            unlink($zip);
            echo "  Deleted: " . basename($zip) . "\n";
        }
    }

    // Also clean dist directory ZIPs for this component and plugin
    if (is_dir($config['output_dir'])) {
        foreach (glob($config['output_dir'] . '/com_vmmapicon-*.zip') as $zip) {
            unlink($zip);
            echo "  Deleted dist: " . basename($zip) . "\n";
        }
        foreach (glob($config['output_dir'] . '/plg_system_ytvmmapicon-*.zip') as $zip) {
            unlink($zip);
            echo "  Deleted dist: " . basename($zip) . "\n";
        }
    }

    // Create directories
    createDir($config['build_dir']);
    createDir($config['packages_dir']);
    createDir($config['output_dir']);

    // Build components
    echo "\n### Building Component ###\n";
    foreach ($extensions['components'] as $component) {
        if (buildExtension($component, $config['build_dir'], $config['packages_dir'], 'component')) {
            $buildResults['success'][] = $component['name'] . ' (component)';
        } else {
            $buildResults['failed'][] = $component['name'] . ' (component)';
        }
    }

    // Build plugins
    echo "\n### Building Plugins ###\n";
    foreach ($extensions['plugins'] as $plugin) {
        if (buildExtension($plugin, $config['build_dir'], $config['packages_dir'], 'plugin')) {
            $buildResults['success'][] = $plugin['name'] . ' (' . $plugin['group'] . ' plugin)';
        } else {
            $buildResults['failed'][] = $plugin['name'] . ' (' . $plugin['group'] . ' plugin)';
        }
    }

    // Show build summary
    echo "\n### Build Summary ###\n";
    echo "✓ Successful: " . count($buildResults['success']) . " extensions\n";
    if (count($buildResults['failed']) > 0) {
        echo "✗ Failed: " . count($buildResults['failed']) . " extensions\n";
        foreach ($buildResults['failed'] as $failed) {
            echo "  - $failed\n";
        }
    }

    // Stop if any builds failed
    if (count($buildResults['failed']) > 0) {
        echo "\nERROR: Some extensions failed to build. Aborting.\n";
        return false;
    }

    // Copy the component and plugin ZIPs to dist with versioned names
    echo "\n### Preparing Final ZIPs ###\n";

    // Component
    $component = $extensions['components'][0];
    $componentZip = $config['packages_dir'] . '/' . $component['zip_name'];
    if (!file_exists($componentZip)) {
        echo "ERROR: Built ZIP not found: {$componentZip}\n";
        return false;
    }
    $componentFinalName = 'com_vmmapicon-' . $config['package_version'] . '.zip';
    copy($componentZip, $config['output_dir'] . '/' . $componentFinalName);

    // Plugin
    foreach ($extensions['plugins'] as $plugin) {
        $pluginZip = $config['packages_dir'] . '/' . $plugin['zip_name'];
        if (!file_exists($pluginZip)) {
            echo "ERROR: Built ZIP not found: {$pluginZip}\n";
            return false;
        }
        $pluginFinalName = $plugin['name'] . '-' . $config['package_version'] . '.zip';
        copy($pluginZip, $config['output_dir'] . '/' . $pluginFinalName);
    }

    echo "\n";
    echo "========================================\n";
    echo "  BUILD SUCCESSFUL!\n";
    echo "  Packages created in: {$config['output_dir']}/\n";
    echo "========================================\n\n";

    // Clean up build directory
    cleanDir($config['build_dir']);

    return true;
}

// Run the build
if (php_sapi_name() === 'cli') {
    // Parse command line arguments
    $options = getopt('v:', ['version:']);
    if (isset($options['v'])) {
        $config['package_version'] = $options['v'];
    } elseif (isset($options['version'])) {
        $config['package_version'] = $options['version'];
    }

    // Clean ALL old build artifacts
    echo "Cleaning ALL old build artifacts...\n";
    cleanDir($config['build_dir']);
    cleanDir($config['packages_dir']);

    // Remove old component and plugin package files in dist
    foreach (glob($config['output_dir'] . '/com_vmmapicon-*.zip') as $file) {
        unlink($file);
        echo "Removed old package: " . basename($file) . "\n";
    }
    foreach (glob($config['output_dir'] . '/plg_system_ytvmmapicon-*.zip') as $file) {
        unlink($file);
        echo "Removed old package: " . basename($file) . "\n";
    }

    // Run build
    $success = build($config, $extensions);
    exit($success ? 0 : 1);
} else {
    echo "This script must be run from the command line.\n";
    exit(1);
}

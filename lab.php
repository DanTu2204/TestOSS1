<?php
$pageTitle = 'Lab';
require_once 'includes/header.php';

// Base directory for files
$baseDir = 'files';
$requestDir = isset($_GET['dir']) ? $_GET['dir'] : '';

// Security check to prevent directory traversal
// Remove any attempts to go up directories
$requestDir = str_replace('..', '', $requestDir);
$requestDir = trim($requestDir, '/\\');

$currentDir = $baseDir . ($requestDir ? '/' . $requestDir : '');

// Verify if the directory actually exists
if (!is_dir($currentDir)) {
    $currentDir = $baseDir;
    $requestDir = '';
}

$files = scandir($currentDir);

// Breadcrumb logic
$breadcrumbs = [];
if ($requestDir) {
    $parts = explode('/', $requestDir);
    $path = '';
    foreach ($parts as $part) {
        if ($part) {
            $path .= ($path ? '/' : '') . $part;
            $breadcrumbs[] = ['name' => $part, 'path' => $path];
        }
    }
}
?>

<main class="main-content">
    <div class="container">
        <div class="lab-container" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 class="section-title" style="text-align: left; margin-bottom: 20px; font-size: 24px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <a href="lab.php" style="text-decoration: none; color: #333;">Files</a>
                <?php foreach ($breadcrumbs as $crumb): ?>
                    <span style="color: #999; margin: 0 5px;">/</span> 
                    <a href="lab.php?dir=<?php echo urlencode($crumb['path']); ?>" style="text-decoration: none; color: #667eea; background: #f0f2ff; padding: 2px 8px; border-radius: 4px; font-size: 0.9em;"><?php echo htmlspecialchars($crumb['name']); ?></a>
                <?php endforeach; ?>
            </h2>

            <div class="file-list">
                <?php
                // Display ".." if we are inside a subdirectory
                if ($requestDir) {
                    $parentPath = dirname($requestDir);
                    if ($parentPath == '.') $parentPath = '';
                    
                    echo '<div class="file-item" style="padding: 12px; border-bottom: 1px solid #f5f5f5; transition: background 0.2s;">';
                    echo '<a href="lab.php?dir=' . urlencode($parentPath) . '" style="text-decoration: none; color: #333; display: flex; align-items: center; gap: 15px;">';
                    echo '<span style="font-size: 20px;">🔙</span> <span style="font-weight: 500;">..</span>';
                    echo '</a>';
                    echo '</div>';
                }

                $hasFiles = false;
                foreach ($files as $file) {
                    if ($file == '.' || $file == '..') continue;
                    $hasFiles = true;
                    
                    $filePath = $currentDir . '/' . $file;
                    $relativePath = ($requestDir ? $requestDir . '/' : '') . $file;
                    $isDir = is_dir($filePath);
                    
                    echo '<div class="file-item" style="padding: 12px; border-bottom: 1px solid #f5f5f5; transition: background 0.2s;">';
                    if ($isDir) {
                        echo '<a href="lab.php?dir=' . urlencode($relativePath) . '" style="text-decoration: none; color: #333; display: flex; align-items: center; gap: 15px;">';
                        echo '<span style="font-size: 20px; color: #ffc107;">📁</span> <span style="font-weight: 600;">' . htmlspecialchars($file) . '</span>';
                        echo '</a>';
                    } else {
                        // Link to view the file
                        echo '<a href="' . $baseDir . '/' . htmlspecialchars($relativePath) . '" target="_blank" style="text-decoration: none; color: #555; display: flex; align-items: center; gap: 15px; width: 100%;">';
                        echo '<span style="font-size: 20px; color: #6c757d;">📄</span> <span>' . htmlspecialchars($file) . '</span>';
                        echo '<span style="margin-left: auto; font-size: 11px; color: #fff; background: #999; padding: 2px 6px; border-radius: 3px;">' . strtoupper(pathinfo($file, PATHINFO_EXTENSION)) . '</span>';
                        echo '</a>';
                    }
                    echo '</div>';
                }
                
                if (!$hasFiles) {
                    echo '<p style="padding: 20px; text-align: center; color: #999;">Thư mục trống.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</main>
<style>
    .file-item:hover {
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>

<?php require_once 'includes/footer.php'; ?>

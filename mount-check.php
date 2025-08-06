<?php
$mounts = [
    [
        "label" => "Frontend-Komponente",
        "path" => "/srv/www/joomla/joomla5/components/com_vmmapicon",
        "url"  => "/components/com_vmmapicon/test.php"
    ],
    [
        "label" => "Administrator-Komponente",
        "path" => "/srv/www/joomla/joomla5/administrator/components/com_vmmapicon",
        "url"  => "/administrator/components/com_vmmapicon/test.php"
    ],
    [
        "label" => "Plugin (System)",
        "path" => "/srv/www/joomla/joomla5/plugins/system/ytvmmapicon",
        "url"  => "/plugins/system/ytvmmapicon/test.php"
    ],
];
function checkPath($path): array {
    if (!file_exists($path)) {
        return ["status" => "❌", "message" => "Ordner existiert NICHT"];
    }
    $files = scandir($path);
    $count = count(array_diff($files, [".", ".."]));
    if ($count === 0) {
        return ["status" => "⚠️", "message" => "Ordner ist LEER"];
    }
    return ["status" => "✅", "message" => "$count Datei(en)/Ordner(n)"];
}
?><!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><title>Mount-Check</title>
<style>body{font-family:sans-serif;padding:1em;background:#f9f9f9}h1{color:#333}.ok{color:green}
.warn{color:orange}.fail{color:red}a{font-size:.9em;color:#0366d6}</style></head><body>
<h1>🧩 Docker Mount-Check</h1><ul><?php foreach ($mounts as $mount): $result = checkPath($mount["path"]);
$class = match ($result["status"]) { "✅" => "ok", "⚠️" => "warn", "❌" => "fail", default => "" };
?><li><strong><?= htmlspecialchars($mount["label"]) ?></strong><br>Pfad:
<code><?= htmlspecialchars($mount["path"]) ?></code><br>
<span class="<?= $class ?>"><?= $result["status"] ?> <?= $result["message"] ?></span><br>
<a href="<?= htmlspecialchars($mount["url"]) ?>" target="_blank">🔗 Testdatei aufrufen</a></li><br><?php endforeach; ?>
</ul></body></html>

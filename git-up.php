<?php
if (php_sapi_name() !== 'cli') {
    die("This script can only be run via the command line interface.\n");
}

echo "🔍 Scanning repository status...\n";

// 1. Stage everything so we can read the exact changes accurately
shell_exec("git add .");

// 2. Fetch the current branch name (e.g., "feature/login-page" or "bugfix/issue-42")
$branch = trim(shell_exec("git rev-parse --abbrev-ref HEAD 2>/dev/null"));

// 3. Fetch list of modified, deleted, and newly staged files
$stagedFilesRaw = shell_exec("git diff --cached --name-status");
$files = array_filter(explode("\n", trim($stagedFilesRaw)));

if (empty($files)) {
    echo "ℹ️ No changes detected to commit.\n";
    exit(0);
}

// 4. Group files by their action type (Added, Modified, Deleted)
$added = [];
$modified = [];
$deleted = [];

foreach ($files as $fileLine) {
    if (preg_match('/^([AMD])\s+(.+)$/', trim($fileLine), $matches)) {
        $status = $matches[1];
        $fileName = basename($matches[2]); // Extract only the filename for brevity
        
        if ($status === 'A') $added[] = $fileName;
        if ($status === 'M') $modified[] = $fileName;
        if ($status === 'D') $deleted[] = $fileName;
    }
}

// 5. Generate a semantic prefix based on the branch name
$prefix = 'update';
if (strpos($branch, 'feat') !== false) $prefix = 'feat';
if (strpos($branch, 'fix') !== false || strpos($branch, 'bug') !== false) $prefix = 'fix';
if (strpos($branch, 'doc') !== false) $prefix = 'docs';
if (strpos($branch, 'refactor') !== false) $prefix = 'refactor';

// 6. Build the descriptive message string
$summaryItems = [];
if (!empty($added))    $summaryItems[] = "add " . implode(', ', $added);
if (!empty($modified)) $summaryItems[] = "modify " . implode(', ', $modified);
if (!empty($deleted))  $summaryItems[] = "remove " . implode(', ', $deleted);

$messageBody = implode('; ', $summaryItems);
$finalCommitMessage = "{$prefix}({$branch}): {$messageBody}";

// Enforce a sensible max length limit for the terminal call
if (strlen($finalCommitMessage) > 150) {
    $finalCommitMessage = substr($finalCommitMessage, 0, 147) . '...';
}

echo "📝 Auto-generated message: \"\033[32m{$finalCommitMessage}\033[0m\"\n";

// 7. Securely escape and execute the final sequence
$escapedMessage = escapeshellarg($finalCommitMessage);
passthru("git commit -m {$escapedMessage}", $commitStatus);

if ($commitStatus === 0) {
    echo "→ Pushing changes to remote...\n";
    passthru("git push", $pushStatus);
    if ($pushStatus === 0) {
        echo "✨ Done! Changes pushed successfully.\n";
    }
}

<?php
use App\Core\Session;
$flashes = Session::getFlashes();
foreach ($flashes as $type => $messages):
    $class = match ($type) {
        'success' => 'alert-success',
        'error'   => 'alert-error',
        default   => 'alert-info',
    };
    foreach ($messages as $msg):
?>
    <div class="alert <?= $class ?>" role="alert"><?= e($msg) ?></div>
<?php endforeach; endforeach; ?>

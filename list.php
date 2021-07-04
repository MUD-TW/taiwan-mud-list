<!DOCTYPE HTML>
<html lang="zh-Hant">
    <head>
        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="initial-scale=1, width=device-width" name="viewport">
        <title>臺灣泥巴列表</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h1>臺灣泥巴列表</h1>
            <table class="table table-striped table-responsive text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>名稱</th>
                        <th>位置</th>
                        <th>連接埠</th>
                        <th>狀態</th>
                        <th>人數</th>
                        <th>更新時間</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $muds = json_decode(file_get_contents('muds.json'), true);
    foreach ($muds as $index => $mud) {
        $url = @$mud['url'] ?: "telnet://{$mud['ip']}:{$mud['port']}/";
?>
                    <tr class="<?= $mud['count'] === false ? 'text-danger' : '' ?>">
                        <td class="text-right"><?= $index + 1 ?></td>
                        <td><?= $mud['name'] ?></td>
                        <td><a class="text-reset" href="<?= $url ?>" target="_blank"><?= $mud['ip'] ?></a></td>
                        <td class="text-right"><?= $mud['port'] ?></td>
                        <td class="text-center"><i class="<?= $mud['count'] === false ? 'fas fa-times' : 'far fa-circle' ?>"></i></td>
                        <td class="text-right"><?= ($mud['count'] === false) ? '' : (($mud['count'] === true) ? '-' : $mud['count']) ?></td>
                        <td><?= $mud['time'] ?? '' ?></td>
                    </tr>
<?php
    }
?>
                </tbody>
            </table>
        </div>
    </body>
</html>

<?php //>

function lookup($options, $big5 = true) {
    list('ip' => $ip, 'port' => $port, 'timeout' => $timeout, 'regex' => $regex) = $options;

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['file', '/tmp/error-output.txt', 'a'],
    ];

    $process = proc_open("timeout 12 telnet {$ip} {$port}", $descriptors, $pipes, '/tmp', []);

    sleep($timeout);

    fclose($pipes[0]);

    $content = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    if ($content) {
        if ($big5) {
            $content = mb_convert_encoding($content, 'UTF-8', 'BIG5');
        }

        $content = preg_replace('/(\[([\d;]*)m)/', '', $content);
    }

    proc_close($process);

    return parseCount($content, $regex);
}

function lookupWeb($options) {
    list('regex' => $regex, 'url' => $url) = $options;

    return parseCount(file_get_contents($url), $regex);
}

function parseCount($content, $regex) {
    if ($content) {
        if ($regex) {
            $content = trim(preg_replace($regex, '$1', $content));

            if (strlen($content)) {
                $count = intval($content);

                if ($count >= 0) {
                    return $count;
                }
            }
        }

        return true;
    }

    return false;
}

$muds = json_decode(file_get_contents('muds.json'), true);

usort($muds, function ($a, $b) {
    return strcmp(mb_convert_encoding($a['name'], 'BIG5', 'UTF-8'), mb_convert_encoding($b['name'], 'BIG5', 'UTF-8'));
});

foreach ($muds as &$mud) {
    $mud['count'] = @$mud['port'] ? lookup($mud): lookupWeb($mud);

    if ($mud['count'] !== false) {
        $mud['time'] = date('Y-m-d H:i');
    }
}

file_put_contents('muds.json', json_encode($muds, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

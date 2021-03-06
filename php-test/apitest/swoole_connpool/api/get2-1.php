<?php
/**
 * Created by IntelliJ IDEA.
 * User: chuxiaofeng
 * Date: 17/5/22
 * Time: 下午8:56
 */


$tcp_pool = new \swoole_connpool(\swoole_connpool::SWOOLE_CONNPOOL_TCP);
$r = $tcp_pool->setConfig([
    "host" => "11.11.11.11", // youtube
    "port" => 80,
]);
assert($r === true);
$r = $tcp_pool->createConnPool(1, 1);
assert($r === true);

$timeout = 100;

$timerId = swoole_timer_after($timeout + 100, function() {
    assert(false);
    swoole_event_exit();
});

// 已经FIX timeout 参数有问题
$connId = $tcp_pool->get(100, function(\swoole_connpool $pool, /*\swoole_client*/ $cli) use($timerId) {
    swoole_timer_clear($timerId);
    if ($cli instanceof \swoole_client) {
        assert(false);
    } else {
        echo "timeout";
        assert(true);
    }
    swoole_event_exit();
});
if ($connId === false) {
    assert(false);
    swoole_event_exit();
}


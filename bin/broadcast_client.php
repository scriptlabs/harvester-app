<?php
$port = 9001;
// open socket
echo "Create socket..." . "\n";
$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if (!$sock) {
    echo "ERROR: " . socket_strerror(socket_last_error());
} else {
    echo "Done!" . "\n";
    socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
    $uid = bin2hex(random_bytes(32));
    echo "UID (".strlen($uid)."): " . $uid . "\n";
    $request = [
        'status'    => 'REQUEST',
        'uid'       => $uid,
        'hostname'  => gethostname(),
        'ip4'       => gethostbyname(gethostname().'.local')
    ];
    $request = json_encode($request);
    socket_sendto($sock, $request, strlen($request), 0, '255.255.255.255', $port);
    socket_recv($sock, $buffer, 1024, 0);
    echo "Answer: ". $buffer . "\n";
    if(!empty($buffer) && strpos($buffer, '{')===0) {
        $result = json_decode($buffer, true);
        print_r($result);
    }
}
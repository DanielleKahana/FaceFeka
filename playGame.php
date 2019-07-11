<?php
function notifyNode() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ch, CURLOPT_PORT, 5000);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
//    $pf = array('f'=> $type, 'pid' => $project_id, 'user_from' => $from_user, 'data' => array());
//    foreach($data as $k => $v) {
//        $pf['data'][$k] = $v;
//    }
//    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pf));
    curl_exec($ch);
    curl_close($ch);

}

<?php

$commande_ssh = "curl -i --header 'Referer: https://YOUR_QBIT_LINK_OR_IP' --data 'username=USERNAME&password=PASSWORD' https://YOUR_QBIT_LINK_OR_IP/api/v2/auth/login | grep 'SID='";
$result_ssh = shell_exec($commande_ssh);
// echo $result_ssh;

$pattern = '/SID=([^;]+)/';
preg_match($pattern, $result_ssh, $matches);


if (isset($matches[1])) {
    $sidWithPrefix = 'SID=' . $matches[1];

    $getDl = "curl https://YOUR_QBIT_LINK_OR_IP/api/v2/torrents/info --cookie '".$sidWithPrefix."' | jq '.[] | select(.state | contains(\"downloading\")) | .name'";
    if(!empty($result_ssh = shell_exec($getDl))) {
        echo "<br><br<br><h4>" . $result_ssh . "</h4><br>";
        $getvaleur1 = "curl https://YOUR_QBIT_LINK_OR_IP/api/v2/torrents/info --cookie '".$sidWithPrefix."' | jq '.[] | select(.state | contains(\"downloading\")) | .downloaded'";
        if($valeur1 = shell_exec($getvaleur1)) {
            // echo $valeur1 . "<br>";
        }

        $getvaleur2 = "curl https://YOUR_QBIT_LINK_OR_IP/api/v2/torrents/info --cookie '".$sidWithPrefix."' | jq '.[] | select(.state | contains(\"downloading\")) | .amount_left'";
        if($valeur2 = shell_exec($getvaleur2)) {
            // echo $valeur2 . "<br>";
            $valeurDL = round(($valeur1*100)/($valeur1+$valeur2), 1) . "%" ;
            echo "<br><br<br><h1>" . $valeurDL . "</H1>";
        }
    } else {
        echo "No movie downloading.";
    }
} else {
    echo "Error. Please contact administrator.";
}

?>

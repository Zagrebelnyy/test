<?php
require_once  "phpQuery.php";
function get_content($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}


$url = 'https://www.coingecko.com/en';
$html = phpQuery::newDocument(get_content($url));

$list = $html->find('.coin-table.table-responsive tbody tr');

$itr = 0;
$result = [];
foreach ($list as $item) {
    $result[$itr]['name'] = str_replace("\n", "", pq($item)->find('.coin-name .center a:eq(0)')->text());
    $result[$itr]['price'] = pq($item)->find('.td-price.price span')->text();
    $itr++;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <button class="btn-query">Запрос</button>
    <div class="content">

    </div>
    <script>
        $(function() {
            let coins = <?php echo json_encode($result) ?>;
            console.log(coins);

            $('.btn-query').on('click', function() {


                let tbody = "<tbody>";
                for (let i = 0; i < 65; i++) {
                    let tr = '<tr>';
                    tr += '<td>' + coins[i]['name'] + '</td>';
                    tr += '<td>' + coins[i]['price'] + '</td>';
                    tr += '<td>' + Math.round(new Date().getTime() / 1000) + '</td>';
                    tr += '</tr>';
                    tbody += tr;

                }
                tbody += "</tbody>";
                $('.content').append(`
                    <table border="1" class="table-coins">
                        <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Цена</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        ${tbody}
                    </table>`);

            })

        })
    </script>
</body>

</html>

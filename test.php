<?

define("MARKSIXURL","http://bet.hkjc.com/marksix/index.aspx?lang=ch");

// 建立CURL連線
$ch = curl_init();

// 設定擷取的URL網址
curl_setopt($ch, CURLOPT_URL, constant("MARKSIXURL"));
curl_setopt($ch, CURLOPT_HEADER, false);
//將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
// 執行
$temp=curl_exec($ch);
curl_close($ch);

//echo $temp;
$dom = new DOMDocument();
//從一個字符串加載HTML
@$dom->loadHTML($temp);
$xpath = new DOMXPath($dom);
$xpathResult = $xpath->evaluate("//*[@id='oddsTable']/table/tr[3]/td/table/tr[2]/td/table/tr/td[1]/table/tr[2]/td/table/tr");
Diu
foreach ($xpathResult as $node) {
    $result[] = $dom->saveHTML($node);
}
print_r($result);

?>
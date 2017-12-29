<?
//header("Content-type: text/plain");

header('charset=utf-8');


libxml_use_internal_errors(true);
$html = file_get_contents("http://bet.hkjc.com/marksix/index.aspx?lang=ch");
$html=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
$dom = new DOMDocument();
$dom->loadHtml($html);
$xpath = new DOMXpath($dom);

$xpathResult = $xpath->evaluate('//*[@id="oddsTable"]/table/tr[3]/td/table/tr[2]/td/table');
$mainDiv = $xpathResult->item(0);
$numberTable = $xpath->evaluate('tr/td/table/tr',$mainDiv);
$num =lala('td[1]',$numberTable,$xpath);
$date = lala('td[2]',$numberTable,$xpath);
echo $num;
echo '<br/>';
echo $date;

function lala($path,$source,$xpath)
{
    return $xpath->evaluate($path,$source->item(0))->item(0)->nodeValue;
}
?>
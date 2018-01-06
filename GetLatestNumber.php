<?php
header("Content-type: application/json;charset=utf-8");


libxml_use_internal_errors(true);
$html = file_get_contents("http://bet.hkjc.com/marksix/index.aspx?lang=ch");
$html=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);

$dom = new DOMDocument();
$dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

$xpath = new DOMXpath($dom);

$xpathResult = $xpath->evaluate('//*[@id="oddsTable"]/table/tr[3]/td/table/tr[2]/td/table');

$mainDiv = $xpathResult->item(0);
$numberTable = $xpath->evaluate('tr/td/table/tr',$mainDiv);

//------------------------------------------------------------------------------------
function getContentAfterColon($input)
{
    $input =substr($input, strpos($input, ":") + 1);
    return trim($input);
}

$drawNumber = getContentAfterColon(extractNodeContent('td[1]',$numberTable));
$drawDate = getContentAfterColon(extractNodeContent('td[2]',$numberTable));
$totalTurnover = getContentAfterColon(extractNodeContent('td[3]',$numberTable));

$lastDrawResultPrizeTable = $xpath->evaluate('//*[@id="_ctl0_ContentPlaceHolder1_indexMarkSix_lastDrawResultPrizeTable"]');
$arrayOfPrize = array();
for($i = 0; $i<3;$i++)
{    
    $tmpPrize = extractNodeContent('tr['.($i+2).']/td[2]',$lastDrawResultPrizeTable);
    $tmpUnitPrize= extractNodeContent('tr['.($i+2).']/td[3]',$lastDrawResultPrizeTable);
    $tmpWinningUnit =  extractNodeContent('tr['.($i+2).']/td[4]',$lastDrawResultPrizeTable);
    $tmp = new LastDrawResult($tmpPrize,$tmpUnitPrize,$tmpWinningUnit);
    $arrayOfPrize[$i]=$tmp;
}

//------------------------------------------------------------------------------------
//Obtain the number here.
$drawResultTable = $xpath->evaluate('tr/td/table/tr[2]',$mainDiv);
$arrayOfNumbers = array();

     preg_match_all('/no_([\d].).gif/', $dom->saveHTML($drawResultTable->item(0)), $matches, PREG_OFFSET_CAPTURE);

foreach($matches[1] as $number)
{
    array_push($arrayOfNumbers,$number[0]);
}

$arrayResult = array("Numbers"=>$arrayOfNumbers,"DrawNumber"=>$drawNumber,"DrawDate"=>$drawDate,"TotalTurnOver"=>$totalTurnover,"Prizes"=>$arrayOfPrize);

echo json_encode($arrayResult, JSON_UNESCAPED_UNICODE| JSON_UNESCAPED_SLASHES);


function extractNodeContent($path,$source)
{
    return $GLOBALS['xpath']->evaluate($path,$source->item(0))->item(0)->nodeValue;
}
class LastDrawResult {
    function LastDrawResult($prize,$unitPrize,$winningUnit)
    {
        $this->Prize = $prize;
        $this->UnitPrize = $unitPrize;
        $this->WinningUnit = $winningUnit;
    }
}
?>
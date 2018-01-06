<?php
header("Content-type: application/json;charset=utf-8");


libxml_use_internal_errors(true);
$html = file_get_contents("http://bet.hkjc.com/marksix/index.aspx?lang=ch");
$html=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);

$dom = new DOMDocument();
$dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

$xpath = new DOMXpath($dom);

$xpathResult = $xpath->evaluate('//*[@id="oddsTable"]/table/tr[2]/td/table/tr/td[1]/table');

//print_r($xpathResult->item(0));


$nextDraw = new NextDraw(
                            extractNodeContent('tr[1]/td[2]',$xpathResult),
                            extractNodeContent('tr[2]/td[2]',$xpathResult),
                            extractNodeContent('tr[3]/td[2]',$xpathResult),
                            extractNodeContent('tr[4]/td[2]',$xpathResult),
                            extractNodeContent('tr[5]/td[2]',$xpathResult),
                            extractNodeContent('tr[6]/td[2]',$xpathResult)
);

echo json_encode($nextDraw, JSON_UNESCAPED_UNICODE| JSON_UNESCAPED_SLASHES);


function extractNodeContent($path,$source)
{        
    return $GLOBALS['xpath']->evaluate($path,$source->item(0))->item(0)->nodeValue;
}
class NextDraw {
    function NextDraw($nextDrawNumber,$drawDate,$stopSellingTime,$turnOver,$jackpot,$firstDivisionPrizeFund)
    {
      $this->NextDrawNumber = $nextDrawNumber;
      $this->DrawDate = $drawDate;
      $this->StopSellingTime = $stopSellingTime;
      $this->Turnover = $turnOver;
      $this->Jackpot = $jackpot;
      $this->FirstDivisionPrizeFund = $firstDivisionPrizeFund;
    }
}
?>
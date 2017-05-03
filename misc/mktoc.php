<?php
ini_set('memory_limit','300M');
setlocale(LC_ALL, 'en_GB.UTF-8');

$toclines = [];
$toclines[] = "<ul class='toc' id='toc22'>".PHP_EOL;

$toclines[] = "<li><a href=\".U0_0_1\"><b>I. MERKEZI VE AŞKIN EVRENLER</b></a>".PHP_EOL;
$toclines[] = " <ul>".PHP_EOL;
$toclines = array_merge($toclines, add_papers(0, 31));
$toclines[] = " </ul>".PHP_EOL;
$toclines[] = "</li>".PHP_EOL;

$toclines[] = "<li><a href=\".U32_0_1\"><b>II. YEREL EVRENLER</b></a>".PHP_EOL;
$toclines[] = " <ul>".PHP_EOL;
$toclines = array_merge($toclines, add_papers(32, 56));
$toclines[] = " </ul>".PHP_EOL;
$toclines[] = "</li>".PHP_EOL;

$toclines[] = "<li><a href=\".U57_0_1\"><b>III. URANTIA’NIN TARIHI</b></a>".PHP_EOL;
$toclines[] = " <ul>".PHP_EOL;
$toclines = array_merge($toclines, add_papers(57, 119));
$toclines[] = " </ul>".PHP_EOL;
$toclines[] = "</li>".PHP_EOL;

$toclines[] = "<li><a href=\".U120_0_1\"><b>IV. İSA’NIN HAYATI VE ÖĞRETILERI</b></a>".PHP_EOL;
$toclines[] = " <ul>".PHP_EOL;
$toclines = array_merge($toclines, add_papers(120, 196));
$toclines[] = " </ul>".PHP_EOL;
$toclines[] = "</li>".PHP_EOL;

$toclines[] = "</ul>".PHP_EOL;
file_put_contents("text/22/toc.html", $toclines);

function add_papers($i_min, $i_max) {
   $retlines = [];
   for ($i = $i_min; $i <= $i_max; $i++) {
      $papertitle = get_paper_title($i);
      if ($i > 0) $papertitle = $i.". ".$papertitle;
      $retlines[] = "  <li><a class=\"U".$i."_0_1\" href=\".U".$i."_0_1\"><b>".$papertitle."</b></a>".PHP_EOL;
      $retlines[] = "    <ul>".PHP_EOL;
      $retlines = array_merge($retlines, add_sections($i));
      $retlines[] = "    </ul>".PHP_EOL;
      $retlines[] = "  </li>".PHP_EOL;
   }
   return $retlines;
}

function get_paper_title($i) {
   if ($i == 0)
      $title = "Önsöz";
   else {
      $filename = sprintf("u8/turnys2011_exemplar_p%03d.u16", $i);
      $line = trim(fgets(fopen($filename, 'r')));
      $title = explode("{{{br}}}", $line, 2);
      $title = $title[1];
   }
   return $title;
}

function add_sections($i) {
   $paperfile = sprintf("text/22/p%03d.html", $i);
   $lines = file($paperfile);
   $retlines = [];
   foreach($lines as $line)
      if (preg_match('/<h4>(<a class="U\d{1,3}_\d{1,2}_0" href="\.U\d{1,3}_\d{1,2}_0">.*<\/a>)<\/h4>/u', $line, $matches))
         $retlines[] = "      <li>".$matches[1]."</li>".PHP_EOL;
   return $retlines;
}
?>

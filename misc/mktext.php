<?php
ini_set('memory_limit','300M');

system("rm -rf text ; mkdir -p text/22");

$languages = ['English' => 0,
              'Russian' => 2,
              'Bulgarian' => 3,
              'German' => 4,
              'Dutch' => 6,
              'Estonian' => 7,
              'Finnish' => 8,
              'French' => 9,
              'Greek' => 10,
              'Hungarian' => 11,
              'Italian' => 12,
              'Korean' => 13,
              'Lithuanian' => 14,
              'Polish' =>  15,
              'Portuguese' => 16,
              'Romanian' => 17,
              'SpanishAmericas' => 18,
              'SpanishEuropean' => 19,
              'Swedish' => 20];

$metrics = json_decode(file_get_contents("metrics-short.json"));

for ($i = 0; $i <= 196; $i++) {
   $pmetrics = $metrics[$i];
   $filename = sprintf("u8/turnys2011_exemplar_p%03d.u16", $i);
   $out = sprintf("text/22/p%03d.html", $i);
   $lines = file($filename);
   $lines = array_slice($lines, 1);
   $olines = [];
   foreach($lines as $linenum => $line) {
       $line = convert_tags($line);
       $line_data = explode("] ", $line, 2);
       if (isset($line_data[1])) {
          if (isset($pmetrics[$linenum])) {
             list($sec, $par, $type) = $pmetrics[$linenum];
             if ($type == 'TEXTP')
                $text = '<p><a class="U'.$i.'_'.$sec.'_'.$par.'" href=".U'.$i.'_'.$sec.'_'.$par.'"><sup>'.$i.':'.$sec.'.'.$par.'</sup></a> ¶ ' . $line_data[1];
             elseif ($type == 'TEXT')
                $text = '<p><a class="U'.$i.'_'.$sec.'_'.$par.'" href=".U'.$i.'_'.$sec.'_'.$par.'"><sup>'.$i.':'.$sec.'.'.$par.'</sup></a> ' . $line_data[1];
             elseif ($type == 'HEADER')
                $text = '<h4><a class="U'.$i.'_'.$sec.'_'.$par.'" href=".U'.$i.'_'.$sec.'_'.$par.'">'.rtrim($line_data[1]).'</a></h4>'.PHP_EOL;
             else
                printf("UNKNOWN TYPE IN METRICS! File: %s, linenum=%d\n", $filename, $linenum);
             $olines[] = $text;
          } else
             printf("CORRUPTED METRICS! File: %s, linenum=%d\n", $filename, $linenum);
       } else
          printf("CORRUPTED DATA! File: %s, linenum=%d\n", $filename, $linenum);
   }
   file_put_contents($out, $olines);
}

function convert_tags($line) {
   $search =  ['/{i{i{/u',
               '/}i}i}/u',
               '/{h{h{/u',
               '/}h}h}/u',
               '/{{F[23]{{/u',
               '/{[ru]{[ru]{/u',
               '/}[su]}[su]}/u',
               '/{[cx]{[cx]{[^}]*}[cx]}[cx]}/u',
               '/{n{n{[^}]*}n}n}/u',
               '/ */u'];
   $replace = ['<i>',
               '</i>',
               '<sup>',
               '</sup>',
               '',
               '',
               '',
               '',
               '',
               ''];
   return preg_replace($search, $replace, $line);
}
?>

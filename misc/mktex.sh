#!/bin/bash

#
# Generate TeX source tex/p???.tex files from UBDS exemplar and metric files.
#

METRIC_FILE=metrics/exemplarmap.js
DATA_DIR=u8-notags
FILE_PREFIX=turnys2011_exemplar

./convert-tags.sh

declare -a parlist lineid linetype
declare -i sec i paridx linenum

rm -rf tex ; mkdir tex
#for ((i = 1; i <= 2; i++));
for ((i = 0; i <= 123; i++));
do
   p=$(printf "%03d" $i)
   parlist=($(sed -ne "s%^/\* Paper${i} \*/  *\[\(.*\)\],*%\1%p" ${METRIC_FILE} | sed -e "s%,%%g"))
   numsec=${#parlist[@]}
   echo "paper $p: ($numsec sections)"
   linenum=0
   lineid=()
   linetype=()
   for ((sec = 0; sec < $numsec; sec++));
   do
      for ((paridx = 0; paridx <= parlist[$sec]; paridx++));
      do
         if ((i == 0 && sec == 12 && paridx == 10)) ; then
            linetype[$linenum]="sectiontitle"
         fi
         if ((paridx == 0)) ; then
            linetype[$linenum]="sectiontitle"
            if ((sec == 0)) ; then
               linetype[$linenum]="papertitle"
            fi
         fi
         if ((i == 139 && sec > 9)) ; then
            lineid[$linenum]="p$p $((sec+1)):${paridx}"
         else
            lineid[$linenum]="p$p ${sec}:${paridx}"
         fi
         ((linenum++))
      done
   done
   linenum=0
   >tex/p${p}.tex
   while read -r line
   do
      text=$(echo -E "$line" | sed -e "s%^\[[0-9][0-9]*\] %%")
      if [ "${linetype[$linenum]}" = "papertitle" ] ; then
         if ((i==0)) ; then
            echo -E "\uforeword" >> tex/p${p}.tex
         else
            papertitle=$(echo -E "$text" | sed -ne "s%^[0-9][0-9]*. Makale..\(.*\)$*$%\1%p")
            echo -E "\upaper{$i}{$papertitle}" >> tex/p${p}.tex
         fi
      else
         if [ "${linetype[$linenum]}" = "sectiontitle" ] ; then
            echo -E "\usection{$text}" >> tex/p${p}.tex
         else
            if ((i == 144)) ; then
               if ((linenum == 26 || linenum == 28 || linenum == 30 || linenum == 32 || linenum == 34 || linenum == 61 || linenum == 63 || linenum == 65 || linenum == 67 || linenum == 70 || linenum == 72 || linenum == 74 || linenum == 76 || linenum == 78 || linenum == 80 || linenum == 83 || linenum == 85 || linenum == 87 || linenum == 89 || linenum == 91 || linenum == 93 || linenum == 95 || linenum == 97 || linenum == 99 || linenum == 101 || linenum == 103 || linenum == 105 || linenum == 107 || linenum == 110 || linenum == 112 || linenum == 114 || linenum == 116 || linenum == 118 || linenum == 120 || linenum == 122 || linenum == 124 || linenum == 126 || linenum == 128 || linenum == 130 || linenum == 132 || linenum == 134 || linenum == 136 || linenum == 138 || linenum == 141 || linenum == 143 || linenum == 145 || linenum == 147 || linenum == 149 || linenum == 151 || linenum == 153 || linenum == 155 || linenum == 157 || linenum == 159)) ; then
                    echo -nE "\vs ${lineid[$linenum]} \hsetoff ${text}" >> tex/p${p}.tex
               else
                    echo -nE "\vs ${lineid[$linenum]} ${text}" >> tex/p${p}.tex
               fi
            else
                echo -nE "\vs ${lineid[$linenum]} ${text}" >> tex/p${p}.tex
            fi
            sec=$(echo -E "${lineid[$linenum]}" | sed -ne "s%^p[0-9][0-9]* \([0-9][0-9]*\):.*%\1%p")
            par=$(echo -E "${lineid[$linenum]}" | sed -ne "s%^p[0-9][0-9]* [0-9][0-9]*:\([0-9][0-9]*\)%\1%p")
            echo >> tex/p${p}.tex
            if ((i == 31 && linenum == 90)) ; then
                echo -E "\separatorline" >> tex/p${p}.tex
            fi
            if ((i == 56 && linenum == 90)) ; then
                echo -E "\separatorline" >> tex/p${p}.tex
            fi
            if ((i == 119 && linenum == 69)) ; then
                echo -E "\separatorline" >> tex/p${p}.tex
            fi
            if ((i == 120 && linenum == 39)) ; then
                echo -E "\separatorline" >> tex/p${p}.tex
            fi
            if ((i == 134 && linenum == 68)) ; then
                echo -E "\separatorline" >> tex/p${p}.tex
            fi
            if ((i == 144)) ; then
               if ((linenum == 24 || linenum == 34 || linenum == 59 || linenum == 159)) ; then
                   echo -E "\vsetoff" >> tex/p${p}.tex
               fi
               if ((linenum == 68 || linenum == 81 || linenum == 93 || linenum == 108 || linenum == 126 || linenum == 139)) ; then
                   echo -E "\separatorline" >> tex/p${p}.tex
               fi
            fi
            if ((i == 196 && linenum == 76)) ; then
                echo -E "\separatorline" >> tex/p${p}.tex
            fi
         fi
      fi
      ((linenum++))
   done < ${DATA_DIR}/${FILE_PREFIX}_p${p}.u16
done

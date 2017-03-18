<?php
include_once("../../config.php");
   function toImgArray($text)
     {
                 $imgs = Array();
                 $i=0;

                 $temp = explode("/#imgsepara##/", $text);
                 foreach ($temp as $img)
                 {

                    if($img!="")
                    {

                         $temp3 = preg_split("/\.([^\.]+)$/", $img);
                         preg_match("/\.([^\.]+)$/", $img, $temp2);
        if(sizeOf($temp2)>0 && sizeOf($temp3)>0)
        {
                         $imgs[$i][0]=SERVER_URL."public/files/".$temp3[0];
                         $imgs[$i][1]=$temp2[0];
        }
                    }
                    $i++;
                 }

        return $imgs;
     }

if(isset($_GET['array']))
{
     $ar=toImgArray($_GET['array']);
        unlink($ar[0][0].$ar[0][1]);

//     unlink(SERVER_URL."public/images/".substr($_GET['array'], 0, -1));

}
?>

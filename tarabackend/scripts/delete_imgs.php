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
                         $imgs[$i][0]=SERVER_URL."public/images/".$temp3[0];
                         $imgs[$i][1]=$temp2[0];
        }
                    }
                    $i++;
                 }

        return $imgs;
     }

     function dirList ($directory)
        {

            // create an array to hold directory list
            $results = array();

            // create a handler for the directory
            $handler = opendir($directory);

            // keep going until all files in directory have been read
            while ($file = readdir($handler)) {

                // if $file isn't this directory or its parent,
                // add it to the results array
                if ($file != '.' && $file != '..')
                    $results[] = $file;
            }

            // tidy up: close the handler
            closedir($handler);

            // done!
            return $results;

        }
if(isset($_GET['array']))
{
     $ar=toImgArray($_GET['array']);
        unlink($ar[0][0].$ar[0][1]);
    $list = dirList(SERVER_URL."public/images");
    foreach($list as $l)
    {
       if(strrpos($l, $ar[0][0]))
        unlink($l);
    }
   

//     unlink(SERVER_URL."public/images/".substr($_GET['array'], 0, -1));

}
?>

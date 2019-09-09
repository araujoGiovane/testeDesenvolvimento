
<?php 
        
    $url = $_POST["urlPage"]; //receive the URL address
    $urlString = file_get_contents($url); //get the data from url in str format
    
    //here and bellow i take how many occurrence of <link we have
    $h0 = substr_count($urlString, '<link type=');
    $h1 = substr_count($urlString, '<link href=');
    $h2 = substr_count($urlString, '<link charset=');
    $h3 = substr_count($urlString, '<link crossorigin=');
    $h4 = substr_count($urlString, '<link hreflang=');
    $h5 = substr_count($urlString, '<link media=');
    $h6 = substr_count($urlString, '<link rel=');
    $h7 = substr_count($urlString, '<link rev=');
    $h8 = substr_count($urlString, '<link sizes=');
    $h9 = substr_count($urlString, '<link target=');
    $totalCss = $h0+$h1+$h2+$h3+$h4+$h5+$h6+$h7+$h8+$h9;//here i get the sum of all possible matchs above, ill use later
    
    $countHttps = substr_count($urlString, 'href="https://'); //count how many https:// in href occurrence we have
    $countHttp = substr_count($urlString, 'href="http://'); // same here, but with http:// instead
    $totalOccurrenceHttp = $countHttp + $countHttps; //here i took the total amount of occurrences
    
    $domain = parse_url($url, PHP_URL_HOST); //take just the domain
    $linkIntType1 = substr_count($urlString, 'href="http://'.$domain);//here i took the internal link in case of site is HTTP, i cocatenated the domain with protocol for make sure that the fetch is guaranted
    $linkIntType2 = substr_count($urlString, 'href="https://'.$domain);//i do the same here, but with HTTPS instead
    $linkIntWithProtocol = $linkIntType1 + $linkIntType2;  //here i get all internal links with HTTP or HTTPS before it
    $linkIntType3 = substr_count($urlString, 'href="./');//here i proceed with the fetch of internal thta dont use the base url in source code
    $linkIntType4 = substr_count($urlString, 'href="/');//here too
    $totalInternalLinks = $linkIntType1+$linkIntType2+$linkIntType3+$linkIntType4;//here i get the of the possible number of internal links
    
    preg_match("/<title>(.*)<\/title>/i", $urlString, $pageTitle);//here i fetch the title
    
    $htmlFourCheck = substr_count($urlString, '<!DOCTYPE HTML PUBLIC');//here i do a checker for validate if the version of HTML is 4
    $htmlFiveCheck = substr_count($urlString, '<!DOCTYPE html>');//same here but for HTML 5 instead
    $xhtmlCheck =substr_count($urlString, '<!DOCTYPE html PUBLIC');//and XHTML here
    
    if ($htmlFourCheck == 1){    // condition for validate the HTML 4 
        $html = "HTML 4";
    }
    if ($htmlFiveCheck == 1){    // condition for validate the HTML 5
        $html = "HTML 5";
    }
    if ($xhtmlCheck == 1){    // condition for validate the XHTML 
        $html = "XHTML";
    }
    if ($htmlFiveCheck + $htmlFourCheck + $xhtmlCheck == 0){   //if either checker of HTML 4 or and 5 are 0 than i assume that the HTML version is older, i dont find a safe way to check the older version
        $html = "The HTML version is older than HTML 4";
    }
    
    //now the check for external links
    $removeCssFromFinalCheck = $totalOccurrenceHttp - $totalCss;
    $totalExternalLinks = $removeCssFromFinalCheck - $linkIntWithProtocol;
    $totalAbsoluteExternalLinks = abs($totalExternalLinks);
       
    
    ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body style="background-color: darkgray;"> 
        <form action="index.php">
        <div style=" position: absolute; top: 25%; left: 35%; height: 200px; width: 400px; background-color: lightgrey; box-shadow: 5px 5px">
            <div style="background-color: lightgrey; overflow: hidden; max-width: 350px; max-height: 20px"> <?php echo "Analysing  $url</br>";?> </div>
            <div style="background-color: lightslategray"> <?php echo "HTML Version:  $html</br>";?> </div>
            <div style="background-color: lightgrey"> <?php echo "Page Title:  $pageTitle[1]</br>";?> </div>
            <div style="background-color: lightslategray"> <?php echo "External Links:  $totalAbsoluteExternalLinks</br>";?> </div>
            <div style="background-color: lightgrey"> <?php echo "Internal Links:  $totalInternalLinks</br>";?> </div>
            <input type="submit" value="Return" style=" position: absolute; top: 61%; right: 7%; height: 40px; width: 100px; font-size: 25px; box-shadow: 5px 5px">
        </div>
    </body>
</html>



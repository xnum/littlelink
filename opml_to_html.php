#!/usr/bin/php

<?php
# From: https://gist.github.com/anthonygelibert/6e533280534ccafb74c2a99fc35f06ea
# Forked and edit to fit tiny tiny rss exported opml.
function usage()
{
    return "Usage: php convert.php /path/to/opml_file.opml > output.html\r\n";
}

# Print usage if need be.
if(count($argv) < 2) die(usage());

# Grab the file path.
$f = $argv[1];

# Load it into a SimpleXML.
$xml = simplexml_load_file($f);

# Output buffer
$out = '';
$isFirstSection = true;

# Run through the nodes in the OPML and buffer the Netscape output
foreach($xml->xpath("//outline") as $outline )
{
    $title = htmlspecialchars($outline['text'], ENT_QUOTES);
    $feed = htmlspecialchars($outline['htmlUrl']);
    if($feed)
    {
        $out .= "\n\t" . '<DT><A HREF="' . str_replace("http://", "https://", $feed) . '">' . $title . '</A>';
    }
    else
    {
        if ($isFirstSection) {
            $isFirstSection = false;
        } else {
            $out .= "</DL>\n";
        }
        $out .= "<DT><H3>$title</H3><DL>";
    }
}

$out .= "\n"

?>

<!DOCTYPE NETSCAPE-Bookmark-file-1>
<HTML>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
<TITLE>My RSS Feed List</TITLE>
<H1>My RSS Feed List</H1>
<?php echo $out; ?>


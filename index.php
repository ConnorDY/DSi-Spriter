<?php
function RGB2hex($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return $color;
}

if (isset($_GET['file']))
{
	if (file_exists('sprites/'.$_GET['file'].'.png'))
	{
		//Load Image
		$avatar = imagecreatefrompng('sprites/'.$_GET['file'].'.png');
		
		//Set Mode
		$loaded = true;
	}
	else $loaded = false;
}
else $loaded = false;

//Create Canvas
for ($y = 1; $y <= 16; $y += 1)
{
	for ($x = 1; $x <= 16; $x += 1)
	{
		if (!$loaded)
		{
			$colour[$x][$y] = 'FFF';
		}
		else
		{
			$rgb = imagecolorat($avatar, $x-1, $y-1);
			
			$colours = imagecolorsforindex($avatar, $rgb);
			
			$colour[$x][$y] = RGB2hex($colours['red'], $colours['green'], $colours['blue']);
		}
	}
}

//Include Array
include('colours.php');
?>
<html>
	<head>
		<title>DSi Sprite Creator</title>
		<meta name="viewport" content="width=240" />
		<link rel="stylesheet" href="common.css" type="text/css" />
		
		<script type="text/javascript">
			function setPixel(id)
			{
				document.getElementById('pixelf'+id).value = '#'+currentColour;
				
				document.getElementById('pixel'+id).style.background = '#'+currentColour;
				document.getElementById('pixeld'+id).style.background = '#'+currentColour;
				document.getElementById('pixeln'+id).style.background = '#'+currentColour;
			}
			
			function setColour(colour)
			{
				currentColour = colour;
				
				document.getElementById('current').style.background = '#'+currentColour;
				
				document.getElementById('hexcode').innerHTML = '#'+currentColour;
			}
			
			function getPixel(id)
			{
				return document.getElementById(id).style.background;
			}
			
			function saveAvatar()
			{
				updateName();
				document.forms['form'].submit();
			}
			
			function updateName()
			{
				document.getElementById('nameR').value = document.getElementById('nameF').value;
			}
			
			var currentColour = '000';
		</script>
	</head>
	<body>
		
		<div id="topscreen">
			<table id="grid">
				<form id="form" action="save.php" method="post">
				<?php
				$i = 1;
				for ($y = 1; $y <= 16; $y += 1)
				{
					echo "<tr>";
					for ($x = 1; $x <= 16; $x += 1)
					{
						echo "
							<td 
								id='pixel".$x."_".$y."' 
								class='pixel' 
								onClick='setPixel(\"".$x."_".$y."\")' 
								style='background:#".$colour[$x][$y]."'
							>
							</td>
							<input 
								id='pixelf".$x."_".$y."' 
								name='p".$i."' 
								value='#".$colour[$x][$y]."' 
								type='hidden'
							/>
						";
						$i += 1;
					}
					echo "</tr>";
				}
				?>
					<input type="hidden" id="nameR" name="nameR" value="1" />
				</form>
			</table>
			<div id="rt">
				<br/>
				Avatar<br/>
				Preview<br/>
				<br/>
				<div id="previewd">
					<table border="0" style="border-collapse:collapse">
						<?php
						for ($y = 1; $y <= 16; $y += 1)
						{
							echo "<tr>";
							for ($x = 1; $x <= 16; $x += 1)
							{
								echo "
									<td
										class='dpixel' 
										id='pixeld".$x."_".$y."' 
										style='background:#".$colour[$x][$y]."'
									>
									</td>
								";
							}
							echo "</tr>";
						}
						?>
					</table>
				</div>
				<br/>
				Name:<br/>
				<input type="text" id="nameF" value="" style="width:40px" onBlur="updateName()">
				<input type="button" onclick="saveAvatar()" value="Save" />
			</div>
		</div>
		<div id="bottomscreen">
			<table id="colours">
				<?php
				$i = 0;
				
				for ($y = 1; $y <= 16; $y += 1)
				{
					echo "<tr>";
					for ($x = 1; $x <= 16; $x += 1)
					{
						echo "
							<td 
								class='cpixel' 
								onClick='setColour(\"".$colours[$i]."\")' 
								style='background:#".$colours[$i]."'
							>
							</td>
						";
						$i += 1;
					}
					echo "</tr>";
				}
				?>
			</table>
			<div id="rb">
				<br/>
				Current<br/>
				Colour<br/>
				<br/>
				<div id="current" style="background:#000000"></div>
				<span id="hexcode">
					#000000
				</span>
				<br/><br/>
				<table id="grayscale" style="margin:auto">
				<?php
				$i = 0;
				
				for ($y = 1; $y <= 2; $y += 1)
				{
					echo "<tr>";
					for ($x = 1; $x <= 5; $x += 1)
					{
						echo "
							<td 
								class='cpixel' 
								onClick='setColour(\"".$gcolours[$i]."\")' 
								style='background:#".$gcolours[$i]."'
							>
							</td>
						";
						$i += 1;
					}
					echo "</tr>";
				}
				?>
				</table>
			</div>
		</div>
	</body>
</html>
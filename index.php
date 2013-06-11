<!-- How to check if the request came from mobile or computer?
       - MobileESP
-->

<?php
	// 1. 데이터베이스 서버에 접속
	$link=mysql_connect('localhost', 'root', '1l0v2ujin');
	
	if(!$link)
		die('Could not connect: '.mysql_error());

	// 2. 데이터베이스 선택
	mysql_select_db('waterthepot');
	mysql_query("set session character_set_connection=utf8;");
	mysql_query("set session character_set_results=utf8;");
	mysql_query("set session character_set_client=utf8;");

	// 메인 리스트 관련 테이블 조회
	if(!empty($_GET['id']))
	{	
		$sql="SELECT * FROM plant WHERE id = ".$_GET['id'];
		$result = mysql_query($sql);
		$topic = mysql_fetch_assoc($result);
	}
	
	// 이미지 갤러리 관련 테이블 조회
	if (!empty($_GET['img_id']))
	{
		$sql="SELECT * FROM img_detail WHERE plant_id = ".$_GET['id']. " and img_id = ".$_GET['img_id'];
		$img_result = mysql_query($sql);
		$img_description = mysql_fetch_assoc($img_result);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>Water The Pot</title>
		<meta name="description" content="" />
		<meta name="author" content="Keunyop" />

		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
		
		<style type="text/css">
			body
			{
				font-size: 0.8em;
				font-family: dotum;
				line-heigh: 1.6em;
                                width: 1210px;
				margin:0 auto;
			}
			header
			{
				border-bottom: 1px solid #ccc;
				padding: 20px 0;
			}			
			#toolbar
			{
				padding: 10px;
				float: right;
			}			
			nav 
			{
				float: left;
				margin-right: 20px;
				min-height: 500px;
				border-right: 1px solid #ccc;
			}			
			nav ul
			{
				list-style: none;
				padding-left: 0;
				padding-right: 20px;
			}
			article
			{
				width: 800px;
				float: left;
			}
			#rightSidebar
			{
				float: right;				
			}
			footer {
				clear: both;
			}
			a
			{
				text-decoration: none;
			}
			a:link, a:visited {
				color: #333;
			}
			a:hover, a:active {
				color: #f60;
			}
			hi {
				font-size: 1.4em;
			}
		</style>
		
		<?
			/**
			 *   TOTAL, TODAY COUNTER	
			 **/

			$totalCountFile="totalCounter.data";
			$todayCountFile="todayCounter.data";
			$totalCountValue=0;
			$todayCountValue=0;

			function readTotalCountFile()
			{
				global $totalCountFile, $totalCountValue;
				
				if (file_exists($totalCountFile))
				{
					$fp = fopen($totalCountFile, 'r');
					$buf = fread($fp, filesize($totalCountFile));
					fclose($fp);
					$totalCountValue=intval($buf);
				}
			}

			function readTodayCountFile()
			{
				global $todayCountFile, $todayCountValue;

				if (file_exists($todayCountFile))
				{
					$fp = fopen($todayCountFile, 'r');
					$buf = fread($fp, filesize($todayCountFile));
					fclose($fp);
					$todayCountValue=intval($buf);
				}
			}

			function writeTotalCountFile()
			{
				global $totalCountFile, $totalCountValue;
				$fp = fopen($totalCountFile, 'w');
				fwrite($fp, $totalCountValue);
				fclose($fp);
			}

			function writeTodayCountFile()
			{
				global $todayCountFile, $todayCountValue;
				$fp = fopen($todayCountFile, 'w');
				fwrite($fp, $todayCountValue);
				fclose($fp);
			}

			function displayTotalCount()
			{
				global $totalCountValue;
				readTotalCountFile();
				$totalCountValue = $totalCountValue+1;
				writeTotalCountFile();
				echo $totalCountValue;
			}

			function displayTodayCount()
			{
				global $todayCountValue;
				readTodayCountFile();
				$todayCountValue = $todayCountValue + 1;
				writeTodayCountFile();
				echo $todayCountValue;
			}
		?> 
		
		<!-- 이미지 갤러리 시작 -->
		<link rel="stylesheet" type="text/css" href="images/gallery/engine/css/slideshow.css" media="screen" />
		<style type="text/css">.slideshow a#vlb{display:none}</style>
		<script type="text/javascript" src="images/gallery/engine/js/mootools.js"></script>
		<script type="text/javascript" src="images/gallery/engine/js/visualslideshow.js"></script>
		<!-- 이미지 갤러리 끝 -->
	</head>

	<body id="body" style="background-image: url('images/background.png'); background-repeat:repeat-x">
		<div>
			<header>
				<h1><a href="index.php?id=1"><img src="images/waterthepotlogo.png" alt="Water The Pot Logo" border=0 height=60></a></h1>
			</header>

			<nav>
				<ul>
					<?php
						$sql="select id, name from plant";
						$result=mysql_query($sql);
						while($row=mysql_fetch_assoc($result)) {
						echo "
						<li><h3>
						<a href=\"?id={$row['id']}\">{$row['name']}</a></h3></li>";
						}
					?>
				</ul>

			<br><br><br>
			TODAY: <? displayTodayCount(); ?> <br>
                        TOTAL: <? displayTotalCount(); ?>

			</nav>

			<article>
				<?php
					if(!empty($topic)){
				?>
				
				<h2><?=$topic['name']?></h2>

				<div class="description">
					<?=$topic['description']?>
					<br><br>
					<?=$topic['image']?>
					<br><br>
			
					<!-- 이미지 갤러리 시작 -->
					<div id="show" class="slideshow" style="float:left">
						<div class="slideshow-images">
							<a href="?id=1&img_id=1"><img id="slide-0" src="images/gallery/data/images/20130402_01.jpg" alt="2013-04-02" title="2013-04-02" /></a>
							<a href="?id=1&img_id=2"><img id="slide-1" src="images/gallery/data/images/20130402_02.jpg" alt="2013-04-02" title="2013-04-02" /></a>
							<a href="?id=1&img_id=3"><img id="slide-2" src="images/gallery/data/images/20130531.jpg" alt="2013-05-31" title="2013-05-31" /></a>
							<a href="?id=1&img_id=4"><img id="slide-3" src="images/gallery/data/images/20130608_01.jpg" alt="2013-06-08" title="2013-06-08" /></a>
							<a href="?id=1&img_id=5"><img id="slide-4" src="images/gallery/data/images/20130608_02.jpg" alt="2013-06-08" title="2013-06-08" /></a>
							<a href="?id=1&img_id=6"><img id="slide-5" src="images/gallery/data/images/20130608_03.jpg" alt="2013-06-08" title="2013-06-08" /></a>
						</div>
						<div class="slideshow-thumbnails">
							<ul>
								<li><a href="#slide-0"><img src="images/gallery/data/thumbnails/20130402_01.jpg" alt="2013-04-02" /></a></li>
								<li><a href="#slide-1"><img src="images/gallery/data/thumbnails/20130402_02.jpg" alt="2013-04-02" /></a></li>
								<li><a href="#slide-2"><img src="images/gallery/data/thumbnails/20130531.jpg" alt="2013-05-31" /></a></li>
								<li><a href="#slide-3"><img src="images/gallery/data/thumbnails/20130608_01.jpg" alt="2013-06-08" /></a></li>
								<li><a href="#slide-4"><img src="images/gallery/data/thumbnails/20130608_02.jpg" alt="2013-06-08" /></a></li>
								<li><a href="#slide-5"><img src="images/gallery/data/thumbnails/20130608_03.jpg" alt="2013-06-08" /></a></li>
							</ul>
						</div>
					</div>
					<div style="float:left; margin-left: 10px">
						<textarea readonly="readonly" style="height:360px; width:300px; resize:none; overflow:hidden">
						<?php
							if(!empty($img_description)){
						?>
						<?=$img_description['created']?>
						
						<?=$img_description['description']?>
						<?php
							}
							else
							{
						?>
							<< 이미지를 클릭하면 설명을 볼수 있습니다.
						<?php
							}
						?>
						</textarea>
					</div>
					<!-- 이미지 갤러리 끝 -->
					
					<br><br>
					<?=$topic['video']?>
					<br><br>
					<video id="player1" width="600" height="360" controls>
						<source src="videos/sample_mpeg4.mp4" />
					</video>
					<!-- http://daoudev.tistory.com/29 -->
				</div>

				<?php
				}
				?>
		
				<div id="disqus_thread"></div>

			</article>
			
			<!-- RIGHT SIDEBAR -->
			<!-- <div id="rightSidebar">
				<table border=1 width=250 height=300>
					<tr>
						<td align="center"> google adsense 1 </td>
					</tr>
				</table>
				<table border=1 width=250 height=300>
                                        <tr>
                                                <td align="center"> google adsense 2 </td>
                                        </tr>
                                </table>
				<table border=1 width=250 height=300>
                                        <tr>
                                                <td align="center"> google adsense 3 </td>
                                        </tr>
                                </table>
			</div> -->

   		</div>
	


		<script type="text/javascript">
        		/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
       			var disqus_shortname = 'waterthepot'; // required: replace example with your forum shortname

        		/* * * DON'T EDIT BELOW THIS LINE * * */
        		(function()
			{
            			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            			dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        		})();
    		</script>
    		<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    		<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
		
		<script type="text/javascript">
    			/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    			var disqus_shortname = 'waterthepot'; // required: replace example with your forum shortname

    			/* * * DON'T EDIT BELOW THIS LINE * * */
    			(function ()
			{
        			var s = document.createElement('script'); s.async = true;
        			s.type = 'text/javascript';
        			s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    			}());
    		</script>
		<script src="http://www.zetsense.com/zetsense/zetsense.html?user=samurae83" type="text/javascript" charset="utf-8"></script>
	</body>
</html>

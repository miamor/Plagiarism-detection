<? error_reporting(E_ERROR | E_PARSE);
include 'func.php';

if (isset($_GET['do']) && $_GET['do'] == 'compare') {
	$eg = false;
	$txtAr[1] = html_entity_decode($_POST['cont1']);
	$txtAr[2] = html_entity_decode($_POST['cont2']);
	$compareAr = array(
		array(1, 2)
	);
	echo '<h2>Result</h2>';
	showDetection($compareAr, $txtAr, false);
} else {
	$eg = true;
	for ($i = 1; $i <= 7; $i++) 
		$txtAr[$i] = file_get_contents('data/'.$i.'.format.cpp');
	$compareAr = array(
		'12' => array(1, 2, true),
		'13' => array(1, 3, true),
		'14' => array(1, 4, true),
		'15' => array(1, 5, false),
		'16' => array(1, 6, false),
		'17' => array(1, 7, false),

		'23' => array(2, 3, true),
		'24' => array(2, 4, true),
		'25' => array(2, 5, false),
		'26' => array(2, 6, false),
		'27' => array(2, 7, false),

		'34' => array(3, 4, true),
		'35' => array(3, 5, false),
		'36' => array(3, 6, false),
		'37' => array(3, 7, false),

		'45' => array(4, 5, false),
		'46' => array(4, 6, false),
		'47' => array(4, 7, false),

		'56' => array(5, 6, true),
		'57' => array(5, 7, true),

		'67' => array(6, 7, true),
	);
	if (isset($_GET['p'])) {
		$pair = $_GET['p'];
		$compareAr = array($compareAr[$pair]);
		showDetection($compareAr, $txtAr, true);
	} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="assets/dist/img/favicon.ico" />
	<title>Simple C++ Plagiarism Detection</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/samples.css">
	<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>

<div class="page-head">
	<h1>Simple tests</h1>
</div>

<div class="intro">

<div class="howitworks">
<h3>How it works</h3>
<ol>
	<li>Beautify the code, remove libraries included.</li>
	<li>Format code.
		<ol class="note">
			<li>Rename similar tokens</li>
			<li>Rewrite printf-cout / scanf-cin,...</li>
		</ol>
		<div class="eg"><b>Eg</b>: 
			<ul>
				<li>(var) <b>token1</b> -> <b>a</b>, (var) <b>token2</b> -> <b>b</b>, (function) <b>func1</b> -> <b>a_15</b>, (function) <b>func2</b> -> <b>b_93</b>,...</li>
				<li><b>float a, b</b> -> <b>float a; float b</b></li>
				<li><b>printf / scanf</b> -> <b>cout / cin</b></li>
				<li><b>do {} while ()</b> -> <b>while {}</b></li>
			</ul>
		</div>
	</li>
	<li>Convert content to tokens.<br/>
		<b>Eg</b>: Given <code>int main () { return 0; }</code><br/>
		We will divide it into string of tokens, not characters.<br/>
		For example, The sequence of 4-grams derived from the content given will be something like <br/>
		<code>intmain()</code> <code>main(){</code> <code>(){return</code> <code>return0;}</code><br/>
		but not <code>intm</code> <code>main</code> <code>ain(</code>...
	</li>
	<li>Remove unnecessary tokens.<br/>
		<div class="eg"><b>Eg</b>: (brackets) {}...</div>
		<div class="note">Remove brackets so that <b>if {<i>[OneLineCode]</i>}</b> and <b>if <i>[OneLineCode]</i></b> will be catched.</div>
	</li>
	<li>Get fingerprints.<br/>
		By getting the minimum value of each group of k-value.
	</li>
</ol></div>

<div class="problems">
<h3>Problems remain</h3>
<ol>
	<li>For / while</li>
	<li>Change variables order</li>
	<li>...</li>
</ol>
</div>
</div>

<div class="test">
	<h2>Test</h2>
	<form class="submit" method="post" action="?do=compare">
		<div class="col-lg-6 no-padding-left">
			<h4>Code content #1</h4>
			<textarea name="cont1" style="height:100px" class="form-control"></textarea>
		</div>
		<div class="col-lg-6 no-padding-right">
			<h4>Code content #2</h4>
			<textarea name="cont2" style="height:100px" class="form-control"></textarea>
		</div>
		<div class="clearfix"></div>
		<div class="btn-groups center" style="margin-top:10px">
			<input type="reset" class="btn btn-default" value="Reset"/>
			<input type="submit" class="btn btn-success" value="Submit"/>
		</div>
	</form>

	<div id="result-input"></div>
</div>

<div class="examples">
	<h1>Examples</h1>
	<blockquote>
		<ol>
			<li>4-gram</li>
			<li>Winnow w = 4</li>
			<li>Select minimum value as fingerprint</li>
		</ol>
	</blockquote>
	<div id="toc" class="col-lg-2 anchorList no-padding-left">
		<div class="choose-pair">
			<? for ($i = 1; $i <= 7; $i++) {
				for ($j = $i+1; $j <= 7; $j++) {
					$key = $i.$j;
					$sb = $compareAr[$i.$j][2] ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>';
					echo '<div class="pair-choose-one anchorLink"><a href="#result-eg" data-p="'.$i.$j.'">'.$i.' - '.$j.' (Should be '.$sb.')</a></div>';
				}
			} ?>
		</div>
	</div>
	<div class="col-lg-10 no-padding">
		<div class="alert alert-info">Given some code, select one pair to compare.</div>
		<? for ($i = 1; $i <= 7; $i++) { ?>
			<div class="col-lg-4 no-padding-left code-content-<? echo $i ?>">
				<h3 class="ppd" id="p<? echo $i ?>"><i>p<? echo $i ?></i></h3>
				<pre class="code" style="height:170px"><? echo $txtAr[$i] ?></pre>
			</div>
		<? } ?>
	</div>
	<div class="clearfix"></div>
	
	<div id="result-eg"></div>
	<div class="clearfix"></div>
</div>

<div class="page-foot">
	Footer
</div>

	<script src="js/beautify.js"></script>
	<script src="js/samples.js"></script>

</body>
</html>

<? 	}
}

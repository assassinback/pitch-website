<?php // content="text/plain; charset=utf-8"
require_once ('config.php');
require_once (ADMIN_PATH . 'inc/jpgraph/jpgraph.php');
require_once (ADMIN_PATH . 'inc/jpgraph/jpgraph_radar.php');

$user_id = $_GET['user_id'];
$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_plan WHERE user_id = ?', array($user_id));
if ($result->num_rows() == 0) {
    redirect(getLink());
}
$userPlanInfo = $result->row_array();

$tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 AND test.id IN (' . $userPlanInfo['allowed_test'] . ')', array($user_id));
$tests = $tests->result_array();

$limit = count($tests);
$plotLines = array();
$plotLines[] = array('color' => '#848484', 'line_weight' => '1', 'points' => array_fill(0, $limit, 20));
$plotLines[] = array('color' => '#848484', 'line_weight' => '1', 'points' => array_fill(0, $limit, 40));
$plotLines[] = array('color' => '#848484', 'line_weight' => '1', 'points' => array_fill(0, $limit, 60));
$plotLines[] = array('color' => '#ff2525', 'line_weight' => '2', 'points' => array_fill(0, $limit, 80));
$plotLines[] = array('color' => '#58b354', 'line_weight' => '2', 'points' => array_fill(0, $limit, 100));

$titles = array();
$points = array();
foreach ($tests as $test) {
    $titles[] = strtoupper(str_replace(" Test", "", $test['title']));
    $points[] = round($test['weightage']);
}
$plotLines[] = array('color' => '#4774c5', 'line_weight' => '2', 'points' => $points);

// Create the basic radar graph
$graph = new RadarGraph(400,400);
$graph->img->SetAntiAliasing();

// Set background color and shadow
$graph->SetColor("white");

// Set image border
$graph->SetFrame(false);

// Position the graph
$graph->SetCenter(0.5,0.5);

// Setup the axis formatting 	
//$graph->axis->SetFont(FF_VERDANA);

// Setup the grid lines
$graph->grid->Show();
$graph->HideTickMarks();

// Hide legend
$graph->legend->Hide();

// Set Axis title
$graph->SetTitles($titles);

foreach ($plotLines as $key => $line) {
    ${'plot' . $key} = new RadarPlot($line['points']);
    ${'plot' . $key}->SetColor($line['color']);
    ${'plot' . $key}->SetLineWeight($line['line_weight']);
    $graph->Add(${'plot' . $key});
}

// And output the graph
$graph->Stroke();

?>

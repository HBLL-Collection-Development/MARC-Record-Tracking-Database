<?php
  require_once 'config.php';
  
  // Filter input
  $goal = filter_input(INPUT_GET, 'goal', FILTER_SANITIZE_STRING);
  $start_chapter_id = filter_input(INPUT_GET, 'start_chapter_id', FILTER_SANITIZE_NUMBER_INT);
  $end_chapter_id = filter_input(INPUT_GET, 'end_chapter_id', FILTER_SANITIZE_NUMBER_INT);
  $start_date = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_STRING);
  $end_date = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
  $chapters_or_verses = filter_input(INPUT_GET, 'chapters_or_verses', FILTER_SANITIZE_STRING);
  $num_per_day = filter_input(INPUT_GET, 'num_per_day', FILTER_SANITIZE_NUMBER_INT);
  $highlight_shortest_verse = filter_input(INPUT_GET, 'highlight_shortest_verse', FILTER_VALIDATE_BOOLEAN);
  $format = filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING);
  
  // Create args array to pass to class
  $args = array(
    'goal'                     => $goal,
    'start_chapter_id'         => $start_chapter_id,
    'end_chapter_id'           => $end_chapter_id,
    'start_date'               => $start_date,
    'end_date'                 => $end_date,
    'date'                     => $date,
    'chapters_or_verses'       => $chapters_or_verses,
    'num_per_day'              => $num_per_day,
    'highlight_shortest_verse' => $highlight_shortest_verse,
    'format'                   => $format
  );
  
  $schedule = new logic;
  $reading = $schedule->create($args);

?>

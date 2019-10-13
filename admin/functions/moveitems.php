<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");

// Change the position of an item on a page or of a page in the navigator
// $table:        the database table to update
// $position_key: the database column name for writing the new int position
// $primary_key:  the database column containing the primary key for the $items
// $item:         the int item to swap
// $items:        all items in the same context (e.g. page) as $item
//                must be in the ascending order if $direction === 'down' and in descending order otherwise
// $positions:    the number of positions that the item will be swapped
//                must be > 0; too large values will be interpreted as max position
// $direction:    'up' or 'down'
function move_item($table, $position_key, $primary_key, $item, $items, $positions, $direction) {
	if(!(@is_numeric($positions) || @ctype_digit($positions))) {
		return false;
	}
	if ($positions < 1) {
		return false;
	}

	$itemcount = count($items);

	// Detect source position of the item to swap
	$found = false;
	$swapfrom = 0;
	for ($i = 0; $i < $itemcount && !$found; $i++) {
		if ($item == $items[$i])
		{
			$found = true;
			$swapfrom = $i;
		}
	}
	if ($found) {
		// Detect target position
		$swapto = min($itemcount - 1, $swapfrom + $positions);
		if (!($swapfrom < $swapto)) {
			return true;
		}

		// Swap
		for ($i = $swapfrom; $i < $swapto; $i++) {
			$items[$i] = $items[$i + 1];
		}
		$items[$swapto] = $item;

		// Count up or down depending on direction
		if ($direction !== "down") {
			$items = array_reverse($items);
		}

		// Bring into shape for the database call
		$values = array();
		for ($i = 0; $i < $itemcount; $i++) {
			array_push($values, array($i, $items[$i]));
		}

		// Write
		$sql = new SQLUpdateStatement($table,
			array($position_key), array($primary_key),
			array(), 'ii');
		$sql->set_values($values);

		return $sql->run();
	}
	return false;
}
?>

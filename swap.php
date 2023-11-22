<?php
/*
 * url eg. database/asdf/table/fruit/swap/2,5
 */

/*
SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'elements' order by ORDINAL_POSITION; - x2 slow
*/
global $db;
/*$dbname = $_GET['database'];
$table = $_GET['table'];
$qry = "DESC {$dbname}.{$table}";*/
$qry = "USE {$_GET['database']}";
$db->run($qry);
//$qry = "DESC {$_GET['database']}.{$_GET['table']}";
$qry = "DESC {$_GET['table']}";
$columns = $db->run($qry)fetchColumn();
//$rows = explode (',', $_GET['rows']);
/*
update fruit a
 inner join fruit b on a.id <> b.id
   set a.color = b.color,
       a.name = b.name,
       a.calories = b.calories
	   where a.id in (2,5) and b.id in (2,5)
 */
$qry = "UPDATE {$_GET['table']} a INNER JOIN {$_GET['table']} b ON a.id <> b.id SET";
unset ($columns['id']);
foreach ($columns as $c)
{
	$qry .= " a.{$c} = b.{$c},";
}
$qry = substr($qry, 0, -1);
//$qry .= " WHERE a.id IN ({$rows[0]},{$rows[1]}) AND b.id IN ({$rows[0]},{$rows[1]})";
$qry .= " WHERE a.id IN ({$_GET['swap']}) AND b.id IN ({$_GET['swap']})";

/*
UPDATE
  yourtable t1 INNER JOIN yourtable t2
  ON (t1.id, t2.id) IN ((1,5),(5,1))
SET
  t1.color = t2.color,
  t1.name = t2.name,
  t1.calories = t2.calories
 */

/*
SELECT name, color, calories FROM fruit WHERE id = 2 INTO @name, @color, @calories;

UPDATE fruit AS f1, fruit AS f2
SET
 f1.name = f2.name, f2.name = @name,
 f1.color = f2.color, f2.color = @color,
 f1.calories = f2.calories, f2.calories = @calories
 WHERE (f1.id, f2.id) = (2, 5);
*/

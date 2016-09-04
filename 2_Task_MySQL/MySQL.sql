/**
1. To improve DB tables I'd change the creation query of `link` table to look like the following:
 */

CREATE TABLE `link` (
        `data_id` int(11) NOT NULL,
        `info_id` int(11) NOT NULL,
        PRIMARY KEY (data_id, info_id)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*
I added here primary key in order to exclude occurrences of duplicating rows in this many-to-many connecting table.

2. Using "SELECT * ..." statement with asterisk is highly not recommended because,
firstly, using it decreases efficiency in moving data to the consumer(not needed columns are selected),
secondly - it retrieves two columns of the same name from two different tables(like id),
and thirdly - it decreases code readability(at first glance you don't understand what columns are selected).
Since it is SELECT query, fields names in it should be specified instead of "*" sign.
It could be looking like the following:
*/

SELECT  data.date, data.value, info.name, info.`desc`, link.data_id, link.info_id
FROM data, link, info
WHERE
  link.info_id = info.id
  AND
  link.data_id = data.id

/*
Also this query could be made with joins like so:
*/

select data.date, data.value, info.name, info.`desc`, link.data_id, link.info_id
from info
  inner join link on info.id = link.info_id
  inner join data on link.data_id = data.id
<?php
try {
	$youtrack = new YouTrack\Connection(
		YOUTRACK_URL,
		YOUTRACK_USERNAME,
		YOUTRACK_PASSWORD
	);
} catch (\YouTrack\IncorrectLoginException $e) {
	echo $e->getMessage();
}

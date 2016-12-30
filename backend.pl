#!usr/bin/perl

require "main.pl";

while(1)
{
	$start = time();
	uptime();
	$end = time();

	$rest = $end - $start;

	sleep(30 - ($end - $start));
}

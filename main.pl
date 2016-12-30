#!/usr/bin/perl

use Net::SNMP qw(snmp_dispatcher);
use DBI;
use Cwd;
require "dbpath.pl";
require "$realpath";

sub uptime 
{	
	$dsn = "DBI:mysql:$database:$host:$port";
	$dbh = DBI->connect($dsn,$username,$password);

	$uth = $dbh->prepare("CREATE TABLE IF NOT EXISTS Uptime (id int (11) NOT NULL AUTO_INCREMENT, IP tinytext NOT NULL, PORT int (11) NOT NULL, COMMUNITY tinytext NOT NULL, Uptime varchar(255) NOT NULL, Sent int (11) NOT NULL, Lost int (11) NOT NULL, TLost int (11) NOT NULL , PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET= latin1 AUTO_INCREMENT=1;");
	$uth->execute() or die $DBI::errstr; 
	
	$fth = $dbh->prepare("INSERT IGNORE INTO Uptime (id,IP,PORT,COMMUNITY) SELECT DEVICES.id, DEVICES.IP, DEVICES.PORT, DEVICES.COMMUNITY FROM DEVICES");		
	$fth->execute() or die $DBI::errstr;

	$sth = $dbh->prepare("SELECT * FROM Uptime");
	$sth->execute() or die $DBI::errstr;

	while(@row = $sth->fetchrow_array())
	{

	$ip = $row[1];
	$ports = $row[2];
	$com = $row[3];

	$session = Net::SNMP->session(
                           -hostname      => $ip,
                           -port          => $ports,
                           -community     => $com,
			   -nonblocking   => 1,
			   -timeout       => 3,   
       					);
	
	$sysUpTime = '1.3.6.1.2.1.1.3.0';
      	
	$session->get_request(
          -varbindlist => [$sysUpTime],
          -callback    => [\&call, $ip, $ports, $com]
      				);
	}	
	
	snmp_dispatcher(); # Enter the event loop

	sub call
   {
      	 my ($session, $ip, $ports, $com) = @_;

         if (!defined($session->var_bind_list))
	{
		$rth = $dbh->prepare("UPDATE Uptime SET Sent = Sent+1,Lost = Lost+1,TLost= TLost+1 WHERE IP= '$ip' AND PORT = '$ports' AND COMMUNITY = '$com'");		
		$rth->execute() or die $DBI::errstr;		
		printf("%-15s %s : Non-responsive\n", 							
        	$session->hostname, $com);
		$rth->finish();	            
        }

	  else
	{
		my $up = $session->var_bind_list->{$sysUpTime}; 
		$rth2 = $dbh2->prepare("UPDATE Uptime SET Lost=0,Uptime = '$up',Sent = Sent+1 WHERE IP= '$ip' AND PORT = '$ports' AND COMMUNITY = '$com'");		
	 	$rth2->execute() or die $DBI::errstr;		
		printf("%-15s : Uptime is %s \n", 							
        	$session->hostname, $up);
		$rth2->finish();            
        }

   }

}


#!/usr/bin/perl -w
use strict;
use warnings;
use LWP::Simple;
use HTML::Tree;
use DBI;
use DBD::mysql;

#Connectin to Database

my $host="";
my $database="stock";
my $user="root";
my $pw="ssn";

my $connect = DBI->connect("DBI:mysql:$database:$host",$user,$pw);
my $myquery = "";

my $url_full = "http://money.rediff.com";
my $content = get($url_full);



if($content) 
{
	my $tree = HTML::Tree->new();
	$tree->parse($content);


	my $bse_div = $tree->look_down( _tag => 'div', 'id' => 'sensTab1' );
	my $nse_div = $tree->look_down( _tag => 'div', 'id' => 'sensTab2' );

	if($bse_div)
	{
		print "\nBse index: ";		
		my $bse_val = $bse_div->look_down( _tag => 'span' )->as_text;
		print $bse_val;
		print "\nToday's change: ";
		my $bse_today = $bse_div->look_down( _tag => 'span', 'class' => 'red');
		if($bse_today) { }
		else
		{
			$bse_today = $bse_div->look_down( _tag => 'span', 'class' => 'green');
		}
		print $bse_today->as_text;
		print "\nBse percent: ";
		#my @temp = $bse_div->content_list;		
		my $temp = substr $bse_div->as_text, -7, -1;		
		print $temp;
	
		my $bse_text = $bse_today->as_text;
		$myquery="INSERT INTO stock_details(market,price,daily_change,percentage_change) VALUES ('BSE',?,?,?)";
		my $statement = $connect->prepare($myquery);
		$statement->execute($bse_val,$bse_text,$temp);
	}
	else
	{
		print "Not found";
	}

	if($nse_div)
	{
		print "\nNse index: ";		
		my $nse_val = $nse_div->look_down( _tag => 'span' )->as_text;;
		print $nse_val;
		print "\nToday's change: ";
		my $nse_today = $nse_div->look_down( _tag => 'span', 'class' => 'red');
		if($nse_today) { }
		else
		{
			$nse_today = $nse_div->look_down( _tag => 'span', 'class' => 'green');
		}
		print $nse_today->as_text;
		print "\nNse percent: ";
		my $temp = substr $nse_div->as_text, -7, -1;		
		print $temp;
		my $nse_text = $nse_today->as_text;
		$myquery="INSERT INTO stock_details(market,price,daily_change,percentage_change) VALUES ('NSE',?,?,?)";
		my $statement = $connect->prepare($myquery);
		$statement->execute($nse_val,$nse_text,$temp);	
	}
	else
	{
		print "Not found";
	}
}
else 
{
	print "Content is empty"
}
print "\n";


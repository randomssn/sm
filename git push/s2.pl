#!/usr/bin/perl
use File::stat;
use Time::localtime;
my $file_pattern  =$ARGV[0];
my @a = glob $file_pattern  ;
foreach (@a) {
    	
    if ( -e $_) 
    {
	my $d = ctime(stat($_)->mtime);
        print "File found $_ and was created on $d.\n";
    } 
    else 
    { 
            print "No file found $_.\n";
    }
}
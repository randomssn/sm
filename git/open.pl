#!/usr/bin/perl
use strict;
use warnings;
my $path = "/root/Desk";
my $name = "Desktop";

opendir (DIR_TO_SEARCH, $path)  or  die "Failed to open directory $path : $!";
my @dirs_found = grep { /$name/ } readdir DIR_TO_SEARCH;
closedir (DIR_TO_SEARCH);

for my $dir (@dirs_found) {
        print $dir , "\n";
}

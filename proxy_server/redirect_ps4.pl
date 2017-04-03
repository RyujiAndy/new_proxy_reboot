#!/usr/bin/perl
use strict;

$| = 1;

while (<>) {
    
    my @elems = split;
    
    my $url = $elems[0];
    
    if ($url =~ m#^http://manuals\.playstation\.net(/.*)?#i) {
        
        $url = "http://ps4.reboot.ms";
        
        print "$url\n";
        
    }
    
    else {
        
        print "$url\n";
        
    }
    
}

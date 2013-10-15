
#for downloading the file
wget $1 -O $2

#moving it to rootdir
mv $2 example/rootdir/$2

#now doing the mounting process
./src/bbfs example/rootdir/ example/mountdir/


# Copyright (c) 2017 Matt Samudio (Albert Lea Data)  All Rights Reserved.
# Contact information for Albert Lea Data is available at:
#	http://www.albertleadata.com
#
# This file is part of FreeCMDB.
#
#   FreeCMDB is free software: you can redistribute it and/or modify
#   it under the terms of the GNU Lesser General Public License as
#   published by the Free Software Foundation, either version 3 of
#   the License, or (at your option) any later version.
#
#   FreeCMDB is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU Lesser General Public License for more details.
#
#   You should have received a copy of the
#   GNU Lesser General Public License along with FreeCMDB.
#   If not, see <http://www.gnu.org/licenses/>.
DML = "zen:/srv/www/albertleadata.org/freecmdb"
DSL = "spirit:$(HOME)/www/mantis"
PGM1 = cmdb
PGMS = $(PGM1)
JAR = freecmdb.jar
UTP = testunit
CC_SRC1 = freecmdb.cc
PKG = freecmdb-bin.tar.gz
#CC_HDR = $(shell ls *.h)
#CFLG = -g -c -I/usr/X11R6/include
#LFLG = -g -L/usr/X11R6/lib -lX11

all: $(PGMS)
	@echo Build complete

clean:
	@rm -f $(PKG) $(PGMS) freecmdb-dev.tar.gz

pkg: $(PKG)
	@mkdir -p ./pkg/bin
	@mkdir -p ./pkg/lib
	@cp ./bin/cmdb ./pkg/bin/
	@tar cf freecmdb-bin.tar -C ./pkg .
	@gzip freecmdb-bin.tar
	@rm -rf ./pkg
#	@scp $(PKG) $(DML)
#	@rm -f $(PKG)

$(PKG): $(PGMS) ${JAR}
	@echo "Binaries for package built"

rls: $(PGMS)
	tar cf freecmdb-src.tar Makefile bin etc README.md
	gzip freecmdb-src.tar
	scp freecmdb-src.tar.gz $(DML)
	rm -f freecmdb-src.tar.gz

syncwww:
	@rsync -ai --no-o --no-g --no-p --exclude='*.swp' ./www/ $(DSL)/

%.class : %.java
	javac -cp $(LCP) $<

%.o : %.cc
	g++ -o $*.o -g -Wno-write-strings -c -fPIC $(INC) $(DEFS) $<

%.o : %.c
	gcc -o $*.o -g -Wno-write-strings -c -fPIC $(INC) $(DEFS) $<

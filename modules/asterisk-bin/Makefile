# Makefile skeleton for baf modules

CUR_DIR		:= $(shell pwd)
BAF_DIR		:= /usr/local/share/baf

TOOLCHAIN	:= $(BAF_DIR)/toolchain/bin
TOOLPREFX	:= arm-linux-uclibc

TOOL_INC	:= $(BAF_DIR)/toolchain/usr/include
TOOL_LIB	:= $(BAF_DIR)/toolchain/lib
TOOL_USR_LIB	:= $(BAF_DIR)/toolchain/usr/lib

BFX_INC		:= $(BAF_DIR)/include
BFX_LIB		:= $(BAF_DIR)/lib

CFLAGS		:= "-I$(TOOL_INC) -I$(BFX_INC)"
LDFLAGS		:= "-L$(TOOL_LIB) -L$(TOOL_USR_LIB) -L$(BFX_LIB)"

DEST_ROOT	:= /userapp
INST_PATH	:= $(CUR_DIR)

CONF_OPTS	:= --build=$(shell uname -m) --host=$(TOOLPREFX) --prefix=$(DEST_ROOT)
MAKE_OPTS	:= -s
INST_OPTS	:= exec_prefix=$(INST_PATH) -s

all:
	echo "nothing to do"

install:
	echo "nothing to install"

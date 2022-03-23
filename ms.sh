#!/usr/bin/env bash
clear
ARCH_(){
	case $(dpkg --print-architecture) in
		arm64|aarch*)
			echo "arm64"
			ARCH=arm64 ;;
		*) echo "仅支持64位设备"
		echo -e "正在退出"
		sleep 1
		exit ;;
esac
}
INSTALL_JAVA(){
    sed -i 's@^\(deb.*stable main\)$@#\1\ndeb https://mirrors.tuna.tsinghua.edu.cn/termux/termux-packages-24 stable main@' $PREFIX/etc/apt/sources.list && sed -i 's@^\(deb.*games stable\)$@#\1\ndeb https://mirrors.tuna.tsinghua.edu.cn/termux/game-packages-24 games stable@' $PREFIX/etc/apt/sources.list.d/game.list && sed -i 's@^\(deb.*science stable\)$@#\1\ndeb https://mirrors.tuna.tsinghua.edu.cn/termux/science-packages-24 science stable@' $PREFIX/etc/apt/sources.list.d/science.list
    apt install openjdk-17
    MAIN
}
INSTALL_SERVER(){
    echo "正在下载服务端,版本135"
    wget mcw.muxi416.ga/135server.zip
}
MAIN(){
	printf "你的手机架构为"
	ARCH_
	echo -e "
	1) 未安装java(默认选择)
	2) 已安装java
	0) 退出\n"
	read -r -p "请选择:" input
	case $input in
		1) INSTALL_JAVA ;;
		2) INSTALL_SERVER ;;
		0) echo -e "正在退出"
			sleep 1
			exit 1 ;;
		*) echo -e "无效选择，请重选"
			sleep 2
			MAIN ;;
	esac
}
MAIN
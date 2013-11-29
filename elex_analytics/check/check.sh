#!/bin/bash

#net_ip=`ifconfig eth1 |grep 'inet addr'| cut -d: -f2| awk '{print $1'}`
net_ip="127.0.0.1"
echo ${net_ip}
rm ../site_data/test -rf
rm ../store_log/test -rf

wget "http://${net_ip}/index.php?appid=test&uid=index_ok1&event=user.visit"
wget "http://${net_ip}/index_img.php?appid=test&uid=index_imgok2&event=user.visit"
wget "http://${net_ip}/123456/static.gif?appid=test&uid=static_ok3&event=user.visit"
wget "http://${net_ip}/123456/index.gif?appid=test&uid=index_gif_ok4&event=user.visit"
wget 'http://'${net_ip}'/123456/index.gif?appid=test&uid=index_gif_ok9&event=pay.complete&json_var={"vamount":100}'


wget 'http://'${net_ip}'/storelog.php?appid=test&log={"signedParams":{"sns_uid":"store_ok5","appid":"test"},
"stats":[{"statfunction":"count","data":["buy","item","","","","","90"],"timestamp":"1302250796"}]}'
wget 'http://'${net_ip}'/123457/storelog.gif?appid=test&log={"signedParams":{"sns_uid":"store_gif_ok6","appid":"test"},
"stats":[{"statfunction":"count","data":["buy","item","","","","","90"],"timestamp":"1302250796"}]}'
wget 'http://'${net_ip}'/storelog_img.php?appid=test&log={"signedParams":{"sns_uid":"store_img_ok7","appid":"test"},
"stats":[{"statfunction":"count","data":["buy","item","","","","","90"],"timestamp":"1302250796"}]}'

wget 'http://'${net_ip}'/index.php?json={"signedParams":{"uid":"index_all8","appid":"test"},
"stats":[{"eventName":"user.visit","params":{"ref":"abcd"},"timestamp":"1302250796"},{"eventName":"user.quit","params":{"a":"b"},"timestamp":"1302250796"},{"eventName":"count","params":{"a":"b"},"timestamp":"1302250796"}]}'



#new log
wget 'http://'${net_ip}'/v3/index.php?json={"signedParams":{"uid":"index_v3_index","appid":"test","timestamp":"1302250820"},
"stats":[{"eventName":"visit","params":{"ref":"abcd"},"value":100,"timestamp":"1302250796"},{"eventName":"quit","value":200,"params":{"debug":"b"},"timestamp":"1302250796"},{"eventName":"count.haha.abcd","params":{"debug":"count"},"value":100,"timestamp":"1302250796"},{"eventName":"pay","params":{"gcurrency":"USD","debug":"count"},"value":100,"timestamp":"1302250796"}
,{"eventName":"update","params":{"update1":"1","update2":"2"},"value":100,"timestamp":"1302250796"}]}'

wget 'http://'${net_ip}'/v3/123456/index.gif?json={"signedParams":{"uid":"index_v3_index_gif","appid":"test","timestamp":"1302250820"},
"stats":[{"eventName":"visit","params":{"ref":"abcd"},"value":100,"timestamp":"1302250796"},{"eventName":"quit","value":200,"params":{"debug":"b"},"timestamp":"1302250796"},{"eventName":"count.haha.abcd","params":{"debug":"count"},"value":100,"timestamp":"1302250796"},{"eventName":"pay","params":{"gcurrency":"USD","debug":"count"},"value":100,"timestamp":"1302250796"}
,{"eventName":"update","params":{"update1":"1","update2":"2"},"value":100,"timestamp":"1302250796"}]}'

wget 'http://'${net_ip}'/v4/test/indexv4?action0=visit&action1=buy.fruit.apple,5&action2=buy.fruit.5&update0=grade,10'

cat ../site_data/test/*/*/*/*
cat ../store_log/test/*/*/*/*

site=`cat ../site_data/test/*/*/*/*|wc -l`
echo "expect 17=${site}"
store=`cat ../store_log/test/*/*/*/*|wc -l`
echo "expect 8=${store}"


rm ./index*uid* -rf
rm ./index*v4* -rf
rm ./static*uid* -rf
rm ./*storelog*uid* -rf





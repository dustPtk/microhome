<?php
try {

    $dbh = new PDO('pgsql:host=localhost;port=3306;dbname=test', 'root','123456');
    $dbh1 = new PDO('mysql:host=localhost;port=3306;dbname=test', 'root','123456');

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die("connect failed");
}

$sql = "select id,gongqiu,diduan,quyu,shoujia,mianji,title,description,linkman,mobile,shenfen,longitude,latitude,city,chakancishu,insert_time,status,localdiduan,classid,fangxing,classtbname,uid,company,payment,factorytype,infono,rent from changfang";


while($row=$dbh->query($sql)) {
    $sql1 = "insert into changfang(id,gongqiu,diduan,quyu,shoujia,mianji,title,description,linkman,mobile,shenfen,longitude,latitude,city,chakancishu,insert_time,status,localdiduan,classid,fangxing,classtbname,uid,company,payment,factorytype,infono,rent)
     values($row->id,gongqiu,diduan,quyu,shoujia,mianji,title,description,linkman,mobile,shenfen,longitude,latitude,city,chakancishu,insert_time,status,localdiduan,classid,fangxing,classtbname,uid,company,payment,factorytype,infono,rent)";



    $dbh1->exec($sql1);
    continue;
}


$dbh = null;
$dbh1 = null;
?>
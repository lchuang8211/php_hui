select
(case when 國>=80 then '優秀' when 國>=60 then '及格'else '不及格') as 國,
(case when 數>=80 then '優秀' when 數>=60 then '及格'else '不及格') as 數,
(case when 英>=80 then '優秀' when 英>=60 then '及格'else '不及格') as 英 FROM score;


SELECT
(case when `國`>=80 then '優秀' when `國`>=60 then '及格'else '不及格') as `國`,
(case when `數`>=80 then '優秀' when `數`>=60 then '及格'else '不及格') as `數`,
(case when `英`>=80 then '優秀' when `英`>=60 then '及格'else '不及格') as `英` FROM `score`;

SELECT 
(case when 國>= 80 then '優秀' when 國>=60 then '及格' else '不及格' END) as 國,
(case when 數>= 80 then '優秀' when 數>=60 then '及格' else '不及格' END) as 數,
(case when 英>= 80 then '優秀' when 英>=60 then '及格' else '不及格' END) as 英 FROM `score`


create table #tmp(rq varchar(10),shengfu char(1));

insert into #tmp values('2005-05-09','w');
insert into #tmp values('2005-05-09','w');
insert into #tmp values('2005-05-09','l');
insert into #tmp values('2005-05-09','l');
insert into #tmp values('2005-05-10','w');
insert into #tmp values('2005-05-10','l');
insert into #tmp values('2005-05-10','l');

select rq, sum(case when shengfu='w' then 1 else 0 end)'w',sum(case when shengfu='l' then 1 else 0 end)'l' from #tmp group by rq ;

select N.rq,N.w,M.l from (select rq,w=count(*) from #tmp where shengfu='w'group by rq)N inner join(select rq,l=count(*) from #tmp where shengfu='l'group by rq)M on N.rq=M.rq;

select a.col001,a.a1 w,b.b1 l from (select col001,count(col001) a1 from temp1 where col002='w' group by col001) a,
(select col001,count(col001) b1 from temp1 where col002='l' group by col001) b where a.col001=b.col001 ;

1.一道SQL語句面試題,關於group by
表內容:
2005-05-09 勝
2005-05-09 勝
2005-05-09 負
2005-05-09 負
2005-05-10 勝
2005-05-10 負
2005-05-10 負

如果要生成下列結果, 該如何寫sql語句?

            勝 負
2005-05-09 2 2
2005-05-10 1 2
------------------------------------------
create table tmp(rq varchar(10),shengfu nchar(1))

insert into tmp values('2005-05-09','勝')
insert into tmp values('2005-05-09','勝')
insert into tmp values('2005-05-09','負')
insert into tmp values('2005-05-09','負')
insert into tmp values('2005-05-10','勝')
insert into tmp values('2005-05-10','負')
insert into tmp values('2005-05-10','負')

1)
select rq, sum(case when shengfu='勝' then 1 else 0 end)'勝',sum(case when shengfu='負' then 1 else 0 end)'負' from tmp group by rq
2)
select N.rq,N.勝,M.負 from (
select rq,勝=count(*) from tmp where shengfu='勝'group by rq)N inner join
(select rq,負=count(*) from tmp where shengfu='負'group by rq)M on N.rq=M.rq
3)
select a.col001,a.a1 勝,b.b1 負 from
(select col001,count(col001) a1 from temp1 where col002='勝' group by col001) a,
(select col001,count(col001) b1 from temp1 where col002='負' group by col001) b
where a.col001=b.col001


一、

教師號 星期號 是否有課

1 2 有

1 3 有

2 1 有

3 2 有

1 2 有

寫一條sql語句讓你變為這樣的表

教師號 星期一 星期二 星期三

		1 		2		1

		2		1

		3		1

各星期下的數字表示:對應的教師在星期幾已經排的課數

create table teacher(Monday char(1), Tuesday char(1), Wednesday char(1));

insert into teacher values('0', '1', '1');
insert into teacher values('0', '0', '0');
insert into teacher values('0', '1', '1');
insert into teacher values('1', '1', '0');
insert into teacher values('1', '0', '0');
insert into teacher values('0', '1', '1');

sum(teacher.Monday), teacher.Tuesday=count(*), teacher.Wednesday=count(*) 
select sum(teacher.Monday), sum(teacher.Tuesday), sum(teacher.Wednesday) from teacher;


7.請用一個sql語句得出結果
從table1,table2中取出如table3所列格式資料,注意提供的資料及結果不準確,只是作為一個格式向大家請教。
如使用儲存過程也可以。

table1

月份mon 部門dep 業績yj
-------------------------------
一月份      01      10
一月份      02      10
一月份      03      5
二月份      02      8
二月份      04      9
三月份      03      8

table2

部門dep      部門名稱dname
--------------------------------
      01      國內業務一部
      02      國內業務二部
      03      國內業務三部
      04      國際業務部

table3 (result)

部門dep 一月份      二月份      三月份
--------------------------------------
      01      10        null      null
      02      10         8        null
      03      null       5        8
      04      null      null      9

------------------------------------------
1)
select a.部門名稱dname,b.業績yj as '一月份',c.業績yj as '二月份',d.業績yj as '三月份'
from table1 a,table2 b,table2 c,table2 d
where a.部門dep = b.部門dep and b.月份mon = '一月份' and
a.部門dep = c.部門dep and c.月份mon = '二月份' and
a.部門dep = d.部門dep and d.月份mon = '三月份' and
2)
select a.dep,
sum(case when b.mon=1 then b.yj else 0 end) as '一月份',
sum(case when b.mon=2 then b.yj else 0 end) as '二月份',
sum(case when b.mon=3 then b.yj else 0 end) as '三月份',
sum(case when b.mon=4 then b.yj else 0 end) as '四月份',
sum(case when b.mon=5 then b.yj else 0 end) as '五月份',
sum(case when b.mon=6 then b.yj else 0 end) as '六月份',
sum(case when b.mon=7 then b.yj else 0 end) as '七月份',
sum(case when b.mon=8 then b.yj else 0 end) as '八月份',
sum(case when b.mon=9 then b.yj else 0 end) as '九月份',
sum(case when b.mon=10 then b.yj else 0 end) as '十月份',
sum(case when b.mon=11 then b.yj else 0 end) as '十一月份',
sum(case when b.mon=12 then b.yj else 0 end) as '十二月份',
from table2 a left join table1 b on a.dep=b.dep

8.華為一道面試題
一個表中的Id有多個記錄,把所有這個id的記錄查出來,並顯示共有多少條記錄數。
------------------------------------------
select ID, Count(*) from tb group by ID having count(*)>1
select * from(select count(ID) as count from table group by ID)T where T.count>1
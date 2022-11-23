DROP TABLE IF EXISTS linkdata;

CREATE TABLE linkdata (
    id         int(11)       NOT NULL AUTO_INCREMENT,
    l_begin_time      varchar(255)  NOT NULL,
    l_end_time    varchar(100)  NOT NULL,
    l_content  varchar(255)  NOT NULL,
    l_date     datetime      NOT NULL,
    PRIMARY KEY (id)
); 

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (1, 'https://github.com/GotoRen', 'Github', 'GotoRen Remote Repository', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (2, 'https://qiita.com/ren1007', 'Qiita', 'GotoRen Qiita mypage', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (3, 'https://pluslab.org', 'Pluslab', '内藤研究室', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (4, 'https://www.youtube.com/', 'YouTube', 'YouTube 動画視聴サイト', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (5, 'https://techbowl.co.jp/techtrain', 'Techbowl', 'TechTrain | TechBowl', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (6, 'https://www.cyberagent.co.jp/', 'CyberAgent', '株式会社サイバーエージェント', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (7, 'https://dl.acm.org/', 'CyberAgent', 'ACM DL DIGITAL LIBRARY', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (8, 'https://app.diagrams.net/', 'Drow.io', 'EditPage Drow.io', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (9, 'https://golang.org/', 'GoLang', 'GoLang Documentation', '2020-10-22 14:57:14');

INSERT INTO linkdata (id, l_begin_time, l_end_time, l_content, l_date)
VALUES (10, 'https://www.php.net/manual/ja/index.php', 'PHP', 'PHP Documentation', '2020-10-22 14:57:14');

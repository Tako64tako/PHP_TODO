DROP TABLE IF EXISTS post;

CREATE TABLE post (
    title  text      NOT NULL,
    date   datetime  NOT NULL,
    name   text      NOT NULL,
    body   text      NOT NULL
);

INSERT INTO post (title, date, name, body) 
VALUES ('ようこそ掲示板へ', '2020-10-16 20:16:34', '管理人 RenGoto', 'ざっくばらんに会話を楽しみましょう。');
